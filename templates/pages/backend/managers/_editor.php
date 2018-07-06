	<form action="" method="post">
		<div class="panel-wrapper fixed-right">
			<div class="panel-fixed">
				<div class="metabox">
					<div class="metabox-header">Properties</div>
					<div class="metabox-body">
						<div class="form-group">
							<label for="status" class="control-label">Status</label>
							<select name="status" id="status" class="form-control input-block" data-value="<?php sanitized_print($item ? $item->status : ''); ?>">
								<option value="Active">Active</option>
								<option value="Inactive">Inactive</option>
							</select>
						</div>
						<div class="text-right">
							<a href="<?php $site->urlTo("/backend/managers/", true); ?>" class="button button-link">Go back</a>
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
							<label for="email" class="control-label">Email</label>
							<input type="text" name="email" id="email" class="form-control input-block" value="<?php sanitized_print($item ? $item->email : ''); ?>">
						</div>
							<div class="form-group">
								<label for="login" class="control-label">User name</label>
								<input type="text" id="login" name="login" class="form-control input-block" value="<?php sanitized_print($item ? $item->login : ''); ?>">
							</div>
					
						<div class="form-group">
							<label for="nicename" class="control-label">Display name</label>
							<input type="text" name="nicename" id="nicename" class="form-control input-block" value="<?php sanitized_print($item ? $item->nicename : ''); ?>">
						</div>
						
							<div class="form-group">
								<label for="password" class="control-label">Password</label>
								<input type="password" name="password" id="password" class="form-control input-block" value="<?php sanitized_print($item ? $item->password : ''); ?>">
							</div>
							<div class="form-group">
								<label for="confirm" class="control-label">Confirm password</label>
								<input type="password" name="confirm" id="confirm" class="form-control input-block" value="<?php sanitized_print($item ? $item->password : ''); ?>">
							</div>
					</div>
				</div>
			</div>
		</div>
	</form>