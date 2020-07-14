	<form action="" method="post">
		<div class="panel-wrapper fixed-right">
			<div class="panel-fixed">
				<div class="metabox">
					<div class="metabox-header">Properties</div>
					<div class="metabox-body">
						<?php if($item): ?>
							<div class="form-group">
								<div class="text-right">
									<?php if($item->status == 'burned'):?>
										<input type="submit" name="unburned" class="button button-danger" value="Unburned" class="input-block">
									<?php else: ?>
										<input type="submit" name="burned" class="button button-danger" value="Burned" class="input-block">
									<?php endif; ?>
								</div>
								<div class="text-center">

									<img src="<?php echo 'https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl='.$site->urlTo('/backend/events/'.$item->id_event.'/attendees/edit/'.$item->event_code.'', false).'&choe=UTF-8'; ?>" alt="Code" class="img-responsive">
								</div>
								<div class="text-center">
									<label for="name" class="control-label"><?php sanitized_print($item ? $item->event_code : ''); ?></label>
								</div>
							</div>
						<?php endif; ?>
						<div class="text-right">
							<a href="<?php $site->urlTo("/backend/events/", true); ?>" class="button button-link">Go back</a>
							<button type="submit" class="button button-primary">Save changes</button>
						</div>
					</div>
				</div>
			</div>
			<div class="panel-fluid">
				<div class="metabox">
					<div class="metabox-header">Generals</div>
					<div class="metabox-body">
						<div class="form-group">
							<label for="name" class="control-label">Name</label>
							<input type="text" name="user_name" id="user_name" class="form-control input-block" value="<?php sanitized_print($item ? $item->user_name : ''); ?>">
						</div>
						<div class="form-group">
							<label for="description" name="user_email" class="control-label">Email</label>
						</div>
						<div class="form-group">
							<input type="email" name="user_email" id="email" class="form-control input-block" value="<?php sanitized_print($item ? $item->user_email : ''); ?>">
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>