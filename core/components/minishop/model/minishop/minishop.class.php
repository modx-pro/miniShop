<?php
/**
 * miniShop
 *
 * Copyright 2012 by Vasiliy Naumkin <bezumkin@yandex.ru>
 *
 * miniShop is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * miniShop is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * miniShop; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package minishop
 */
/**
 * The base class for miniShop.
 *
 * @package minishop
 */
class miniShop {


	/*
	 * Construction of class
	 *
	 * @param class modX
	 * @param array $config
	 * */
    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;

        $corePath = $this->modx->getOption('minishop.core_path',$config,$this->modx->getOption('core_path').'components/minishop/');
        $assetsUrl = $this->modx->getOption('minishop.assets_url',$config,$this->modx->getOption('assets_url').'components/minishop/');
        $connectorUrl = $assetsUrl.'connector.php';
        $connectorsUrl = $assetsUrl.'connectors/';

        $this->config = array_merge(array(
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl.'css/',
            'jsUrl' => $assetsUrl.'js/',
            'imagesUrl' => $assetsUrl.'images/',

            'connectorUrl' => $connectorUrl,
            'connectorsUrl' => $connectorsUrl,

            'corePath' => $corePath,
            'modelPath' => $corePath.'model/',
            'chunksPath' => $corePath.'elements/chunks/',
            'chunkSuffix' => '.tpl',
            'snippetsPath' => $corePath.'elements/snippets/',
            'processorsPath' => $corePath.'processors/',
			'ms_categories_tpls' => explode(',', $this->modx->getOption('minishop.categories_tpl', '', 1)),
			'ms_goods_tpls' => explode(',', $this->modx->getOption('minishop.goods_tpl', '', 1)),
			'ms_kits_tpls' => explode(',', $this->modx->getOption('minishop.kits_tpl', '', 1)),
			'ms_status_new' => $this->modx->getOption('minishop.status_new', '', 1)
        ),$config);

        $this->modx->addPackage('minishop',$this->config['modelPath'], $this->modx->config['table_prefix'].'ms_');
        $this->modx->lexicon->load('minishop:default');
        $this->modx->lexicon->load('minishop:add');

		// Show errors if debug enabled
		if (isset($this->config['debug']) && $this->config['debug']) {
			ini_set('display_errors', 1); 
			error_reporting(E_ALL ^ E_NOTICE);
		}

		// Default session variables for miniShop
		if (!isset($_SESSION['minishop']['warehouse'])) {$_SESSION['minishop']['warehouse'] = $this->getDefaultWarehouse();}
		if (!isset($_SESSION['minishop']['category'])) {$_SESSION['minishop']['category'] = 0;}
		if (!isset($_SESSION['minishop']['status'])) {$_SESSION['minishop']['status'] = 0;}
    }

	/**
	 * Initializes miniShop into different contexts.
	 *
	 * @access public
	 * @param string $ctx The context to load. Defaults to web.
	 */
	public function initialize($ctx = 'web') {
		switch ($ctx) {
			case 'mgr':
				$this->config['statuses'] = json_encode($this->getStatusesArray());
				if (!$this->modx->loadClass('minishop.request.miniShopControllerRequest',$this->config['modelPath'],true,true)) {
					return 'Could not load controller request handler.';
				}
				$this->request = new miniShopControllerRequest($this);
				return $this->request->handleRequest();
			break;
			default: break;
		}
	}

 
	/*
	 * Print human-readable array.
	 * Used in the development.
	 *
	 * @param array $arr
	 * @ignore
	 * */
	function print_arr($arr = array()) {
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}


	/*
	 * Receive indexed array with values and returns 2 arrays with placeholders and values
	 *
	 * @param array $arr
	 * @returns array $tmp
	 * */
	function makePlaceholders($arr = array()) {
		$tmp = array();
		foreach ($arr as $k => $v) {
			$tmp['pl'][$k] = '[[+'.$k.']]';
			$tmp['vl'][$k] = $v;
		}
		return $tmp;
	}


	/*
	 * Function for logging events in table ModLog
	 *
	 * @param string $type			// any type, like warehouse, goods, status etc
	 * @param int $oid				// id of the order, which owns the record
	 * @param int $iid				// id of the item for this entry. Used for logging changing of the goods
	 * @param string $operation		// change, create, etc
	 * @param string $old			// old value
	 * @param string $new			// new value
	 * @param string $comment
	 * */
	function Log($type, $oid, $iid, $operation, $old, $new, $comment = '') {
		if ($old == $new) {return;}
		$uid = empty($this->modx->user->id) ? 1 : $this->modx->user->id;
		$res = $this->modx->newObject('ModLog');
		$res->set('uid', $uid);
		$res->set('type', $type);
		$res->set('oid', $oid);
		$res->set('iid', $iid);
		$res->set('operation', $operation);
		$res->set('old', $old);
		$res->set('new', $new);
		$res->set('ip', $_SERVER['REMOTE_ADDR']);
		$res->set('comment', $comment);
		$res->save();
	}


	/*
	 * Get array of statuses from ModStatus
	 * Used for render statuses in manager
	 *
	 * @returns array $arr
	 * */
	function getStatusesArray() {
		$arr = array();
		if ($tmp = $this->modx->getCollection('ModStatus')) {
			foreach ($tmp as $v) {
				$arr[$v->get('id')] = array('name' => $v->get('name'), 'color' => $v->get('color'));
			}
		}
		return $arr;
	}


	/*
	 * Function returns id of warehouse for this user
	 *
	 * @returns int $id					// id of entry in ModWarehouse
	 * */
	function getDefaultWarehouse() {
		// If variable exists in $_SESSION - return it
		if (!empty($_SESSION['minishop']['warehouse'])) {
			return $_SESSION['minishop']['warehouse'];
		}
		//If no - calculate
		$q = $this->modx->newQuery('ModWarehouse');
		$q->sortby('name', 'ASC');
		$tmp = $this->modx->getCollection('ModWarehouse', $q);
		foreach ($tmp as $v) {
			// Check required permission for this warehouse
			$permission = $v->get('permission');
			if (!empty($permission) && !$this->modx->hasPermission($permission)) {
				continue;
			}
			else {
				return $v->get('id');
			}
		}
		// if there are no correct warehouses - return 1, because we need not empty value in $_SESSION
		return 1;
	}


	/*
	 * Function returns errors array
	 * Mostly used for ajax requests.
	 *
	 * @param string $msg				// key of lexicon entry
	 * @param array $data				// any additional data for response
	 * @param array $pl					// placeholders for lexicon entry
	 * @returns array $data				// id of entry in ModWarehouse
	 * */
	function error($msg, $data = array(), $pl = array()) {
		$data['status'] = 'error';
		$data['message'] = $this->modx->lexicon($msg, $pl);
		return $data;
	}


	/*
	 * Function returns success array
	 * Mostly used for ajax requests.
	 *
	 * @param string $msg				// key of lexicon entry
	 * @param array $data				// any additional data for response
	 * @param array $pl					// placeholders for lexicon entry
	 * @returns array $data				// id of entry in ModWarehouse
	 * */
	function success($msg, $data = array(), $pl = array()) {
		$data['status'] = 'success';
		$data['message'] = $this->modx->lexicon($msg, $pl);
		return $data;
	}


	/*
	 * Selects ids of resources for parents by the links in ModCategory
	 * It is not an getChildIds variant. It is function for multi-categories feature.
	 *
	 * @param ararys $parents				// array of ids of categories
	 * @returns array $ids					// array of ids matched resources
	 * */
	function getGoodsByCategories($parents = array()) {
		if (empty($parents)) {$parents = array($this->modx->resource->id);}
		if (!is_array($parents)) {$parents = explode(',', $parents);}
		// Поиск подходящих ресурсов через связи в ModCategories
		$ids = array();
		if ($res = $this->modx->getCollection('ModCategories', array('cid:IN' => $parents))) {
			foreach ($res as $v) {
				$ids[] = $v->get('gid');
			}
		}
		return $ids;
	}


	/*
	 * This function prepares and returns chunk for list of customers orders on fronend
	 *
	 * @returns string $chunk
	 * */
	function getMyOrdersList() {
		$arr = array(
			'config' => $this->modx->toJSON(
				array(
					'status' => $_SESSION['minishop']['status']
					,'statuses' => $this->getStatusesArray()
				)
			)
			,'connector_url' => $this->modx->makeUrl($this->modx->resource->id, '', '', 'full')
			,'connectors_url' => $this->config['connectorsUrl']
		);
		
		
		return $this->modx->getChunk($this->config['tplMyOrdersList'], $arr);
	}


	/*
	 * Returns array with current cart status
	 *
	 * @returns array $arr 					// array with total weight, price, and number of goods
	 * */
	function getCartStatus() {
		$cart = $_SESSION['minishop']['goods'];
		if (empty($cart)) {$cart = array();}
		
		$arr = array();
		$arr['total'] = 0;
		$arr['count'] = 0;
		$arr['weight'] = 0;
		foreach ($cart as $v) {
			$arr['count'] += $v['num'];
			$arr['total'] += $v['price'] * $v['num'];
			$arr['weight'] += $v['weight'] * $v['num'];
		}
		return $arr;
	}


	/*
	 * Gets price of product
	 * If it's set snippet in system setting minishop.getprice_snippet - runs it
	 *
	 * @returns int $price
	 * */
	function getPrice($id) {
		$snippet = $this->modx->getOption('minishop.getprice_snippet');
		if (!empty($snippet)) {
			if ($res = $this->modx->getObject('modResource', $id)) {
				$price = $this->modx->runSnippet($snippet, array('resource' => $res));
			}
			else {$price = 0;}
		}
		else {
			if ($res = $this->modx->getObject('ModGoods', array('gid' => $id, 'wid' => $_SESSION['minishop']['warehouse']))) {
				$price = $res->get('price');
			}
			else {$price = 0;}
		}
		return $price;
	}


	/*
	 * Gets weight of product
	 * If it's set snippet in system setting minishop.getweight_snippet - runs it
	 *
	 * @returns int $weight
	 * */
	function getWeight($id) {
		$snippet = $this->modx->getOption('minishop.getweight_snippet');
		if (!empty($snippet)) {
			if ($res = $this->modx->getObject('modResource', $id)) {
				$weight = $this->modx->runSnippet($snippet, array('resource' => $res));
			}
			else {$weight = 0;}
		}
		else {
			if ($res = $this->modx->getObject('ModGoods', array('gid' => $id, 'wid' => $_SESSION['minishop']['warehouse']))) {
				$weight = $res->get('weight');
			}
			else {$weight = 0;}
		}
		return $weight;
	}


	/*
	 * Adds product in cart
	 * 
	 * @param $id							// id of resource
	 * @param $num							// quantity
	 * @param $data							// array with additional data (color, manufacterer, country etc)
	 * @returns array $cartStatus
	 * */
	function addToCart($id, $num = 1, $data = array()) {
		if (empty($id)) {return $this->error('ms.addToCart.error');}
		if (empty($num)) {$num = 1;}
		if (empty($data)) {$data = array();}

		// Verification of resource to be added to cart
		if ($res = $this->modx->getObject('modResource', array('id' => $id, 'deleted' => 0))) {
			$tpl = $res->get('template');
			// This is a kit
			if (in_array($tpl, $this->config['ms_kits_tpls']) && !in_array($tpl, $this->config['ms_goods_tpls'])) {
				$tmp = $this->modx->getCollection('ModKits', array('rid' => $id));
				$i = 0;
				foreach ($tmp as $v) {
					$response = $this->addToCart($v->get('gid'), 1);
					if ($response['status'] == 'error') {
						$error = 1;
					}
					else {$i++;}
				}
				if ($error) {
					return $this->error('ms.addKitToCart.error', $this->getCartStatus(), array('count' => $i));
				}
				return $this->success('ms.addKitToCart.success', $this->getCartStatus(), array('count' => $i));
			}
			// This is a product
			else if (!in_array($tpl, $this->config['ms_goods_tpls'])) {
				// This is not a kit or product
				return $this->error('ms.addToCart.error');
			}
		}
		else {return $this->error('ms.addToCart.error');}
		
		// Continuing adding product
		$num = intval($num);
		if ($num > 1000000) {return $this->error('ms.addToCart.error', $this->getCartStatus());}

		if (!isset($_SESSION['minishop'])) {$_SESSION['minishop'] = array();}
		if (!isset($_SESSION['minishop']['goods'])) {$_SESSION['minishop']['goods'] = array();}
		
		$key = md5($id.(json_encode($data)));
		
		if (array_key_exists($key, $_SESSION['minishop']['goods'])) {
			$_SESSION['minishop']['goods'][$key]['num'] += $num;
			return $this->success('ms.addToCart.success', $this->getCartStatus());
		}
		else {
			$_SESSION['minishop']['goods'][$key] = array(
				'id' => $id
				,'price' => $this->getPrice($id)
				,'weight' => $this->getWeight($id)
				,'num' => $num
				,'data' => $data
			);
			
			return $this->success('ms.addToCart.success', $this->getCartStatus());
		}
	}


	/*
	 * Removes product from cart
	 * 
	 * @param $key							// key of entry for remove
	 * @returns array $cartStatus
	 * */
	function remFromCart($key) {
		if (array_key_exists($key, $_SESSION['minishop']['goods'])) {
			unset($_SESSION['minishop']['goods'][$key]);
			return $this->success('ms.remFromCart.success', $this->getCartStatus());
		}
		else {
			return $this->error('ms.remFromCart.error');
		}
	}


	/*
	 * Changes products quantity in cart
	 *
	 * @param $key							// key of entry
	 * @param $num							// quantity
	 * @returns array $arr					// success of error array
	 * */
	function changeCartCount($key, $num = 0) {
		if (array_key_exists($key, $_SESSION['minishop']['goods'])) {
			if ($num <= 0) {
				unset($_SESSION['minishop']['goods'][$key]);
			}
			else {
				$_SESSION['minishop']['goods'][$key]['num'] = $num;
			}
			return $this->success('ms.changeCartCount.success', $this->getCartStatus());
		}
		else {
			return $this->error('ms.changeCartCount.error', $this->getCartStatus());
		}
	}


	/*
	 * Prepares and returns rendered cart for frontend
	 * 
	 * @returns string $cart				// fully processed cart
	 * */
	function getCart() {
		$cart = $_SESSION['minishop']['goods'];

		if (empty($cart)) {
			return $this->modx->lexicon('ms.Cart.empty');
		}
		// load rendered items of cart
		$pl = $this->renderCart($this->config['tplCartRow']);
		// if user is authenticated - load his profile and set plaseholders to chunk
		if ($this->modx->user->isAuthenticated()) {
			$profile = $this->modx->user->getOne('Profile');
			$this->modx->setPlaceholders(array(
				'email' => $profile->get('email')
				,'receiver' => $profile->get('fullname')
				,'phone' => $profile->get('phone')
				,'city' => $profile->get('city')
				,'region' => $profile->get('state')
				,'index' => $profile->get('zip')
			));
		}
		
		return $this->modx->getChunk($this->config['tplCartOuter'], $pl);
	}


	/*
	 * Returns mini cart for frontend
	 * 
	 * @returns string $cart				// fully processed mini cart
	 * */
	function getMiniCart() {
		$status = $this->getCartStatus();
		
		return $this->modx->getChunk($this->config['tplMiniCart'], $status);
	}
	
	
	/*
	 * Renders cart rows, e.g. products
	 * 
	 * @param string $tpl				// template for processing
	 * @returns array $arr				// array with rendered cart rows and cart total vars (weight, price and quantity)
	 * */
	function renderCart($tpl) {
		$arr = array();
		$arr['rows'] = '';
		$arr['count'] = $arr['total'] = $arr['weight'] = 0;
		$cart = $_SESSION['minishop']['goods'];
		foreach ($cart as $k => $v) {
			if ($res = $this->modx->getObject('modResource', $v['id'])) {
				$tmp = $res->toArray();
				$tmp['key'] = $k;
				$tmp['num'] = $v['num'];
				$tmp['sum'] = $v['num'] * $v['price'];
				$tmp['tmp_weight'] = $v['num'] * $v['weight'];

				// Template variables
				$tvs = $res->getMany('TemplateVars');
				foreach ($tvs as $v2) {
					$tmp[$v2->get('name')] = $v2->get('value');
				}
				
				// Main properties of product
				if ($tmp2 = $this->modx->getObject('ModGoods', array('gid' => $v['id'], 'wid' => $_SESSION['minishop']['warehouse']))) {
					$tmp3 = $tmp2->toArray(); 
					unset($tmp3['id']);
					$tmp = array_merge($tmp, $tmp3);
				}
				
				// Additional properties of product
				if (is_array($v['data']) && !empty($v['data'])) {
					foreach ($v['data'] as $k2 => $v2) {
						$tmp['data.'.$k2] = $v2;
					}
				}
				$arr['rows'] .= $this->modx->getChunk($tpl, $tmp);
				
				$arr['count'] += $tmp['num'];
				$arr['total'] += $tmp['sum'];
				$arr['weight'] += $tmp['tmp_weight'];
			}
		}
		return $arr;
	}


	/*
	 * Renders methods of delivery of the current warehouse.
	 * Used for selecting by customer on frontend.
	 * 
	 * @returns string $options				// processed html
	 * */
	function getDelivery() {
		$q = $this->modx->newQuery('ModDelivery');
		$q->where(array('enabled' => 1, 'wid' => $_SESSION['minishop']['warehouse']));
		$q->sortby('id','ASC');

		if ($res = $this->modx->getCollection('ModDelivery', $q)) {
			foreach ($res as $v) {
				$tmp = $v->toArray();
				if ($_POST['delivery'] == $tmp['id'] || $_SESSION['minishop']['delivery'] == $tmp['id']) {$tmp['selected'] = 'selected';} else {$tmp['selected'] = '';}
				$options .= $this->modx->getChunk($this->config['tplDeliveryRow'], $tmp);
			}
		}
		return $options;
	}


	/*
	 * Renders methods of payments of the current warehouse and selected method of delivery.
	 * Used for selecting by customer on frontend.
	 * 
	 * @returns string $options				// processed html
	 * */
	function getPayments() {
		$q = $this->modx->newQuery('ModPayment');
	
		$did = $_SESSION['minishop']['delivery'];
		if ($delivery = $this->modx->getObject('ModDelivery', $did)) {
			$payments = $delivery->getPayments();
			if (count($payments)) {
				$q->where(array('id:IN' => $payments));
			}
		}
		$q->sortby('id','ASC');

		if ($res = $this->modx->getCollection('ModPayment', $q)) {
			foreach ($res as $v) {
				$tmp = $v->toArray();
				if ($_POST['payment'] == $tmp['id'] || $_SESSION['minishop']['payment'] == $tmp['id']) {$tmp['selected'] = 'selected';} else {$tmp['selected'] = '';}
				$options .= $this->modx->getChunk($this->config['tplPaymentRow'], $tmp);
			}
		}
		return $options;
	}


	/*
	 * Create order
	 * All necessary parameters are taken from the $_SESSION
	 * 
	 * @returns string $chunk				// processed html with success message or error
	 * */
	function submitOrder() {
		// Checking cart
		if (empty($_SESSION['minishop']['goods']) || empty($_SESSION['minishop']['address']['email'])) {
			return $this->modx->lexicon('ms.Cart.empty');
		}

		// Email is the key of user in miniShop
		// If user is authenticated - we get his email
		// If no email in user profile - get it from $_POST and save
		if ($this->modx->user->isAuthenticated()) {
			$uid = $this->modx->user->id;
			$profile = $this->modx->user->getOne('Profile');
			$email = $profile->get('email');
			if (empty($email)) {
				$profile->set('email', $_SESSION['minishop']['address']['email']);
				$profile->save();
			}
		}
		// Processing not authenticated user
		else {
			// Checking user by email. 
			$email = $_SESSION['minishop']['address']['email'];
			if ($profile = $this->modx->getObject('modUserProfile', array('email' => $email))) {
				$uid = $profile->get('internalKey');
			}
			// If no email exists - registering the new user.
			else {
				$user = $this->modx->newObject('modUser', array('username' => $email, 'password' => md5(rand())));
				$profile = $this->modx->newObject('modUserProfile', array('email' => $email, 'fullname' => $_SESSION['minishop']['address']['receiver']));
				$user->addOne($profile);
				$user->save();
				
				// If needed - write the user to a group
				if (!empty($this->config['userGroups'])) {
					$groups = explode(',', $this->config['userGroups']);
					foreach ($groups as $group) {
						$user->joinGroup(trim($group));
					}
				}
				$uid = $user->get('id');
			}
		}
		
		// Sending order to databse
		// First of all we need to get the current number of order
		$td = date('ym');
		$tmp = $this->modx->query("SELECT `num` FROM {$this->modx->getTableName('ModOrders')} WHERE `num` LIKE '{$td}%' ORDER BY `id` DESC LIMIT 1");
		$tnum = $tmp->fetch(PDO::FETCH_COLUMN);
		$tmp->closeCursor();

		if (empty($tnum)) {$tnum = date('ym').'/0';}
		$tnum = explode('/', $tnum);
		$num = $td.'/'.($tnum[1] + 1);

		// Getting address of order
		// If we received an array - creating the new address entry in ModAddress
		$addr = $_SESSION['minishop']['address'];
		if (is_array($addr) && !empty($addr)) {
			$address = $this->modx->newObject('ModAddress');
			$address->fromArray($addr);
			$address->set('uid', $uid);
			$address->save();
			$aid = $address->get('id');
		}
		// If we received an single number - it is id of the existing record and we must check it.
		// If it ok - we use this id.
		else if (!is_array($addr) && intval($addr) > 0) {
			if ($address = $this->modx->getObject('ModAddress', array('id' => $_SESSION['minishop']['address'], 'uid' => $this->modx->user->id))) {
				$aid = $address->get('id');
			}
		}
		else {
			// Bad address, but we continue.
			// Maybe, in this shop address not needed fo ordering?
		}
		
		
		// Creation of the order
		// Get an delivery and payment ids
		$delivery = !empty($_SESSION['minishop']['delivery']) ? $_SESSION['minishop']['delivery'] : '0';
		$payment = !empty($_SESSION['minishop']['payment']) ? $_SESSION['minishop']['payment'] : '0';

		$order = $this->modx->newObject('ModOrders');
		$order->set('uid', $uid);
		$order->set('num', $num);
		$order->set('wid', $_SESSION['minishop']['warehouse']);
		$order->set('delivery', $delivery);
		$order->set('payment', $payment);
		$order->set('address', $aid);
		$order->set('status', 0);
		$order->set('created', date('Y-m-d H:i:s'));
		$order->set('updated', date('Y-m-d H:i:s'));
		$this->modx->invokeEvent('msOnBeforeOrderCreate', array('order' => $order, 'profile' => $profile, 'address' => $address));
		$order->save();

		// Saving goods in cart
		$cart = $_SESSION['minishop']['goods'];
		$enable_remains = $this->modx->getOption('minishop.enable_remains');

		$cart_sum = $cart_weight = 0;
		$oid = $order->get('id');
		$goods = array();
		foreach ($cart as $v) {
			$res = $this->modx->newObject('ModOrderedGoods');
			$res->set('oid', $oid);
			$res->set('gid', $v['id']);
			$res->set('price', $v['price']);
			$res->set('sum', $v['price'] * $v['num']);
			$res->set('weight', $v['weight'] * $v['num']);
			$res->set('num', $v['num']);
			$res->set('data', json_encode($v['data']));
			$res->save();
			
			$cart_sum += $v['price'] * $v['num'];
			$cart_weight += $v['weight'] * $v['num'];
			
			$goods[] = $res;
			// If the remains are enabled - reserving goods
			if ($enable_remains) {
				if ($tmp = $this->modx->getObject('ModGoods', array('gid' => $v['id'], 'wid' => $_SESSION['minishop']['warehouse']))) {
					$tmp->reserve($v['num']);
				}
			}
		}
		$order->set('sum', $cart_sum);
		$order->set('weight', $cart_weight);
		
		if ($order->save()) {
			$this->modx->invokeEvent('msOnOrderCreate', array('order' => $order, 'profile' => $profile, 'address' => $address, 'goods' => $goods));
		}

		// Sets status "new" to the order and sends email notices
		if ($this->changeOrderStatus($order->get('id'), $this->config['ms_status_new'])) {
			unset($_SESSION['minishop']);
			// Launching special snippet (if it set) for processing the order
			if (!empty($payment) && $tmp = $this->modx->getObject('ModPayment', $payment)) {
				if ($snippet = $tmp->getSnippetName()) {
					return $this->modx->runSnippet($snippet, array('order' => $order, 'profile' => $profile, 'address' => $address));
				}
			}
			// Or returning success chunk
			$arr = $order->toArray();
			$arr['email'] = $profile->get('email');
			return $this->modx->getChunk($this->config['tplSubmitOrderSuccess'], $arr);
		}
		else {
			return 'Error when change order status';
		}
	}


	/*
	 * Changes order status with logging and sending email notices
	 * 
	 * @param int $oid						// id of order
	 * @param int $new						// new order status
	 * */
	function changeOrderStatus($oid, $new) {
		if (!$order = $this->modx->getObject('ModOrders', $oid)) {return false;}
		$pls = $this->makePlaceholders($order->toArray());
		$maxIterations = (integer) $this->modx->getOption('parser_max_iterations', null, 10);

		if ($status = $this->modx->getObject('ModStatus', $new)) {
			$old = $order->get('status');
			$order->set('status', $new);
			$this->modx->invokeEvent('msOnBeforeOrderChangeStatus', array('order' => $order, 'old' => $old, 'new' => $new));
			// Saving new status
			if ($order->save()) {
				$this->Log('status', $order->get('id'), 0, 'change', $old, $new);
				$this->modx->invokeEvent('msOnOrderChangeStatus', array('order' => $order, 'old' => $old, 'new' => $new));
				
				if ($this->modx->getOption('minishop.enable_remains')) {
					if ($new == $this->modx->getOption('minishop.status_final')) {
						$order->unReserve();
					}
					else if ($new == $this->modx->getOption('minishop.status_cancel')) {
						$order->releaseReserved();
					}
				}
			}
			// Email to customer
			if ($status->get('email2user')) {
				if ($tmp = $this->modx->getObject('modUserProfile', array('internalKey' => $pls['vl']['uid']))) {
					$email = $tmp->get('email');
					if (!empty($email)) {
						$subject = str_replace($pls['pl'], $pls['vl'], $status->get('subject2user'));
						if ($chunk = $this->modx->getObject('modChunk', $status->get('body2user'))) {
							$body = $this->modx->getChunk($chunk->get('name'), $pls['vl']);
							$this->modx->getParser()->processElementTags('', $body, false, false, '[[', ']]', array(), $maxIterations);
							$this->modx->getParser()->processElementTags('', $body, true, true, '[[', ']]', array(), $maxIterations);
						}
						$this->sendEmail($email, $subject, $body);
					}
				}
			}

			// Email to manager
			if ($status->get('email2manager')) {
				if ($tmp = $this->modx->getObject('ModWarehouse', $pls['vl']['wid'])) {
					$email = $tmp->get('email');
					if (!empty($email)) {
						$subject = str_replace($pls['pl'], $pls['vl'], $status->get('subject2manager'));
						if ($chunk = $this->modx->getObject('modChunk', $status->get('body2manager'))) {
							$body = $this->modx->getChunk($chunk->get('name'), $pls['vl']);
							$this->modx->getParser()->processElementTags('', $body, false, false, '[[', ']]', array(), $maxIterations);
							$this->modx->getParser()->processElementTags('', $body, true, true, '[[', ']]', array(), $maxIterations);
						}
						$this->sendEmail($email, $subject, $body);
					}
				}
			}

		}
		return true;
	}


	/*
	 * Sends emails
	 * 
	 * @param int $to
	 * @param int $subject
	 * @param int $message
	 * */
	function sendEmail($to, $subject, $message) {
		if (!isset($this->modx->mail) || !is_object($this->modx->mail)) {$this->modx->getService('mail', 'mail.modPHPMailer');}
		$this->modx->mail->set(modMail::MAIL_FROM, $this->modx->getOption('emailsender'));
		$this->modx->mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('site_name'));
		$this->modx->mail->setHTML(true);
		$this->modx->mail->set(modMail::MAIL_SUBJECT, $subject);
		$this->modx->mail->set(modMail::MAIL_BODY, $message);

		$tmp = explode(',', $to);
		foreach ($tmp as $v) {
		  if (!empty($v) && preg_match('/@/', $v)) {
			$this->modx->mail->address('to', trim($v));
		  }
		}
		if (!$this->modx->mail->send()) {
			$this->modx->log(modX::LOG_LEVEL_ERROR,'An error occurred while trying to send the email: '.$this->modx->mail->mailer->ErrorInfo);
		}
		$this->modx->mail->reset();
	}


	/*
	 * Prepares and returns chunk for redirecting customer to payment gateway
	 * Designed for work with z-payment.ru, but must work with other gateways. It depends of form in parsed chunk.
	 * 
	 * @param int $oid						// id of existing order
	 * @param int $email					// email of customer from this order for verification
	 * return string $chunk
	 * */
	function redirectCustomer($oid, $email) {
		if (empty($oid)) {
			$this->modx->log(modX::LOG_LEVEL_ERROR,'msPayment ERR: Empty order Id');
			return $this->modx->lexicon('ms.payment.error');
		}
		if (empty($email)) {
			$this->modx->log(modX::LOG_LEVEL_ERROR,'msPayment ERR: Empty order email');
			return $this->modx->lexicon('ms.payment.error');
		}

		if ($tmp = $this->modx->getObject('ModOrders', $oid)) {
			$order = $tmp->toArray();
			$order['sum'] += $tmp->getDeliveryPrice();

			if ($tmp2 = $this->modx->getObject('modUserProfile', array('internalKey' => $order['uid']))) {
				$order['email'] = $tmp2->get('email');
			}
		}
		else {
			$this->modx->log(modX::LOG_LEVEL_ERROR,'msPayment ERR: Order with Id '.$oid.' not found');
			return $this->modx->lexicon('ms.payment.error');
		}

		if (empty($tmp) || $order['email'] != $email) {
			$this->modx->log(modX::LOG_LEVEL_ERROR,'msPayment ERR: Wrong email '.$email.' for order with Id = '.$oid);
			return $this->modx->lexicon('ms.payment.error');
		}
		else {
			return $this->modx->getChunk($this->config['tplPaymentForm'], $order);
		}
	}


	/*
	 * Receiving info about payment
	 * This is totally for z-payments.ru. Will not work with other payments systems.
	 * Fot other systems you must create your own snippet.
	 * 
	 * @param array $data						// request array
	 * return string $chunk
	 * */
	function receivePayment($data) {
		if (strstr($_SERVER['HTTP_REFERER'], 'z-payment.ru') != false) {
			$url = $this->modx->getOption('site_url');
			$this->modx->sendRedirect($url);
		}
		if (empty($data)) {$this->paymentError('Empty payment request');}

		$status_paid = $this->modx->getOption('minishop.status_paid');
		$shop_id = $this->modx->getOption('minishop.payment_shopid');
		$payment_key = $this->modx->getOption('minishop.payment_key');

		foreach ($data as $Key => $Value) {
			$$Key = $Value;
		}

		// Check shop number
		if ($LMI_PAYEE_PURSE != $shop_id) {$this->paymentError('Invalid shop Id '.$LMI_PAYEE_PURSE);}
		// Check order
		if ($res = $this->modx->getObject('ModOrders', array('id' => $LMI_PAYMENT_NO, 'status:!=' => $status_paid))) {
			$sum = $res->get('sum') + $res->getDeliveryPrice();
			if ($sum != intval($LMI_PAYMENT_AMOUNT)) {$this->paymentError('Wrong amount of the order');}
		}
		else {$this->paymentError('Order with Id = '.$LMI_PAYMENT_NO.' not found or already paid');}

		// Prerequest or no?
		if ($LMI_PREREQUEST == 1) {die('YES');}

		// Working request
		$CalcHash = md5($LMI_PAYEE_PURSE
						.$LMI_PAYMENT_AMOUNT
						.$LMI_PAYMENT_NO
						.$LMI_MODE
						.$LMI_SYS_INVS_NO
						.$LMI_SYS_TRANS_NO
						.$LMI_SYS_TRANS_DATE
						.$payment_key
						.$LMI_PAYER_PURSE
						.$LMI_PAYER_WM
					);
		// Hash ok, continue
		if($LMI_HASH == strtoupper($CalcHash)) {
			// Changing order status
			if ($this->changeOrderStatus($LMI_PAYMENT_NO, $status_paid)) {
				die('YES');
			}
		}
		else {$this->paymentError('Wrong HASH');}
	}

	/*
	 * Log payment errors and returns HTTP code so z-payment.ru continue trying
	 * 
	 * @param string $text
	 * */
	function paymentError($text) {
		$this->modx->log(modX::LOG_LEVEL_ERROR,'msPayment ERR: '.$text);
		header("HTTP/1.0 400 Bad Request");
		die('ERR: '.$text);
	}


	/*
	 * Gets gallery of files for product
	 * 
	 * @param int $id						// id of existing resource
	 * @param string $sord					// field for sorting
	 * @param string $dir					// direction of sorting
	 * return array $arr					// array with all product files with properties
	 * */
	function getGallery($id = 0, $sort = 'id', $dir = 'ASC') {
		if (empty($id)) {$id = $this->modx->resource->id;}
		if (!$this->modx->getCount('modResource', $id)) {return false;}

		$arr = array();
		$q = $this->modx->newQuery('ModGallery');
		$q->where(array('gid' => $id, 'wid' => $_SESSION['minishop']['warehouse']));
		$q->sortby($sort,$dir);
		$gallery = $this->modx->getCollection('ModGallery', $q);
		foreach ($gallery as $v) {
			$arr[] = $v->toArray();
		}
		return $arr;
	}

	/*
	 * Gets mathing resources by tags
	 *
	 * @param array $tags					// Tags for search
	 * @param int $only_ids					// Return only ids of matched resources
	 * @param int $strict					// 0 - goods must have at least one specified tag
	 * 										// 1 - goods must have all specified tags, but can have more
	 * 										// 2 - goods must have exactly the same tags.
	 * @return array $ids					// Or array with resources with data and tags
	 * */
	function getTagged($tags = array(), $strict = 0, $only_ids = 0) {
		if (!is_array($tags)) {$tags = explode(',', $tags);}

		$sql = "SELECT `rid` FROM {$this->modx->getTableName('ModTags')} WHERE `tag` IN ('".implode("','", $tags)."')";
		$q = new xPDOCriteria($this->modx, $sql);
		$ids = array();
		if ($q->prepare() && $q->stmt->execute()){
			$ids = $q->stmt->fetchAll(PDO::FETCH_COLUMN);
		}
		$ids = array_unique($ids);
		
		// If needed only ids of not strictly mathed items - return.
		if (!$strict && $only_ids) {return $ids;}

		// Filtering ids
		$count = count($tags);
		if ($strict) {
			foreach ($ids as $key => $rid) {
				if ($strict > 1) {
					if ($this->modx->getCount('ModTags', array('rid' => $rid)) != $count) {
						unset($ids[$key]);
						continue;
					}
				}
				
				foreach ($tags as $tag) {
					if (!$this->modx->getCount('ModTags', array('rid' => $rid, 'tag' => $tag))) {
						unset($ids[$key]);
						break;
					}
				}
			}
		}

		// Return strictly ids, if needed
		$ids = array_unique($ids);
		if ($only_ids) {
			return $ids;
		}

		// Process results
		$data = array();
		foreach ($ids as $id) {
			if (!$only_ids) {
				if ($res = $this->modx->getObject('modResource', $id)) {
					if ($goods = $this->modx->getObject('ModGoods', array('gid' => $id, 'wid' => $_SESSION['minishop']['warehouse']))) {
						$goods_arr = $goods->toArray();
						$goods_arr['gid'] = $goods_arr['id'];
						unset($goods_arr['id']);
						$data[$id] = array_merge($res->toArray(), $goods_arr);
						$data[$id]['tags'] = $goods->getTags();
					}
				}
			}
		}

		// Return results
		return $data;
	}


	/*
	 * Gets resource with goods properties
	 *
	 * @param int $id						// modResource id
	 * @param int $wid						// ModWarehouse id
	 * @param bool $level						// Level of retrieving.
	 *									0 - goods properties
	 *									1 - goods + resource
	 * 									2 - goods + resource + tvs
	 *
	 * @return array $arr					// Resource with goods properties and tags and processed price
	 * */
	function getProduct($id = 0, $wid = 0, $level = 0) {
		if (empty($id)) {$id = $this->modx->resource->id;}
		if (empty($wid)) {$wid = $_SESSION['minishop']['warehouse'];}

		$res = array();
		if ($level > 0 && $resource = $this->modx->getObject('modResource', $id)) {
			$res = $resource->toArray();
		}

		$tvs = array();
		if ($level > 1) {
			$tmp = $resource->getMany('TemplateVars');
			foreach ($tmp as $v) {
				$tvs['tv.'.$v->get('name')] = $v->get('value');
			}
		}

		$goods = array();
		if ($tmp = $this->modx->getObject('ModGoods', array('gid' => $id, 'wid' => $wid))) {
			$goods = $tmp->toArray();
			unset($goods['id']);
			$goods['price'] = $this->getPrice($id);
			$goods['tags'] = implode(', ', $tmp->getTags());
		}

		$arr = array_merge($res, $goods, $tvs);
		return $arr;
	}
	




	/*--------------------*/
	/* DEPRECATED METHODS */
	/*--------------------*/
	function getAddrForm($arr = array()) {
		return 'This method is disabled. You need to use FormIt!';
	}
	function saveAddrForm() {
		return 'This method is disabled. You need to use FormIt!';
	}
	function validate($str = 0, $type = 0) {
		return 'This method is disabled. You need to use FormIt!';
	}
	function utf8_ucfirst($string) {
		$string = mb_ereg_replace("^[\ ]+","", $string);
		$string = mb_strtolower($string, "utf-8");
		$string = mb_strtoupper(mb_substr($string, 0, 1, "utf-8"), "UTF-8").mb_substr($string, 1, mb_strlen($string), "UTF-8" );  
		return $string;  
	}
	function getGoods($parents) {
		return $this->getGoodsByCategories($parents);
	}

}
