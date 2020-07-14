<?php

	/**
	 *
	 */
	class EventsConnector extends PaymentsConnector {

		function process($order) {
			global $site;
			$order->fetchMetas();

			$form = PaymentsForms::getById($order->getMeta('form'));
			$form->fetchMetas();

			include_once $site->baseDir('/external/lib/hashids/Math.php');
			include_once $site->baseDir('/external/lib/hashids/HashidsException.php');
			include_once $site->baseDir('/external/lib/hashids/HashidsInterface.php');
			include_once $site->baseDir('/external/lib/hashids/Hashids.php');

			$hashids = new Hashids\Hashids($site->hashToken($form->name), 8, 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789');

			for ($i=1; $i <= $order->metas->quantity; $i++) {

				$code = $hashids->encode($order->id.$i);

				$event = Events::getById($form->metas->event);
				$order_event = new EventOrder();
				$order_event->id_order = $order->id;
				$order_event->id_event = $form->metas->event;
				$order_event->event_name = $event->name;
				$order_event->user_name = "{$order->metas->first_name} {$order->metas->last_name}";
				$order_event->user_email = $order->metas->email;
				$order_event->ticket_number = $i;
				$order_event->event_code = $code;
				$order_event->status = 'pending';
				$order_event->save();
			}
		}
	}
?>