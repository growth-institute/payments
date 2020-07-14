<?php
	class HubSpotConnector extends PaymentsConnector {

		function replaceVars($var, $order) {

			if(is_string($var)) {

				$var = str_replace('%ORDER_TOTAL%', $order->total, $var);
				$var = str_replace('%FIRST_NAME%', $order->getMeta('first_name'), $var);
				$var = str_replace('%LAST_NAME%', $order->getMeta('last_name'), $var);
			}

			if(is_array($var)) {

				foreach($var as $k => $property) {

					$var[$k] = str_replace('%ORDER_TOTAL%', $order->total, $property);
					$var[$k] = str_replace('%FIRST_NAME%', $order->getMeta('first_name'), $property);
					$var[$k] = str_replace('%LAST_NAME%', $order->getMeta('last_name'), $property);
				}
			}

			return $var;
		}

		function process($order) {
			global $site;

			$credentials = array();
			$credentials['api_key'] = 'a32d646d-4819-4c40-89a9-dd9140ae9fda';

			$hubspot = HubSpot::newInstance($credentials);

			$form = PaymentsForms::getById( $order->getMeta('form') );
			$sku = $form ? json_decode($form->products) : null;
			$sku = $sku ? $sku : [0];

			$skus = implode(', ', $sku);

			$installments = $order->getMeta('installments', 0);
			$concept = $order->getMeta('concept', '');

			# Upsert a contact
			# First name, Last name, Email, Phone, Company
			$properties = array();
			$properties['email'] = $order->getMeta('email');

			if($order->getMeta('first_name')) $properties['firstname'] = $order->getMeta('first_name');
			if($order->getMeta('last_name')) $properties['lastname'] = $order->getMeta('last_name');

			$properties['company'] = $order->getMeta('company');
			$properties['phone'] = $order->getMeta('phone');
			$properties['su_cart_abondonment'] = $order->payment_status == 'Pending';

			$res = $hubspot->contactsUpsert($properties['email'], $properties);
			$vid = $res && isset($res->vid) ? $res->vid : false;
			$list_id = $form->getMeta('id_list');

			# GDPR Implementation
			if ($form->getMeta('gdpr')) {
				$properties = [];
				$properties['email'] = $order->getMeta('email');
				$properties['hs_legal_basis'] = 'Freely given consent from contact;Legitimate interest – existing customer';
				$res = $hubspot->contactsUpsert($properties['email'], $properties);
			}
			# Create the deal
			# Price, Product Name, SKU (If aplicable), Number of Installments (if applicable)
			if ($res && $order->payment_status != 'Pending') {

				$properties = array();
				$properties['dealtype'] = 'newbusiness';
				$properties['created_from_platform'] = true;
				$properties['sku'] = $sku[0];
				$properties['order_number'] = $order->id;
				$properties['closedate'] = strtotime($order->payment_date) * 1000;


				$generated_deal_name = "#{$order->id} {$concept} ({$skus})" . ($installments ? " - {$installments} Installments" : '');
				$generated_deal_name = str_replace(' ()', '', $generated_deal_name);

				$dealname = $form->getMeta('hubspot_deal_name', $generated_deal_name);
				$dealname = $this->replaceVars($dealname, $order);
				$properties['dealname'] = $dealname;

				$stage = $form->getMeta('hubspot_deal_stage', 'closedwon');
				$properties['dealstage'] = $stage;

				$pipeline = $form->getMeta('hubspot_deal_pipeline', 'default');
				$properties['pipeline'] = $pipeline;

				$amount = $form->getMeta('hubspot_deal_amount', $order->total);
				$properties['amount'] = $amount;

				$extra_properties = $form->getMeta('hubspot_deal_properties');
				if($extra_properties) {
					foreach($extra_properties as $pk => $property) {

						$property = $this->replaceVars($property, $order);
						$properties[$pk] = $property;
					}
				}

				$associations = array();
				$associations['associatedVids'] = array($res->vid);
				$res = $hubspot->dealsCreate($properties, $associations);
			}

			if($list_id && $vid) {

				$res = $hubspot->contactListsAddContact($list_id, $vid);
			}
		}
	}

?>