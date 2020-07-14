	<form action="" method="post">
		<div class="panel-wrapper fixed-right">
			<div class="panel-fixed">
				<div class="metabox">
					<div class="metabox-header">Properties</div>
					<div class="metabox-body">

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
							<input type="text" name="name" id="name" class="form-control input-block" value="<?php sanitized_print($item ? $item->name : ''); ?>">
						</div>
						<div class="form-group">
							<label for="description" class="control-label">Description</label>
						</div>
						<div class="form-group">
							<textarea name="description" id="description" class="form-control input-block" rows="10"><?php sanitized_print($item ? $item->description : ''); ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>