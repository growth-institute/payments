	<h2>User details</h2>
	<div class="tabs">
		<div class="tab active">
			<form action="" method="post">
				<div class="form-fields">
					<div class="row row-md row-5">
						<div class="col col-md-6">
							<div class="form-group">
								<label for="first_name" class="control-label">First name <span class="required">*</span></label>
								<input type="text" name="first_name" id="first_name" class="form-control input-block" value="<?php sanitized_print($order ? $order->getMeta('first_name') : ''); ?>" data-validate="required">
							</div>
						</div>
						<div class="col col-md-6">
							<div class="form-group">
								<label for="last_name" class="control-label">Last name <span class="required">*</span></label>
								<input type="text" name="last_name" id="last_name" class="form-control input-block" value="<?php sanitized_print($order ? $order->getMeta('last_name') : ''); ?>" data-validate="required">
							</div>
						</div>
					</div>
					<div class="row row-md row-5">
						<div class="col col-md-6">
							<div class="form-group">
								<label for="email" class="control-label">Email <span class="required">*</span></label>
								<input type="text" name="email" id="email" class="form-control input-block" value="<?php sanitized_print($order ? $order->getMeta('email') : ''); ?>" data-validate="required">
							</div>
						</div>
						<div class="col col-md-6">
							<div class="form-group">
								<label for="phone" class="control-label">Phone <span class="required">*</span></label>
								<input type="text" name="phone" id="phone" class="form-control input-block" value="<?php sanitized_print($order ? $order->getMeta('phone') : ''); ?>" data-validate="required">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="company" class="control-label">Company <span class="required">*</span></label>
						<input type="text" name="company" id="company" class="form-control input-block" value="<?php sanitized_print($order ? $order->getMeta('company') : ''); ?>" data-validate="required">
					</div>
				</div>
				<div class="form-actions">
					<button type="submit" class="button button-primary">Continue</button>
				</div>
			</form>
		</div>
	</div>