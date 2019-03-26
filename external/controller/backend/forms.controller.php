<?php
class BackendFormsController extends Controller{
	function init(){
		//
	}
	function getSubControllerName($base_name){
		return "BackendForms{$base_name}";
	}
/*Create endpoint to check if a slug exists in db*/
	function slugCheckAction() {
		global $site;
		$request = $site->getRequest();
		$response = $site->getResponse();
		$dbh = $site->getDatabase();
		$name = $request->post('name');
		$id = $request->post('id');
		$slug = $site->toAscii($name);
		$data = [];
		$data['name'] = $name;
		$result = 'error';
		$message = 'This slug already exist';
		$form = PaymentsForms::getBySlug($slug, ['conditions' => " AND id != {$id}"]);
		if (!$form) {
			$message = 'Need a new slug';
			$result = 'success';
			$data['slug'] = $slug;
		}
		return $response->ajaxRespond($result, $data, $message);
	}
/*Create endpoint to check the extension of image*/
function checkImageAction(){
		global $site;
		$request = $site->getRequest();
		$response = $site->getResponse();
		$result = 'error';
		$data = [];
		$message = '';
		$allowed = array('png','jpg','jpeg','gif');
		$product_image = $request->files('file');
		$ext = pathinfo($product_image['name'], PATHINFO_EXTENSION);
		if (!in_array($ext, $allowed)) {
			$message = "This file extension is not allowed";
		} else {
			$attachment = Attachments::upload($product_image);
			if ($attachment) {
				$attachment_image = Attachments::getById($attachment->id);
				$data['attachment'] = $attachment_image;
				$result = "success";
			}
		}
		return $response->ajaxRespond($result, $data, $message);
	}
	function indexAction(){
		global $site;
			$request = $site->getRequest();
			$response = $site->getResponse();
			$this->requireUser();
			$dbh = $site->getDatabase();
			$search = $request->param('search', '');
			$search_products = $request->param('search_products', '');
			$search_language = $request->param('language', '');
			$search_currency = $request->param('currency', '');
			$search_subscription = $request->param('subscription', '');
			$show = $request->param('show', 40);
			$page = $request->param('page', 1);
			$search_s = $dbh->quote("%{$search}%");
			$search_sproducts = $dbh->quote("%{$search_products}%");
			$conditions = '1';
			$conditions .= $search ? " AND (name LIKE {$search_s} OR slug LIKE {$search_s})" : '';
			$conditions .= $search_products ? " AND (products LIKE {$search_sproducts})" : '';
			$conditions .= $search_language == 'English' ? " AND (language = 'English')" : '';
			$conditions .= $search_language == 'Spanish' ? " AND (language = 'Spanish')" : '';
			$conditions .= $search_currency && in_array('mxn', $search_currency) ?  " AND (currency = 'mxn')" : '';
			$conditions .= $search_currency && in_array('usd', $search_currency) ? " AND (currency = 'usd')" : '';
			$conditions .= $search_subscription == 'Yes' ? " AND (subscription = 'Yes')" : '';
			$conditions .= $search_subscription == 'No' ? " AND (subscription = 'No')" : '';
			$params = [];
			$params['show'] = $show;
			$params['page'] = $page;
			$params['conditions'] = $conditions;
			$items = PaymentsForms::all($params);
			$total = PaymentsForms::count($conditions);
			$data = [];
			$data['items'] = $items;
			$data['total'] = $total;
			$data['search'] = $search;
			$data['search_products'] = $search_products;
			$data['search_language'] = $search_language;
			$data['search_currency'] = $search_currency;
			$data['search_subscription'] = $search_subscription;
			$data['show'] = $show;
			//redirect url to page index
			$site->render('backend/forms/page-index', $data);
			return $response->respond();
	}
	function newAction(){
		global $site;
		$request = $site->getRequest();
		$response = $site->getResponse();
		$this->requireUser();
		switch ($request->type) {
			case 'get':
			//creating a variable to send data in url page-new
				$notice = Flasher::notice();
				$data = [];
				$data['notice'] = $notice;
				$site->render('backend/forms/page-new', $data);
				break;
			case 'post':
			//getting data post to send them to the db
				$name = $request->post('name');
				$slug = $request->post('slug');
				$products = $request->post('products');
				$language = $request->post('language');
				$processor = $request->post('processor');
				$processor = array_unique($processor);
				$currency = $request->post('currency');
				$total = $request->post('total');
				$subscription = $request->post('subscription');
				//MetaPost
				$quantity = $request->post('quantity');
				$quantity_value = $request->post('quantity_value');
				$extra_seats_price = $request->post('extra_seats_price');
				$time_to_live = $request->post('time_to_live');
				$thank_you_page = $request->post('thank_you_page');
				$product_description = $request->post('product_description');
				$growsumo = $request->post('growsumo');
				$gdpr = $request->post('gdpr');
				$product_image = $request->post('product_image');
				$periodicity = $request->post('periodicity');
				$ocurrency = $request->post('ocurrency');
				$installments = $request->post('installments');
				$from = $request->post('from');
				$to = $request->post('to');
				$val = $request->post('val');
				$type = $request->post('type');
				$coupon_subscription = $request->post('coupon_subscription');
				$array_discount = false;
				if(is_array($from)) {
					$array_discount = [];
					for($x = 0; $x < count($from); $x++) {
						$array_discount[] = [
							'from' => $from[$x],
							'to' => $to[$x],
							'val' => $val[$x],
							'type' => $type[$x]
						];
					}
				}
				//creating an object validator and added some rules
				$validator = Validator::newInstance()
				->addRule('Name', $name)
				->addRule('Slug', $slug)
				->addRule('Language', $language)
				->addRule('Processor', $processor)
				->addRule('Currency', $currency)
				->validate();
				//check the result
				if(! $validator->isValid() ){
					//add Flasher class to show errors
					Flasher::notice('The following fields are required: ' . implode(', ', $validator->getErrors()));
					$site->redirectTo($site->urlTo('/backend/forms/new'));
				}
				$form = new PaymentsForm();
				$form->name = $name;
				$form->slug = $slug;
				$explode = explode(',', $products);
				foreach ($explode as $data) {
					if ($data == ' ') {
						unset($data);
					} else {
						trim($data);
					}
				}
				$form->products = json_encode($explode);
				$form->language = $language;
				$form->processor = json_encode($processor);
				$form->currency = $currency;
				$form->total = $total;
				$form->subscription = $subscription;
				$form->save();
				//Saving metas to DB
				$form->updateMeta('quantity', $quantity);
				$form->updateMeta('quantity_value', $quantity_value);
				$form->updateMeta('extra_seats_price', $extra_seats_price);
				$form->updateMeta('time_to_live', $time_to_live);
				$form->updateMeta('thank_you_page', $thank_you_page);
				$form->updateMeta('product_description', $product_description);
				$form->updateMeta('growsumo', $growsumo);
				$form->updateMeta('gdpr', $gdpr);
				$form->updateMeta('product_image', $product_image);
				$form->updateMeta('periodicity', $periodicity);
				$form->updateMeta('ocurrency', $ocurrency);
				$form->updateMeta('installments', $installments);
				$form->updateMeta('discounts', $array_discount);
				$form->updateMeta('coupon_subscription', $coupon_subscription);
				//$site->redirectTo($site->urlTo('/backend/forms?msg=220'));
				$site->redirectTo($site->urlTo("/backend/forms/edit/{$form->id}?msg=220"));
			break;
		}
		return $response->respond();
	}
	function editAction($id){
		global $site;
		$request = $site->getRequest();
		$response = $site->getResponse();
		$this->requireUser();
		$params = array();
		//getting data with metas
		$params['pdoargs'] = array('fetch_metas' => 1);
		$form = PaymentsForms::getById($id, $params);
		//Validate if there isn't data redirect to index forms
		if(!$form) {
			$site->redirectTo( $site->urlTo('/backend/forms/') );
		}
		switch($request->type){
			case 'get':
			//create an object Flasher to send massage with url
				$notice = Flasher::notice();
				$data = [];
				$data['item'] = $form;
				$data['notice'] = $notice;
				$site->render('backend/forms/page-edit', $data);
			break;
			case 'post':
			//getting data post to send them DB
				$name = $request->post('name');
				$slug = $request->post('slug');
				$products = $request->post('products');
				$language = $request->post('language');
				$processor = $request->post('processor');
				$processor = array_unique($processor);
				$currency = $request->post('currency');
				$total = $request->post('total');
				$subscription = $request->post('subscription');
				//MetaPost
				$quantity = $request->post('quantity');
				$quantity_value = $request->post('quantity_value');
				$extra_seats_price = $request->post('extra_seats_price');
				$time_to_live = $request->post('time_to_live');
				$thank_you_page = $request->post('thank_you_page');
				$product_description = $request->post('product_description');
				$growsumo = $request->post('growsumo');
				$gdpr = $request->post('gdpr');
				$product_image = $request->post('product_image');
				$periodicity = $request->post('periodicity');
				$ocurrency = $request->post('ocurrency');
				$installments = $request->post('installments');
				$from = $request->post('from');
				$to = $request->post('to');
				$val = $request->post('val');
				$type = $request->post('type');
				$coupon_subscription = $request->post('coupon_subscription');
				$array_discount = false;
				if(is_array($from)) {
					$array_discount = [];
					for($x = 0; $x < count($from); $x++) {
						$array_discount[] = [
							'from' => $from[$x],
							'to' => $to[$x],
							'val' => $val[$x],
							'type' => $type[$x]
						];
					}
				}
				//creating an object validator and added some rules
				$validator = Validator::newInstance()
				->addRule('name',$name)
				->addRule('slug',$slug)
				->addRule('language',$language)
				->addRule('processor',$processor)
				->addRule('currency',$currency)
				->addRule('total',$total)
				->validate();
				//check the result
				if(! $validator->isValid() ){
					//add Flasher class to send message with errors
					Flasher::notice('The following fields are required: ' . implode(',',$validator->getErrors()));
					//redirect url to current form
					$site->redirectTo($site->urlTo("/backend/forms/edit/{$form->id}"));
				}
				//updating data
				$explode = explode(',', $products);
				$form->name = $name;
				$form->slug = $slug;
				$form->products = json_encode($explode);
				$form->language = $language;
				$form->processor = json_encode($processor);
				$form->currency = $currency;
				$form->total = $total;
				$form->subscription = $subscription;
				$form->save();
				//Updating metas to DB
				$form->updateMeta('quantity',$quantity);
				$form->updateMeta('quantity_value',$quantity_value);
				$form->updateMeta('extra_seats_price', $extra_seats_price);
				$form->updateMeta('time_to_live', $time_to_live);
				$form->updateMeta('thank_you_page', $thank_you_page);
				$form->updateMeta('product_description', $product_description);
				$form->updateMeta('growsumo', $growsumo);
				$form->updateMeta('gdpr', $gdpr);
				$form->updateMeta('product_image', $product_image);
				$form->updateMeta('periodicity', $periodicity);
				$form->updateMeta('ocurrency', $ocurrency);
				$form->updateMeta('installments', $installments);
				$form->updateMeta('discounts', $array_discount);
				$form->updateMeta('coupon_subscription', $coupon_subscription);
				$site->redirectTo($site->urlTo("/backend/forms/edit/{$form->id}?msg=220"));
			break;
		}
		return $response->respond();
	}
	function deleteAction($id){
		global $site;
		$request = $site->getRequest();
		$response = $site->getResponse();
		//getting all data per id
		$form = PaymentsForms::getById($id);
		if (! $form){
			$site->redirectTo($site->urlTo('/backend/forms/'));
		}
		switch ($request->type) {
			case 'get':
			//get data to send them in page-delete
				$data = array();
				$data['item'] = $form;
				$site->render('backend/forms/page-delete',$data);
			break;
			case 'post':
				//delete data
				$form->delete();
				$site->redirectTo($site->urlTo('/backend/forms?msg=230'));
			break;
		}
		return $response->respond();
	}
}
?>