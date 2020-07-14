<?php

	/**
	 *
	 */
	class NotificationsConnector extends PaymentsConnector {

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

			# Email
			include_once $site->baseDir('/external/mailer.inc.php');
			include_once $site->baseDir('/external/provider/mandrill.provider.php');

			$event = Events::getById($form->metas->event);
			$subject = "Confirmación de orden #{$order->id}";
			$message = "
			<html>
				<head>
					<title>Asistentes</title>
				</head>
				<body>";
					$body = "<h3>¡Hola {$order->metas->first_name} {$order->metas->last_name}!</h3>";
					$body .= "<p>Este es tu boleto para la entrada del {$event->name}, no olvides presentar tu boleto de manera impresa o digital al llegar al evento. En caso de que hayas comprado varios boletos, por favor, envía el nombre y apellido de cada uno de los asistentes al correo: eventos@growthinstitute.com.</p>";
					$body .= "<table style='border: 1px solid #ddd; text-align: left; border-collapse: collapse; width: 100%;'>"; //starts the table tag
					$body .= "<tr style='border: 1px solid #ddd; text-align: left; padding: 9px;'>
								<td style='border: 1px solid #ddd; text-align: left; padding: 9px; font-weight: bold;'>Orden</td>
								<td style='border: 1px solid #ddd; text-align: left; padding: 9px; font-weight: bold;'>Nombre</td>
								<td style='border: 1px solid #ddd; text-align: left; padding: 9px; font-weight: bold;'>Apellidos</td>
								<td style='border: 1px solid #ddd; text-align: left; padding: 9px; font-weight: bold;'>Correo</td>
								<td style='border: 1px solid #ddd; text-align: left; padding: 9px; font-weight: bold;'>Teléfono</td>
								<td style='border: 1px solid #ddd; text-align: left; padding: 9px; font-weight: bold;'>Compañia</td>
								<td style='border: 1px solid #ddd; text-align: left; padding: 9px; font-weight: bold;'>Total</td>
								<td style='border: 1px solid #ddd; text-align: left; padding: 9px; font-weight: bold;'>Producto</td>
								<td style='border: 1px solid #ddd; text-align: left; padding: 9px; font-weight: bold;'>Número de Boletos</td>
								<td style='border: 1px solid #ddd; text-align: left; padding: 9px; font-weight: bold;'>Folio</td>
								<td style='border: 1px solid #ddd; text-align: left; padding: 9px; font-weight: bold;'>QR</td>
							</tr>"; //sets headings
					for ($i=1; $i <= $order->metas->quantity; $i++) { //loops for each result

						$codigo = $hashids->encode($order->id.$i);
						$link = $site->urlTo('/backend/events/'.$form->metas->event.'/attendees/edit/'.$codigo.'', false);
						$body .= "<tr style='border: 1px solid #ddd; text-align: left; padding: 9px;'>
									<td style='border: 1px solid #ddd; text-align: left; padding: 9px;'>".$order->id."</td>
									<td style='border: 1px solid #ddd; text-align: left; padding: 9px;'>".$order->metas->first_name."</td>
									<td style='border: 1px solid #ddd; text-align: left; padding: 9px;'>".$order->metas->last_name."</td>
									<td style='border: 1px solid #ddd; text-align: left; padding: 9px;'>".$order->metas->email."</td>
									<td style='border: 1px solid #ddd; text-align: left; padding: 9px;'>".$order->metas->phone."</td>
									<td style='border: 1px solid #ddd; text-align: left; padding: 9px;'>".$order->metas->company."</td>
									<td style='border: 1px solid #ddd; text-align: left; padding: 9px;'>".$order->total."</td>
									<td style='border: 1px solid #ddd; text-align: left; padding: 9px;'>".$form->name."</td>
									<td style='border: 1px solid #ddd; text-align: left; padding: 9px;'>".$order->metas->quantity."</td>
									<td style='border: 1px solid #ddd; text-align: left; padding: 9px;'>".$codigo."</td>
									<td style='border: 1px solid #ddd; text-align: left; padding: 9px;'><img src='https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=".$link."&choe=UTF-8' alt='Code'></td>
								</tr>";
					}
					$body .= "</table>"; //closes the table
					$message .= $body . "
				</body>
			</html>";

			$msg = MailerMessage::newInstance()
			->setSubject($subject)
			->setFrom( ['no-reply@growthinstitute.com' => 'Growth Insitute'] )
			//->setTo([ $user->email => isset($user->first_name) ? $user->first_name : 'User' ])
			->setTo(['miguelangel.escamilla@thewebchi.mp' => 'Pruebas','rodrigo.tejero@thewebchi.mp' => 'Pruebas','eventos@growthinstitute.com' => 'Eventos', $order->metas->email => $order->metas->first_name])
			->setContents($message);

			$options = ['key' => 'ueOE6ry_1ECobiQCm7vh0A'];

			try {

				$mail = Mailer::send($msg, 'mandrill', $options);
				$result = 'success';

			} catch(Exception $e) {

				log_to_file($e->getMessage(), 'test_Email');
			}
		}
	}
?>