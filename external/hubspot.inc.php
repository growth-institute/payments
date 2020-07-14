<?php

	class HubSpot {

		protected $api_url;
		protected $api_key;
		protected $api_user;
		protected $client_id;
		protected $client_secret;

		# Constructor -----------------------------------------------------------------------------

		protected function __construct($credentials) {
			$this->api_url = get_item($credentials, 'api_url', 'https://api.hubapi.com');
			$this->api_key = get_item($credentials, 'api_key');
			$this->api_user = get_item($credentials, 'api_user');
			$this->client_id = get_item($credentials, 'client_id');
			$this->client_secret = get_item($credentials, 'client_secret');
		}

		static function newInstance($credentials) {
			$new = new self($credentials);
			return $new;
		}

		# OAuth -----------------------------------------------------------------------------------

		function oauthToken($code) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/oauth/v1/token";
			#
			$headers = array();
			$headers['Content-Type'] = 'application/x-www-form-urlencoded';
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			#
			$fields = array();
			$fields['grant_type'] = 'authorization_code';
			$fields['client_id'] = $this->client_id;
			$fields['client_secret'] = $this->client_secret;
			$fields['redirect_uri'] = 'http://www.hubspot.com/';
			$fields['code'] = '4303bad8-8304-42f4-ad77-da7f53703134';
			#
			$curly = Curly::newInstance(false)
				->setMethod('post')
				->setURL($endpoint)
				->setParams($params)
				->setHeaders($headers)
				->setFields($fields)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		# Companies -------------------------------------------------------------------------------

		function companiesCreate($properties) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/companies/v2/companies";
			#
			$headers = array();
			$headers['Content-Type'] = 'application/json';
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			#
			$properties_arr = array();
			$properties_arr['properties'] = array();
			if ($properties) {
				foreach ($properties as $key => $value) {
					$properties_arr['properties'][] = array('name' => $key, 'value' => $value);
				}
			}
			$fields = json_encode($properties_arr);
			#
			$curly = Curly::newInstance(false)
				->setMethod('post')
				->setURL($endpoint)
				->setParams($params)
				->setHeaders($headers)
				->setFields($fields)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		function companiesUpdate($company_id, $properties) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/companies/v2/companies/{$company_id}";
			#
			$headers = array();
			$headers['Content-Type'] = 'application/json';
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			#
			$properties_arr = array();
			$properties_arr['properties'] = array();
			if ($properties) {
				foreach ($properties as $key => $value) {
					$properties_arr['properties'][] = array('name' => $key, 'value' => $value);
				}
			}
			$fields = json_encode($properties_arr);
			#
			$curly = Curly::newInstance(false)
				->setMethod('put')
				->setURL($endpoint)
				->setParams($params)
				->setHeaders($headers)
				->setFields($fields)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		function companiesGetById($company_id) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/companies/v2/companies/{$company_id}";
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			#
			$curly = Curly::newInstance(false)
				->setMethod('get')
				->setURL($endpoint)
				->setParams($params)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		function companiesGetByDomain($domain) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/companies/v2/companies/domain/{$domain}";
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			#
			$curly = Curly::newInstance(false)
				->setMethod('get')
				->setURL($endpoint)
				->setParams($params)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		function companiesAll($offset = '', $count = 20) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/companies/v2/companies/paged";
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			$params['offset'] = $offset;
			$params['count'] = $count;
			#
			$curly = Curly::newInstance(false)
				->setMethod('get')
				->setURL($endpoint)
				->setParams($params)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		function companiesGetContacts($company_id, $offset = '', $count = 20) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/companies/v2/companies/{$company_id}/contacts";
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			$params['vidOffset'] = $offset;
			$params['count'] = $count;
			#
			$curly = Curly::newInstance(false)
				->setMethod('get')
				->setURL($endpoint)
				->setParams($params)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		function companiesGetContactIds($company_id, $offset = '', $count = 20) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/companies/v2/companies/{$company_id}/vids";
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			$params['vidOffset'] = $offset;
			$params['count'] = $count;
			#
			$curly = Curly::newInstance(false)
				->setMethod('get')
				->setURL($endpoint)
				->setParams($params)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		function companiesAddContact($company_id, $contact_id, $offset = '', $count = 20) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/companies/v2/companies/{$company_id}/contacts/{$contact_id}";
			#
			$headers = array();
			$headers['Content-Type'] = 'application/json';
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			#
			$curly = Curly::newInstance(false)
				->setMethod('put')
				->setURL($endpoint)
				->setParams($params)
				->setFields('')
				->setHeaders($headers)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		function companiesRemoveContact($company_id, $contact_id, $offset = '', $count = 20) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/companies/v2/companies/{$company_id}/contacts/{$contact_id}";
			#
			$headers = array();
			$headers['Content-Type'] = 'application/json';
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			#
			$curly = Curly::newInstance(false)
				->setMethod('delete')
				->setURL($endpoint)
				->setParams($params)
				->setFields('')
				->setHeaders($headers)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		# Contacts --------------------------------------------------------------------------------

		function contactsUpsert($email, $properties) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/contacts/v1/contact/createOrUpdate/email/{$email}";
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			#
			$properties_arr = array();
			$properties_arr['properties'] = array();
			if ($properties) {
				foreach ($properties as $key => $value) {
					$properties_arr['properties'][] = array('property' => $key, 'value' => $value);
				}
			}
			$fields = json_encode($properties_arr);
			#
			$curly = Curly::newInstance(false)
				->setMethod('post')
				->setURL($endpoint)
				->setParams($params)
				->setFields($fields)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		function contactsGetById($contact_id) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/contacts/v1/contact/vid/{$contact_id}/profile";
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			#
			$curly = Curly::newInstance(false)
				->setMethod('get')
				->setURL($endpoint)
				->setParams($params)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		function contactsGetByEmail($email) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/contacts/v1/contact/email/{$email}/profile";
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			#
			$curly = Curly::newInstance(false)
				->setMethod('get')
				->setURL($endpoint)
				->setParams($params)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		function contactsAll($offset = '', $count = 20) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/contacts/v1/lists/all/contacts/all";
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			$params['count'] = $count;
			$params['vidOffset'] = $offset;
			#
			$curly = Curly::newInstance(false)
				->setMethod('get')
				->setURL($endpoint)
				->setParams($params)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		function contactsSearch($query, $offset = '', $count = 20) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/contacts/v1/search/query";
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			$params['q'] = $query;
			$params['offset'] = $offset;
			$params['count'] = $count;
			#
			$curly = Curly::newInstance(false)
				->setMethod('get')
				->setURL($endpoint)
				->setParams($params)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		# Contact Lists ---------------------------------------------------------------------------

		function contactListsGetById($list_id, $offset = '', $count = 20) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/contacts/v1/lists/{$list_id}";
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			#
			$curly = Curly::newInstance(false)
				->setMethod('get')
				->setURL($endpoint)
				->setParams($params)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		function contactListsAll($offset = '', $count = 20) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/contacts/v1/lists";
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			$params['count'] = $count;
			$params['offset'] = $offset;
			#
			$curly = Curly::newInstance(false)
				->setMethod('get')
				->setURL($endpoint)
				->setParams($params)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		function contactListsAllContacts($list_id, $offset = '', $count = 20) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/contacts/v1/lists/{$list_id}/contacts/all";
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			$params['count'] = $count;
			$params['vidOffset'] = $offset;
			#
			$curly = Curly::newInstance(false)
				->setMethod('get')
				->setURL($endpoint)
				->setParams($params)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		function contactListsAddContact($list_id, $contact_ids) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/contacts/v1/lists/{$list_id}/add";
			#
			$headers = array();
			$headers['Content-Type'] = 'application/json';
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			#
			$properties_arr = array();
			$properties_arr['vids'] = is_array($contact_ids) ? $contact_ids : array($contact_ids);
			$fields = json_encode($properties_arr);
			#
			$curly = Curly::newInstance(false)
				->setMethod('post')
				->setURL($endpoint)
				->setParams($params)
				->setHeaders($headers)
				->setFields($fields)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		function contactListsRemoveContact($list_id, $contact_ids) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/contacts/v1/lists/{$list_id}/remove";
			#
			$headers = array();
			$headers['Content-Type'] = 'application/json';
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			#
			$properties_arr = array();
			$properties_arr['vids'] = is_array($contact_ids) ? $contact_ids : array($contact_ids);
			$fields = json_encode($properties_arr);
			#
			$curly = Curly::newInstance(false)
				->setMethod('post')
				->setURL($endpoint)
				->setParams($params)
				->setHeaders($headers)
				->setFields($fields)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		# Timeline --------------------------------------------------------------------------------

		// function timelineEventTypeCreate($app_id, $properties) {
		// 	$ret = false;
		// 	#
		// 	$endpoint = "{$this->api_url}/integrations/v1/{$app_id}/timeline/event-types";
		// 	#
		// 	$headers = array();
		// 	$headers['Content-Type'] = 'application/json';
		// 	#
		// 	$params = array();
		// 	$params['hapikey'] = $this->api_key;
		// 	$params['userId'] = $this->api_user;
		// 	#
		// 	$fields = json_encode($properties);
		// 	#
		// 	$curly = Curly::newInstance(false)
		// 		->setMethod('post')
		// 		->setURL($endpoint)
		// 		->setParams($params)
		// 		->setHeaders($headers)
		// 		->setFields($fields)
		// 		->execute();
		// 	#
		// 	$res = $curly->getResponse('json');
		// 	#
		// 	if ($res) {
		// 		$ret = $res;
		// 	}
		// 	return $ret;
		// }

		// function timelineEventTypeUpdate($app_id, $event_type_id, $properties) {
		// 	$ret = false;
		// 	#
		// 	$endpoint = "{$this->api_url}/integrations/v1/{$app_id}/timeline/event-types/{$event_type_id}";
		// 	#
		// 	$headers = array();
		// 	$headers['Content-Type'] = 'application/json';
		// 	#
		// 	$params = array();
		// 	$params['hapikey'] = $this->api_key;
		// 	$params['userId'] = $this->api_user;
		// 	#
		// 	$fields = json_encode($properties);
		// 	#
		// 	$curly = Curly::newInstance(false)
		// 		->setMethod('put')
		// 		->setURL($endpoint)
		// 		->setParams($params)
		// 		->setHeaders($headers)
		// 		->setFields($fields)
		// 		->execute();
		// 	#
		// 	$res = $curly->getResponse('json');
		// 	#
		// 	if ($res) {
		// 		$ret = $res;
		// 	}
		// 	return $ret;
		// }

		// function timelineEventUpsert($app_id, $event_id, $object_id, $event_type_id, $extra_data) {
		// 	$ret = false;
		// 	#
		// 	$endpoint = "{$this->api_url}/integrations/v1/{$app_id}/timeline/event";
		// 	#
		// 	$headers = array();
		// 	$headers['Content-Type'] = 'application/json';
		// 	#
		// 	$params = array();
		// 	$params['hapikey'] = $this->api_key;
		// 	#
		// 	$properties_arr = array();
		// 	$properties_arr['id'] = $event_id;
		// 	$properties_arr['objectId'] = $object_id;
		// 	$properties_arr['eventTypeId'] = $event_type_id;
		// 	$fields = json_encode($properties_arr);
		// 	#
		// 	$curly = Curly::newInstance(false)
		// 		->setMethod('put')
		// 		->setURL($endpoint)
		// 		->setParams($params)
		// 		->setHeaders($headers)
		// 		->setFields($fields)
		// 		->execute();
		// 	#
		// 	$res = $curly->getResponse('json');
		// 	#
		// 	if ($res) {
		// 		$ret = $res;
		// 	}
		// 	return $ret;
		// }

		# Deals -----------------------------------------------------------------------------------

		function dealsGetById($deal_id) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/deals/v1/deal/{$deal_id}";
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			#
			$curly = Curly::newInstance(false)
				->setMethod('get')
				->setURL($endpoint)
				->setParams($params)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		function dealsGetAssociated($object_type, $object_id, $offset = '', $count = 20) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/deals/v1/deal/associated/{$object_type}/{$object_id}/paged";
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			$params['offset'] = $offset;
			$params['limit'] = $count;
			$params['properties'] = 'dealname';
			$params['includeAssociations'] = 'true';
			#
			$curly = Curly::newInstance(false)
				->setMethod('get')
				->setURL($endpoint)
				->setParams($params)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		function dealsCreate($properties, $associations) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/deals/v1/deal";
			#
			$headers = array();
			$headers['Content-Type'] = 'application/json';
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			#
			$properties_arr = array();
			$properties_arr['properties'] = array();
			if ($properties) {
				foreach ($properties as $key => $value) {
					$properties_arr['properties'][] = array('name' => $key, 'value' => $value);
				}
			}
			if ($associations) {
				$properties_arr['associations'] = $associations;
			}
			$fields = json_encode($properties_arr);
			#
			$curly = Curly::newInstance(false)
				->setMethod('post')
				->setURL($endpoint)
				->setParams($params)
				->setHeaders($headers)
				->setFields($fields)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		function dealsUpdate($deal_id, $properties) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/deals/v1/deal/{$deal_id}";
			#
			$headers = array();
			$headers['Content-Type'] = 'application/json';
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			#
			$properties_arr = array();
			$properties_arr['properties'] = array();
			if ($properties) {
				foreach ($properties as $key => $value) {
					$properties_arr['properties'][] = array('name' => $key, 'value' => $value);
				}
			}
			$fields = json_encode($properties_arr);
			#
			$curly = Curly::newInstance(false)
				->setMethod('put')
				->setURL($endpoint)
				->setParams($params)
				->setHeaders($headers)
				->setFields($fields)
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		function dealsAssociate($deal_id, $object_type, $object_id) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/deals/v1/deal/{$deal_id}/associations/{$object_type}";
			#
			$headers = array();
			$headers['Content-Type'] = 'application/json';
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			$params['id'] = $object_id;
			#
			$curly = Curly::newInstance(false)
				->setMethod('put')
				->setURL($endpoint)
				->setParams($params)
				->setHeaders($headers)
				->setFields('')
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}

		function dealsRemoveAssociation($deal_id, $object_type, $object_id) {
			$ret = false;
			#
			$endpoint = "{$this->api_url}/deals/v1/deal/{$deal_id}/associations/{$object_type}";
			#
			$headers = array();
			$headers['Content-Type'] = 'application/json';
			#
			$params = array();
			$params['hapikey'] = $this->api_key;
			$params['id'] = $object_id;
			#
			$curly = Curly::newInstance(false)
				->setMethod('delete')
				->setURL($endpoint)
				->setParams($params)
				->setHeaders($headers)
				->setFields('')
				->execute();
			#
			$res = $curly->getResponse('json');
			#
			if ($res) {
				$ret = $res;
			}
			return $ret;
		}
	}

?>