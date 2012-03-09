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
            'chunkSuffix' => '.chunk.tpl',
            'snippetsPath' => $corePath.'elements/snippets/',
            'processorsPath' => $corePath.'processors/',
			'ms_categories_tpls' => explode(',', $this->modx->getOption('minishop.categories_tpl', '', 1)),
			'ms_goods_tpls' => explode(',', $this->modx->getOption('minishop.goods_tpl', '', 1)),
			'ms_status_new' => $this->modx->getOption('minishop.status_new', '', 1)
        ),$config);

        $this->modx->addPackage('minishop',$this->config['modelPath'], $this->modx->config['table_prefix'].'ms_');
        $this->modx->lexicon->load('minishop:default');

		// Вывод ошибок
		ini_set('display_errors', 1); 
		error_reporting(E_ALL);
		
		// Определение дефолтных переменных сессии для работы магазина
		// Дефолтный склад
		if (!isset($_SESSION['minishop']['warehouse'])) {
			// Можно добавить определение склада через $_REQUEST
			$_SESSION['minishop']['warehouse'] = $this->getDefaultWarehouse();
		}
		// Категория товаров
		if (!isset($_SESSION['minishop']['category'])) {
			// Можно добавить определение категории через $_REQUEST
			$_SESSION['minishop']['category'] = 0;
		}
		// Статус заказа
		if (!isset($_SESSION['minishop']['status'])) {
			// Можно добавить определение категории через $_REQUEST
			$_SESSION['minishop']['status'] = 0;
		}
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
            case 'web':
                //if (!$this->modx->loadClass('minishop.request.miniShopConnectorRequest',$this->config['modelPath'],true,true)) {
                //    return 'Could not load connector request handler.';
                //}
                //$this->request = new miniShopConnectorRequest($this);
                //return $this->request->handle();
            break;
            default:
                /* if you wanted to do any generic frontend stuff here.
                 * For example, if you have a lot of snippets but common code
                 * in them all at the beginning, you could put it here and just
                 * call $minishop->initialize($modx->context->get('key'));
                 * which would run this.
                 */
            break;
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
		$res = $this->modx->newObject('ModLog');
		$res->set('uid', $this->modx->user->id);
		$res->set('type', $type);
		$res->set('iid', $id);
		$res->set('operation', $operation);
		$res->set('old', $old);
		$res->set('new', $new);
		$res->set('ip', $_SERVER['REMOTE_ADDR']);
		$res->save();
	}
	
	
	// ucfirst для utf-8
	function utf8_ucfirst($string) {
		$string = mb_ereg_replace("^[\ ]+","", $string);
		$string = mb_strtolower($string, "utf-8");
		$string = mb_strtoupper(mb_substr($string, 0, 1, "utf-8"), "UTF-8").mb_substr($string, 1, mb_strlen($string), "UTF-8" );  
		return $string;  
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
	
	// Валидатор данных формы
	function validate($str = 0, $type = 0) {
		if (empty($str) || empty($type)) {return false;}
		
		$str = trim($str);
		switch ($type) {
			/*
			case 'digit':
				preg_match('/[^0-9$]/', $str, $tmp);
				$str = $tmp[0];
				if (empty($str) || !preg_match('/[0-9]/', $str)) {return false;}
			break;
			*/
			case 'email': 
				if (filter_var($str, FILTER_VALIDATE_EMAIL)) {return $str;}
				else {return false;}
			break;
			case 'index':
				preg_match('/[0-9]{6,6}/', $str, $tmp);
				$str = $tmp[0];
				if (empty($str) || !preg_match('/[0-9]/', $str)) {return false;}
			break;
			case 'fio': 
				$str = preg_replace('/[^-а-яa-z]/iu', '', $str);
				if (empty($str) || !preg_match('/[-а-яa-z]/iu', $str)) {return false;}
				else {$str = $this->utf8_ucfirst($str);}
			break;
			case 'phone': 
				$str = preg_replace('/[^0-9+]/', '', $str);
				if (empty($str) || !preg_match('/[0-9+]/', $str)) {return false;} 
			break;
			/*
			case 'date': 
				$tmp = explode('.', $str);
				if (!checkdate($tmp[1], $tmp[0], $tmp[2])) {return false;}
				$str = strftime($this->date_sql, strtotime($str));
			break;
			*/
			case 'notempty': 
				if (!empty($str)) {return $str;}
				else {return false;} 
			break;

			default: return false;
		}
		return $str;
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
		foreach ($cart as $v) {
			$arr['count'] += $v['num'];
			$arr['total'] += $v['price'] * $v['num'];
		}
		return $arr;
	}


	// Цена товара
	function getPrice($id) {
		if ($res = $this->modx->getObject('ModGoods', array('gid' => $id, 'wid' => $_SESSION['minishop']['warehouse']))) {
			$price = $res->get('price');

			return $this->modx->runSnippet('msGetPrice', array('input' => $price));
		}
		else {
			return 0;
		}
	}


	// Добавление товара в корзину
	function addToCart($id, $num = 1, $data = array()) {
		if (empty($id)) {return $this->error($this->modx->lexicon('ms.addToCart.error'));}
		if (empty($num)) {$num = 1;}
		if (empty($data)) {$data = array();}

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


	// Обработка строк корзины по указанному шаблону
	function renderCart($tpl) {
		$arr = array();
		$arr['rows'] = '';
		$arr['count'] = $arr['total'] = 0;
		$cart = $_SESSION['minishop']['goods'];
		foreach ($cart as $k => $v) {
			if ($res = $this->modx->getObject('modResource', $v['id'])) {
				$tmp = $res->toArray();
				$tmp['key'] = $k;
				$tmp['num'] = $v['num'];
				$tmp['sum'] = $v['num'] * $v['price'];

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
				foreach ($v['data'] as $k2 => $v2) {
					$tmp['data.'.$k2] = $v2;
				}
				$arr['rows'] .= $this->modx->getChunk($tpl, $tmp);
				
				$arr['count'] += $tmp['num'];
				$arr['total'] += $tmp['sum'];
			}
		}
		return $arr;
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


	// Форма выбора доставки
	/*
	function selectDelivery() {
		// Проверка предыдущих шагов заказа
		if (empty($_SESSION['minishop']['goods'])) {
			header('Location: ' . $this->modx->makeUrl($this->config['page_cart'], '', '', $full));
		}
		$delivery = $_SESSION['minishop']['delivery'];

		$q = $this->modx->newQuery('modResource');
		$q->where(array('parent' => $this->config['warehouse']));
		$q->sortby('menuindex', 'ASC');
		
		if ($_SESSION['minishop']['delivery'] == 'self') {$checked = 'checked';}
		$rows = $this->modx->getChunk($this->config['tplDeliveryRow'], array('id' => 'self', 'pagetitle' => $this->modx->lexicon('ms.delivery.self'), 'checked' => $checked));
		if ($res = $this->modx->getCollection('modResource', $q)) {
			foreach ($res as $v) {
				$tmp = $v->toArray();

				if ($tmp['id'] == $delivery) {
					$tmp['checked'] = 'checked';
				}
				else {
					$tmp['checked'] = '';
				}
				$rows .= $this->modx->getChunk($this->config['tplDeliveryRow'], $tmp);
			}
		}
		
		$pl = array(
			'rows' => $rows
			,'delivery' => $delivery
		);

		return $this->modx->getChunk($this->config['tplDeliveryOuter'], $pl);
	}
	*/


	// Сохранение выбранной доставки
	/*
	function saveDelivery($id) { 
		$_SESSION['minishop']['delivery'] = $id;
		unset($_SESSION['minishop']['address']);
		return $this->success('ms.saveDelivery.success');
	}
	*/


	// Вывод формы адреса доставки
	function getAddrForm($arr = array()) {
		// Проверка предыдущих шагов заказа
		if (empty($_SESSION['minishop']['goods'])) {
			//header('Location: ' . $this->modx->makeUrl($this->config['page_cart'], '', '', $full));
			return $this->modx->lexicon('ms.Cart.empty');
		}
		/*
		if (empty($_SESSION['minishop']['delivery'])) {
			header('Location: ' . $this->modx->makeUrl($this->config['page_delivery'], '', '', $full));
		}
		// Самовывоз у авторизованного юзера
		else if ($_SESSION['minishop']['delivery'] == 'self' && $this->modx->user->isAuthenticated()) {
			header('Location: ' . $this->modx->makeUrl($this->config['page_confirm'], '', '', $full));
		}
		// Самовывоз у гостя - даем миниформу
		else if ($_SESSION['minishop']['delivery'] == 'self') {
			$tpl = $this->config['tplAddrFormMini'];
			$mini = true;
		}
		// Обычный заказ
		else {
			$tpl = $this->config['tplAddrForm'];
		}
		*/
		$tpl = $this->config['tplAddrForm'];
		
		/*
		if (empty($arr) && !empty($_SESSION['minishop']['address']) && is_array($_SESSION['minishop']['address'])) {
			$arr = $_SESSION['minishop']['address'];
		}
		else {
			$checked = 'checked';
		}
		*/
		
		// Поиск и вывод сохраненных адресов
		/*
		$saved_addresses = '';
		if ($this->modx->user->isAuthenticated()) {
			$q = $this->modx->newQuery('ModAddress');
			$q->where(array('uid' => $this->modx->user->id));
			if ($res = $this->modx->getCollection('ModAddress', $q)) {
				foreach ($res as $v) {
					$addr = array('id' => $v->get('id'));
					$addr['address_info'] = $v->get('receiver')
							.', '.$v->get('phone')
							.', '.$v->get('index')
							.', '.$v->get('region')
							.', '.$v->get('city')
							.', '.$v->get('street')
							.', '.$v->get('building')
							.', '.$v->get('room')
							;
					$saved_addresses .= $this->modx->getChunk($this->config['tplAddrFormSaved'], $addr);
					//$this->print_arr($v->toArray());
				}
				$saved_addresses .= $this->modx->getChunk($this->config['tplAddrFormSaved'], array('id' => 0, 'address_info' => $this->modx->lexicon('ms.address.createnew'), 'checked' => $checked));
			}
			if (empty($arr['receiver'])) {$arr['receiver'] = $this->modx->user->getOne('Profile')->fullname;}
		}
		
		$arr['saved_addresses'] = $saved_addresses;
		$delivery = $_SESSION['minishop']['delivery'];
		//return $this->modx->getChunk($this->config['tplAddrForm'], $arr);
		*/
		return $this->modx->getChunk($tpl, $arr);
	}


	// Проверка и сохранение адреса доставки в сессию
	function saveAddrForm() {
		$data = $_POST;
		$arr = $tmp = array();
		
		/*
		if ($_SESSION['minishop']['delivery'] == 'self') {
			$mini = true;
		}
		*/
		
		/*
		if ($data['address'] > 0) {
			$_SESSION['minishop']['address'] = $data['address'];
			header('Location: ' . $this->modx->makeUrl($this->config['page_confirm'], '', '', 'full'));
		}
		*/
		
		// Проверки данных присланной формы
		// Обязательные поля для всех видов доставки
		// Email, если есть
		//if ($this->modx->user->isAuthenticated() == false) {
		if ($email = $this->validate($data['email'], 'email')) {$arr['email'] = $email;}
		else {$err['email'] = $this->modx->lexicon('ms.validate.email');}
		//}
		// Получатель
		$fio = explode(' ', $data['receiver']);
		foreach ($fio as $v) {
			if ($tmp2 = $this->validate($v, 'fio')) {
				$tmp[] = $tmp2;
			}
		}
		if (count($tmp) > 0) {$arr['receiver'] = implode(' ', $tmp);}
		else {$err['receiver'] = $this->modx->lexicon('ms.validate.receiver');}
		// Телефон
		if ($phone = $this->validate($data['phone'], 'phone')) {$arr['phone'] = $phone;}
		else {$err['phone'] = $this->modx->lexicon('ms.validate.phone');}
		
		// Поля, обязательные только при доставке, а не самовывозе
		if (!$mini) {
			// Индекс
			if ($index = $this->validate($data['index'], 'index')) {$arr['index'] = $index;}
			else {$err['index'] = $this->modx->lexicon('ms.validate.index');}
			// Область, город, улица, дом, квартира
			if ($region = $this->validate($data['region'], 'notempty')) {$arr['region'] = $region;}
			else {$err['region'] = $this->modx->lexicon('ms.validate.notempty');}
			if ($city = $this->validate($data['city'], 'notempty')) {$arr['city'] = $city;}
			else {$err['city'] = $this->modx->lexicon('ms.validate.notempty');}
			if ($street = $this->validate($data['street'], 'notempty')) {$arr['street'] = $street;}
			else {$err['street'] = $this->modx->lexicon('ms.validate.notempty');}
			if ($building = $this->validate($data['building'], 'notempty')) {$arr['building'] = $building;}
			else {$err['building'] = $this->modx->lexicon('ms.validate.notempty');}
			if ($room = $this->validate($data['room'], 'notempty')) {$arr['room'] = $room;}
			else {$err['room'] = $this->modx->lexicon('ms.validate.notempty');}
			// Метро, комментарий
			$arr['metro'] = $data['metro'];
			$arr['comment'] = $data['comment'];
		}
		
		if (!empty($arr['delivery'])) {$_SESSION['minishop']['delivery'] = $arr['delivery'];}
		
		if (count($err) > 0) {
			$this->modx->setPlaceholders($err, 'error.');
			return $this->getAddrForm($arr);
		}
		else {
			$_SESSION['minishop']['address'] = $arr;
			//header('Location: ' . $this->modx->makeUrl($this->config['page_confirm'], '', '', 'full'));
			return $this->submitOrder();
		}
	}

	// Подтверждение заказа
	/*
	function confirmOrder() {
		// Проверка предыдущих шагов заказа
		if (empty($_SESSION['minishop']['goods'])) {
			header('Location: ' . $this->modx->makeUrl($this->config['page_cart'], '', '', $full));
		}
		if (empty($_SESSION['minishop']['delivery'])) {
			header('Location: ' . $this->modx->makeUrl($this->config['page_delivery'], '', '', $full));
		}
		if (empty($_SESSION['minishop']['address']) && $_SESSION['minishop']['delivery'] != 'self') {
			header('Location: ' . $this->modx->makeUrl($this->config['page_address'], '', '', $full));
		}
		
		// Корзина
		$arr = $this->renderCart($this->config['tplConfirmOrderRow']);

		// Доставка
		$arr['delivery'] = $_SESSION['minishop']['delivery'];
		if ($arr['delivery'] == 'self' && $this->modx->user->isAuthenticated()) {
			$arr['delivery_name'] = $this->modx->lexicon('ms.delivery.self');
			$address = array(
				'receiver' => $this->modx->user->getOne('Profile')->fullname
				,'phone' => $this->modx->user->getOne('Profile')->mobilephone
				,'email' => $this->modx->user->getOne('Profile')->email
			);
			//$address = $_SESSION['minishop']['address'];
		}
		else if ($arr['delivery'] == 'self' && !$this->modx->user->isAuthenticated()) {
			$arr['delivery_name'] = $this->modx->lexicon('ms.delivery.self');
			$address = $_SESSION['minishop']['address'];
		}
		else {
			$tmp = $this->modx->getObject('modResource', $arr['delivery']);
			$arr['delivery_name'] = $tmp->get('pagetitle');

			if (is_array($_SESSION['minishop']['address'])) {
				$address = $_SESSION['minishop']['address'];
			}
			else {
				$res = $this->modx->getObject('ModAddress', array('uid' => $this->modx->user->id, 'id' => $_SESSION['minishop']['address']));
				$address = $res->toArray();
			}
		}
		

		// Сливаем заказ и адрес
		$arr = array_merge($arr, $address);

		// Captcha
		$d1 = rand(10, 100);
		$d2 = rand(1, 90);
		$arr['captcha'] = "$d1 + $d2";
		$_SESSION['minishop']['captcha'] = $d1 + $d2;
		$_SESSION['minishop']['cart_hash'] = $this->getCartHash();
		$_SESSION['minishop']['cart_sum'] = $arr['total'];
		
		
		return $this->modx->getChunk($this->config['tplConfirmOrder'], $arr);
	}
	*/
	

	// Контрольная ссума товаров корзины
	/*
	function getCartHash() {
		$cart = $_SESSION['minishop']['goods'];
		
		$str = '';
		foreach ($cart as $v) {
			$str .= implode(',', $v);
		}
		
		return md5($str);
	}
	*/


	// Отправка заказа в БД
	function submitOrder($captcha = '') {
		// Проверка предыдущих шагов заказа
		if (empty($_SESSION['minishop']['goods'])) {
			header('Location: ' . $this->modx->makeUrl($this->config['page_cart'], '', '', $full));
		}
		/*
		if (empty($_SESSION['minishop']['delivery'])) {
			header('Location: ' . $this->modx->makeUrl($this->config['page_delivery'], '', '', $full));
		}
		if (empty($_SESSION['minishop']['address']) && $_SESSION['minishop']['delivery'] != 'self') {
			header('Location: ' . $this->modx->makeUrl($this->config['page_address'], '', '', $full));
		}
		*/
		// Проверка captcha
		/*
		if ((int) $_POST['captcha'] != $_SESSION['minishop']['captcha']) {
			$this->modx->setPlaceholder('error.captcha', $this->modx->lexicon('ms.captcha.error'));
			return $this->confirmOrder();
		}
		*/
		// Проверка неизменности содержимого корзины при подтверждении заказа
		/*
		$hash = $this->getCartHash();
		if ($hash != $_SESSION['minishop']['cart_hash']) {
			$this->modx->setPlaceholder('error.cart_hash', $this->modx->lexicon('ms.cart_hash.error'));
			return $this->confirmOrder();
		}
		*/
		
		// Проверка авторизации юзера и регистрация, при необходимости
		if ($this->modx->user->isAuthenticated()) {
			$uid = $this->modx->user->id;
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
			//$this->print_arr($address->toArray());
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
		
		$cart_sum = 0;
		$oid = $order->get('id');
		foreach ($cart as $v) {
			$res = $this->modx->newObject('ModOrderedGoods');
			$res->set('oid', $oid);
			$res->set('gid', $v['id']);
			$res->set('price', $v['price']);
			$res->set('sum', $v['price'] * $v['num']);
			$res->set('num', $v['num']);
			$res->set('data', json_encode($v['data']));
			$res->save();
			$cart_sum += $v['price'] * $v['num'];
		}
		$order->set('sum', $cart_sum);
		$order->save();

		// Изменения статуса и отправка писем
		if ($this->changeOrderStatus($order->get('id'), $this->config['ms_status_new'])) {
			unset($_SESSION['minishop']);
			return $this->modx->getChunk($this->config['tplSubmitOrderSuccess']);
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
		if (!is_object($this->modx->mail)) {
			$this->modx->getService('mail', 'mail.modPHPMailer');
		}
		$this->modx->mail->set(modMail::MAIL_FROM, $this->modx->getOption('emailsender'));
		$this->modx->mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('site_name'));
		$this->modx->mail->setHTML(true);
		$this->modx->mail->set(modMail::MAIL_SUBJECT, $subject);
		$this->modx->mail->set(modMail::MAIL_BODY, $message);

		$tmp = explode(',', $to);
		foreach ($tmp as $v) {
		  if (!empty($v) && preg_match('/@/', $v)) {
			$this->modx->mail->address('to', $v);
		  }
		}
		//$this->modx->mail->attach($tmp_dir.$file, $file);
		if (!$this->modx->mail->send()) {
			$this->modx->log(modX::LOG_LEVEL_ERROR,'An error occurred while trying to send the email: '.$this->modx->mail->mailer->ErrorInfo);
		}
		$this->modx->mail->reset();
	}



}