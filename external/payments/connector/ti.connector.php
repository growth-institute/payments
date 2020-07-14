<?php

	class TIConnector extends PaymentsConnector {

		function process($order) {
			global $site;
			$order->fetchMetas();
			$form = PaymentsForms::getById($order->getMeta('form'));
			$form->fetchMetas();

			log_to_file("Order ============", 'ti');
			log_to_file(print_r($order, 1), 'ti');

			$params = [];
			$first_name = $order->metas->first_name;
			$last_name= $order->metas->last_name;
			$email = $order->metas->email;
			$form_name = $form->name;
			$password = generate_password(8);
			$products = json_decode($form->products);
			$products = array_filter($products);
			$locale = $form->language;

			$courses = [];
			$licenses = [];

			if($form->getMeta('notify_team')) {

				# Email
				include $site->baseDir('/external/mailer.inc.php');
				include $site->baseDir('/external/provider/mandrill.provider.php');

				$content = "<h2>" . $form->getMeta('notify_team_subject', 'Payments Email notification') . "</h2>" .
							"<p>¡Good day amigos!</p><p>A new sweet sign up to the Edge + payment form ({$form->id}) has been made. Here the details:</p>" .
							"<ul>
								<li><strong>Name:</strong> {$first_name} {$last_name}</li>
								<li><strong>Email:</strong> {$email}</li>
								<li><strong>Form:</strong> {$form_name}</li>
							</ul>
							<p>Have a good day and remember to wash your hands.</p>
							<p>Best regards, the GI Courier.</p>";

				$message = MailerMessage::newInstance()
				->setSubject($form->getMeta('notify_team_subject', 'Payments Email notification'))
				->setFrom( ['no-reply@growthinstitute.com' => 'Growth Institute'] )
				->setTo(['rodrigo.tejero@chimp.mx' => 'Rodrigo Tejero', 'cash@growthinstitute.com' => 'The Edge - Growth Institute'])
				->setContents($content);

				$options = ['key' => '2723e0df-e759-49a7-970e-eb2de33bd9f2'];
				Mailer::send($message, 'mandrill', $options);
			}

			if($courses) {

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

				$fields_email = [
					'send_email' => 1,
					'password' => $password,
					'email' => $email,
					'form_name' => $form_name,
					'language' => $locale
				];

				log_to_file(print_r($fields, 1), 'ti');
				log_to_file(print_r($fields_email, 1), 'ti');
				log_to_file(print_r($products, 1), 'ti');

				$curly = Curly::newInstance(false)
				->setMethod('post')
				->setURL('https://learn.growthinstitute.com/admin/ti-create-email')
				->setFields($fields_email)
				->execute();

				$res = $curly->getResponse('json');

				if($first_name && $last_name && $email && $password) {

					$curly = Curly::newInstance(false)
						->setMethod('post')
						->setURL('https://learn.growthinstitute.com/admin/ti-create')
						->setFields($fields)
						->execute();

					$json = file_get_contents("https://growthinstitute.com/ti/fetch/users/{$email}");
					$res = $curly->getResponse('json');
				}
			}
		}
	}
?>