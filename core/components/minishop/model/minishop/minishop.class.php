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
			'ms_status_new' => $this->modx->getOption('minishop.status_new', '', 1)
        ),$config);

        $this->modx->addPackage('minishop',$this->config['modelPath'], $this->modx->config['table_prefix'].'ms_');
        $this->modx->lexicon->load('minishop:default');

		// Вывод ошибок при отладке
		if ($this->config['debug']) {
			ini_set('display_errors', 1); 
			error_reporting(E_ALL ^ E_NOTICE);
		}

		// Определение дефолтных переменных сессии для работы магазина
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

 
	// Распечатка массива
	function print_arr($arr = array()) {
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}


	// Создание плейсхолдеров из массива значений
	function makePlaceholders($arr = array()) {
		$tmp = array();
		foreach ($arr as $k => $v) {
			$tmp['pl'][$k] = '[[+'.$k.']]';
			$tmp['vl'][$k] = $v;
		}
		return $tmp;
	}


	// Логирование изменений заказов и товаров в БД
	function Log($type, $id, $operation, $old, $new) {
		if ($old == $new) {return;}
		$uid = empty($this->modx->user->id) ? 1 : $this->modx->user->id;
		$res = $this->modx->newObject('ModLog');
		$res->set('uid', $uid);
		$res->set('type', $type);
		$res->set('iid', $id);
		$res->set('operation', $operation);
		$res->set('old', $old);
		$res->set('new', $new);
		$res->set('ip', $_SERVER['REMOTE_ADDR']);
		$res->save();
	}


	// Массив статусов для рендера в админке и фронтенде
	function getStatusesArray() {
		$arr = array();
		if ($tmp = $this->modx->getCollection('ModStatus')) {
			foreach ($tmp as $v) {
				$arr[$v->get('id')] = array('name' => $v->get('name'), 'color' => $v->get('color'));
			}
		}
		return $arr;
	}


	// Вычисление "склада по-умолчанию"
	function getDefaultWarehouse() {
		// Есть значение в сессии
		if (!empty($_SESSION['minishop']['warehouse'])) {
			return $_SESSION['minishop']['warehouse'];
		}
		//Если нет - вычисляем
		$q = $this->modx->newQuery('ModWarehouse');
		$q->sortby('name', 'ASC');
		if ($tmp = $this->modx->getCollection('ModWarehouse', $q)) {
			foreach ($tmp as $v) {
				
				$permission = $v->get('permission');
				if (!empty($permission) && !$this->modx->hasPermission($permission)) {
					continue;
				}
				else {
					return $v->get('id');
				}
			}
		}
		else {
			return 1;
		}
	}


	// Возврат ошибки
	function error($msg, $data = array()) {
		$data['status'] = 'error';
		$data['message'] = $this->modx->lexicon($msg);
		return $data;
	}


	// Возврат успешной операции
	function success($msg, $data = array()) {
		$data['status'] = 'success';
		$data['message'] = $this->modx->lexicon($msg);
		return $data;
	}

	// Функция выводит id ресурсов для которых указанная категория - дополнительная
	function getGoods($x) {
		return $this->getGoodsByCategories($x);
	}
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


	// Вывод списка заказов в личный кабинет
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


	// Подсчет состояния корзины
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


	// Цена товара
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


	// Вес товара
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


	// Добавление товара в корзину
	function addToCart($id, $num = 1, $data = array()) {
		if (empty($id)) {return $this->error($this->modx->lexicon('ms.addToCart.error'));}
		$num = intval($num);
		if (empty($num)) {$num = 1;}
		if (empty($data)) {$data = array();}

		if ($num > 1000000) {return $this->error('ms.addToCart.error', $this->getCartStatus());}

		if (empty($_SESSION['minishop'])) {$_SESSION['minishop'] = array();}
		if (empty($_SESSION['minishop']['goods'])) {$_SESSION['minishop']['goods'] = array();}
		
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


	// Удаление товара из корзины
	function remFromCart($key) {
		if (array_key_exists($key, $_SESSION['minishop']['goods'])) {
			unset($_SESSION['minishop']['goods'][$key]);
			return $this->success('ms.remFromCart.success', $this->getCartStatus());
		}
		else {
			return $this->error('ms.remFromCart.error');
		}
	}


	// Вывод корзины со всеми выбранными товарами
	function getCart() {
		$cart = $_SESSION['minishop']['goods'];

		if (empty($cart)) {
			return $this->modx->lexicon('ms.Cart.empty');
		}

		$pl = $this->renderCart($this->config['tplCartRow']);

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


	// Изменение товаров в корзине
	function changeCartCount($id, $val = 0) {
		if (array_key_exists($id, $_SESSION['minishop']['goods'])) {
			if ($val <= 0) {
				unset($_SESSION['minishop']['goods'][$id]);
			}
			else {
				$_SESSION['minishop']['goods'][$id]['num'] = $val;
			}
			return $this->success('ms.changeCartCount.success', $this->getCartStatus());
		}
		else {
			return $this->error('ms.changeCartCount.error', $this->getCartStatus());
		}
	}


	// Обработка строк корзины по указанному шаблону
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

				// ТВ параметры
				$tvs = $res->getMany('TemplateVars');
				foreach ($tvs as $v2) {
					$tmp[$v2->get('name')] = $v2->get('value');
				}
				
				// Свойства товара
				if ($tmp2 = $this->modx->getObject('ModGoods', array('gid' => $v['id'], 'wid' => $_SESSION['minishop']['warehouse']))) {
					$tmp3 = $tmp2->toArray(); 
					unset($tmp3['id']);
					$tmp = array_merge($tmp, $tmp3);
				}
				
				// Дополнительные свойства выбранного товара
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


	// Вывод методов доставки текущего склада
	function getDelivery() {
		$options = '';

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


	// Отправка заказа в БД
	function submitOrder() {
		// Проверка корзины
		if (empty($_SESSION['minishop']['goods']) || empty($_SESSION['minishop']['address']['email'])) {
			return $this->modx->lexicon('ms.Cart.empty');
		}

		// Проверка авторизации юзера и регистрация, при необходимости
		if ($this->modx->user->isAuthenticated()) {
			$uid = $this->modx->user->id;
			$profile = $this->modx->user->getOne('Profile');
			$email = $profile->get('email');
			if (empty($email)) {
				$profile->set('email', $_SESSION['minishop']['address']['email']);
				$profile->save();
			}
		}
		// Юзер не авторизован
		else {
			// Такой емаил есть в базе - используем этого юзера
			$email = $_SESSION['minishop']['address']['email'];
			if ($profile = $this->modx->getObject('modUserProfile', array('email' => $email))) {
				$uid = $profile->get('internalKey');
			}
			// Новый юзер, регистрируем
			else {
				$user = $this->modx->newObject('modUser', array('username' => $email, 'password' => md5(rand())));
				$profile = $this->modx->newObject('modUserProfile', array('email' => $email, 'fullname' => $_SESSION['minishop']['address']['receiver']));
				$user->addOne($profile);
				$user->save();
				
				// Если указано - заносим в группы
				if (!empty($this->config['userGroups'])) {
					$groups = explode(',', $this->config['userGroups']);
					foreach ($groups as $group) {
						$user->joinGroup(trim($group));
					}
				}
				$uid = $user->get('id');
			}
		}
		
		// Отправка заказа в базу данных
		// Получаем номер заказа
		$td = date('ym');
		$tmp = $this->modx->query("SELECT `num` FROM {$this->modx->getTableName('ModOrders')} WHERE `num` LIKE '{$td}%' ORDER BY `id` DESC LIMIT 1");
		$tnum = $tmp->fetch(PDO::FETCH_COLUMN);
		$tmp->closeCursor();

		if (empty($tnum)) {$tnum = date('ym').'/0';}
		$tnum = explode('/', $tnum);
		$num = $td.'/'.($tnum[1] + 1);

		// Обработка адреса
		$addr = $_SESSION['minishop']['address'];
		if (is_array($addr) && !empty($addr)) {
			$address = $this->modx->newObject('ModAddress');
			$address->fromArray($addr);
			$address->set('uid', $uid);
			$address->save();
			$aid = $address->get('id');
		}
		/*
		else if (!is_array($addr) && $addr > 0) {
			if (!$address = $this->modx->getObject('ModAddress', array('id' => $_SESSION['minishop']['address'], 'uid' => $this->modx->user->id))) {
				die('Ошибка получения выбранного адреса');
			}
			else {
				$aid = $address->get('id');
			}
		}
		else if ($_SESSION['minishop']['delivery'] == 'self') {
			$aid = 0;
		}
		else {
			// Неизвестная ошибка с адресом
		}
		*/
		
		
		// Создание заказа
		$delivery = !empty($_SESSION['minishop']['delivery']) ? $_SESSION['minishop']['delivery'] : '0';
		$order = $this->modx->newObject('ModOrders');
		$order->set('uid', $uid);
		$order->set('num', $num);
		$order->set('wid', $_SESSION['minishop']['warehouse']);
		$order->set('delivery', $delivery);
		$order->set('address', $aid);
		$order->set('status', 0);
		$order->set('created', date('Y-m-d H:i:s'));
		$order->set('updated', date('Y-m-d H:i:s'));
		$order->save();

		// Сохранение товаров корзины
		$cart = $_SESSION['minishop']['goods'];
		$enable_remains = $this->modx->getOption('minishop.enable_remains');

		$cart_sum = 0;
		$oid = $order->get('id');
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
			// Если включена работа с остатками - резервируем товар на складе.
			if ($enable_remains) {
				if ($tmp = $this->modx->getObject('ModGoods', array('gid' => $v['id'], 'wid' => $_SESSION['minishop']['warehouse']))) {
					$tmp->reserve($v['num']);
				}
			}
		}
		$order->set('sum', $cart_sum);
		$order->save();

		// Изменения статуса и отправка писем
		if ($this->changeOrderStatus($order->get('id'), $this->config['ms_status_new'])) {
			unset($_SESSION['minishop']);
			$arr = $order->toArray();
			$arr['email'] = $profile->get('email');
			return $this->modx->getChunk($this->config['tplSubmitOrderSuccess'], $arr);
		}
		else {
			return 'Error when change order status';
		}
	}


	// Изменение статуса заказа, логирование изменения и отправка уведомления (если включено)
	function changeOrderStatus($oid, $new) {
		if (!$order = $this->modx->getObject('ModOrders', $oid)) {return false;}
		$pls = $this->makePlaceholders($order->toArray());
		$maxIterations= (integer) $this->modx->getOption('parser_max_iterations', null, 10);

		if ($status = $this->modx->getObject('ModStatus', $new)) {
			$old = $order->get('status');
			$order->set('status', $new);
			if ($order->save()) {
				$this->Log('status', $order->get('id'), 'change', $old, $new);
				
				if ($this->modx->getOption('minishop.enable_remains')) {
					if ($new == $this->modx->getOption('minishop.status_final')) {
						$order->unReserve();
					}
					else if ($new == $this->modx->getOption('minishop.status_cancel')) {
						$order->releaseReserved();
					}
				}
			}
			// Письмо покупателю
			if ($status->get('email2user')) {
				if ($tmp = $this->modx->getObject('modUserProfile', array('internalKey' => $pls['vl']['uid']))) {
					$email = $tmp->get('email');
					if (!empty($email)) {
						$subject = str_replace($pls['pl'], $pls['vl'], $status->get('subject2user'));
						$body = $this->modx->getChunk($status->get('body2user'), $pls['vl']);
						$this->modx->getParser()->processElementTags('', $body, false, false, '[[', ']]', array(), $maxIterations);
						$this->modx->getParser()->processElementTags('', $body, true, true, '[[', ']]', array(), $maxIterations);
						
						$this->sendEmail($email, $subject, $body);
					}
				}
			}

			// Письмо менеджеру
			if ($status->get('email2manager')) {
				if ($tmp = $this->modx->getObject('ModWarehouse', $pls['vl']['wid'])) {
					$email = $tmp->get('email');
					if (!empty($email)) {
						$subject = str_replace($pls['pl'], $pls['vl'], $status->get('subject2manager'));
						$body = $this->modx->getChunk($status->get('body2manager'), $pls['vl']);
						$this->modx->getParser()->processElementTags('', $body, false, false, '[[', ']]', array(), $maxIterations);
						$this->modx->getParser()->processElementTags('', $body, true, true, '[[', ']]', array(), $maxIterations);
						
						$this->sendEmail($email, $subject, $body);
					}
				}
			}

		}
		return true;
	}


	// Отправка почты по указанному адресу, с темой и телом
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


	// Отправка покупателя на оплату
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


	// Прием информации об оплате от z-payment.ru
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

		//Проверяем номер магазина
		if ($LMI_PAYEE_PURSE != $shop_id) {$this->paymentError('Invalid shop Id '.$LMI_PAYEE_PURSE);}
		//Проверяем наличие заказа и его сумму
		if ($res = $this->modx->getObject('ModOrders', array('id' => $LMI_PAYMENT_NO, 'status:!=' => $status_paid))) {
			$sum = $res->get('sum') + $res->getDeliveryPrice();
			if ($sum != intval($LMI_PAYMENT_AMOUNT)) {$this->paymentError('Wrong amount of the order');}
		}
		else {$this->paymentError('Order with Id = '.$LMI_PAYMENT_NO.' not found or already paid');}

		// Если это предварительный запрос - отвечаем YES
		if ($LMI_PREREQUEST == 1) {die('YES');}

		// Рабочий запрос - проверяем хэш
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
		// Хэш совпадает, проводим платеж у себя
		if($LMI_HASH == strtoupper($CalcHash)) {
			//Подтверждение оплаты заказа
			if ($this->changeOrderStatus($LMI_PAYMENT_NO, $status_paid)) {
				die('YES');
			}
		}
		else {$this->paymentError('Wrong HASH');}
	}


	// Функция вывода ошибки приема оплаты
	function paymentError($text) {
		$this->modx->log(modX::LOG_LEVEL_ERROR,'msPayment ERR: '.$text);
		header("HTTP/1.0 400 Bad Request");
		die('ERR: '.$text);
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

}