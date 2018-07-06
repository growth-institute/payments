<form action="" method="post">
		<div class="panel-wrapper fixed-right">
			<div class="panel-fixed">
				<div class="metabox">
					<div class="metabox-header">Properties</div>
					<div class="metabox-body">
						<div class="form-group">
							<label for="status" class="control-label">Status</label>
							<!--<select name="status" id="status" class="form-control input-block" data-value="<?php sanitized_print($item ? $item->status : ''); ?>">
								<option value="Active">Active</option>
								<option value="Inactive">Inactive</option>
							</select>-->
						</div>
						<div class="text-right">
							<a href="<?php $site->urlTo("/backend/formsmeta/", true); ?>" class="button button-link">Go back</a>
							<button type="submit" class="button button-primary">Save changes</button>
						</div>
					</div>
				</div>
			</div>
			<div class="panel-fluid">
				<div class="metabox">
					<div class="metabox-header">Forms</div>
						<div class="metabox-body">
							<div class="form-group">
								<label for="id_form" class="control-label">ID Form</label>
								<input type="text" name="id_form" id="id_form" class="form-control input-block" value="<?php sanitized_print($item ? $item->id_form : ''); ?>">
							</div>
								<div class="form-group">
									<label for="name" class="control-label">Name</label>
									<input type="text" id="name" name="name" class="form-control input-block" value="<?php sanitized_print($item ? $item->name : ''); ?>">
								</div>
							<div class="form-group">
								<label for="value" class="control-label">Value</label>
								<input type="text" name="value" id="value" class="form-control input-block" value="<?php sanitized_print($item ? $item->value : ''); ?>">
							</div>
							<!--<div class="form-group">
								<label for="quantity">Quantity</label>
								<input type="text" name="name" id="quantity" class="form-control input-block">
							</div>-->
						</div>
					</div>
				</div>
			</div>
	</form>