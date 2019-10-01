<?php

	class TIConnector extends PaymentsConnector {

		function process($order) {
			global $site;
			$order->fetchMetas();
			$form = PaymentsForms::getById($order->getMeta('form'));
			$form->fetchMetas();

			$params = [];
			$first_name = $order->metas->first_name;
			$last_name= $order->metas->last_name;
			$email = $order->metas->email;
			$form_name = $form->name;
			$password = generate_password(8);
			$products = json_decode($form->products);
			$params['locale'] = $form->language;

			$courses = [];
			$licenses = [];

			foreach ($products as $product) {

				$product_parts = explode('-', $product);
				if (get_item($product_parts, 0) == 'license') {

					$product_replace = str_replace('license-', '', $product);
					$licenses[] = trim($product_replace);
				} else if (get_item($product_parts, 0) == 'course') {

					$product_replace = str_replace('course-', '', $product);
					$courses[] = trim($product_replace);
				}
			}

			$fields = [
				'first_name' => $first_name,
				'last_name' => $last_name,
				'email' => $email,
				'password' => $password,
				'id_license' => $licenses,
				'ti_courses' => $courses
			];

			if($first_name && $last_name && $email && $password) {

					$curly = Curly::newInstance(false)
					->setMethod('post')
					->setURL('https://learn.growthinstitute.com/admin/ti-create')
					->setFields($fields)
					->execute();

					$json = file_get_contents("https://growthinstitute.com/ti/fetch/users/{$email}");
					$res = $curly->getResponse('json');
			}

			$fields_email = [
				'send_email' => 1,
				'password' => $password,
				'email' => $email,
				'form_name' => $form_name
			];

			$curly = Curly::newInstance(false)
			->setMethod('post')
			->setURL('https://learn.growthinstitute.com/admin/ti-create-email')
			->setFields($fields_email)
			->execute();

			$res = $curly->getResponse('json');

		}
	}
?>