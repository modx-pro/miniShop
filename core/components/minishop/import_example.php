<?php

// Таблица для транслита имени в alias
$translit_arr =  array ('&'=>'and','%'=>'','\''=>'','À'=>'A','À'=>'A','Á'=>'A','Á'=>'A','Â'=>'A','Â'=>'A','Ã'=>'A','Ã'=>'A','Ä'=>'e','Ä'=>'A','Å'=>'A','Å'=>'A','Æ'=>'e','Æ'=>'E','Ā'=>'A','Ą'=>'A','Ă'=>'A','Ç'=>'C','Ç'=>'C','Ć'=>'C','Č'=>'C','Ĉ'=>'C','Ċ'=>'C','Ď'=>'D','Đ'=>'D','È'=>'E','È'=>'E','É'=>'E','É'=>'E','Ê'=>'E','Ê'=>'E','Ë'=>'E','Ë'=>'E','Ē'=>'E','Ę'=>'E','Ě'=>'E','Ĕ'=>'E','Ė'=>'E','Ĝ'=>'G','Ğ'=>'G','Ġ'=>'G','Ģ'=>'G','Ĥ'=>'H','Ħ'=>'H','Ì'=>'I','Ì'=>'I','Í'=>'I','Í'=>'I','Î'=>'I','Î'=>'I','Ï'=>'I','Ï'=>'I','Ī'=>'I','Ĩ'=>'I','Ĭ'=>'I','Į'=>'I','İ'=>'I','Ĳ'=>'J','Ĵ'=>'J','Ķ'=>'K','Ľ'=>'K','Ĺ'=>'K','Ļ'=>'K','Ŀ'=>'K','Ñ'=>'N','Ñ'=>'N','Ń'=>'N','Ň'=>'N','Ņ'=>'N','Ŋ'=>'N','Ò'=>'O','Ò'=>'O','Ó'=>'O','Ó'=>'O','Ô'=>'O','Ô'=>'O','Õ'=>'O','Õ'=>'O','Ö'=>'e','Ö'=>'e','Ø'=>'O','Ø'=>'O','Ō'=>'O','Ő'=>'O','Ŏ'=>'O','Œ'=>'E','Ŕ'=>'R','Ř'=>'R','Ŗ'=>'R','Ś'=>'S','Ş'=>'S','Ŝ'=>'S','Ș'=>'S','Ť'=>'T','Ţ'=>'T','Ŧ'=>'T','Ț'=>'T','Ù'=>'U','Ù'=>'U','Ú'=>'U','Ú'=>'U','Û'=>'U','Û'=>'U','Ü'=>'e','Ū'=>'U','Ü'=>'e','Ů'=>'U','Ű'=>'U','Ŭ'=>'U','Ũ'=>'U','Ų'=>'U','Ŵ'=>'W','Ŷ'=>'Y','Ÿ'=>'Y','Ź'=>'Z','Ż'=>'Z','à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'e','ä'=>'e','å'=>'a','ā'=>'a','ą'=>'a','ă'=>'a','å'=>'a','æ'=>'e','ç'=>'c','ć'=>'c','č'=>'c','ĉ'=>'c','ċ'=>'c','ď'=>'d','đ'=>'d','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ē'=>'e','ę'=>'e','ě'=>'e','ĕ'=>'e','ė'=>'e','ƒ'=>'f','ĝ'=>'g','ğ'=>'g','ġ'=>'g','ģ'=>'g','ĥ'=>'h','ħ'=>'h','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ī'=>'i','ĩ'=>'i','ĭ'=>'i','į'=>'i','ı'=>'i','ĳ'=>'j','ĵ'=>'j','ķ'=>'k','ĸ'=>'k','ł'=>'l','ľ'=>'l','ĺ'=>'l','ļ'=>'l','ŀ'=>'l','ñ'=>'n','ń'=>'n','ň'=>'n','ņ'=>'n','ŉ'=>'n','ŋ'=>'n','ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'e','ö'=>'e','ø'=>'o','ō'=>'o','ő'=>'o','ŏ'=>'o','œ'=>'e','ŕ'=>'r','ř'=>'r','ŗ'=>'r','ù'=>'u','ú'=>'u','û'=>'u','ü'=>'e','ū'=>'u','ü'=>'e','ů'=>'u','ű'=>'u','ŭ'=>'u','ũ'=>'u','ų'=>'u','ŵ'=>'w','ÿ'=>'y','ŷ'=>'y','ż'=>'z','ź'=>'z','ß'=>'s','ſ'=>'s','Α'=>'A','Ά'=>'A','Β'=>'B','Γ'=>'G','Δ'=>'D','Ε'=>'E','Έ'=>'E','Ζ'=>'Z','Η'=>'I','Ή'=>'I','Θ'=>'TH','Ι'=>'I','Ί'=>'I','Ϊ'=>'I','Κ'=>'K','Λ'=>'L','Μ'=>'M','Ν'=>'N','Ξ'=>'KS','Ο'=>'O','Ό'=>'O','Π'=>'P','Ρ'=>'R','Σ'=>'S','Τ'=>'T','Υ'=>'Y','Ύ'=>'Y','Ϋ'=>'Y','Φ'=>'F','Χ'=>'X','Ψ'=>'PS','Ω'=>'O','Ώ'=>'O','α'=>'a','ά'=>'a','β'=>'b','γ'=>'g','δ'=>'d','ε'=>'e','έ'=>'e','ζ'=>'z','η'=>'i','ή'=>'i','θ'=>'th','ι'=>'i','ί'=>'i','ϊ'=>'i','ΐ'=>'i','κ'=>'k','λ'=>'l','μ'=>'m','ν'=>'n','ξ'=>'ks','ο'=>'o','ό'=>'o','π'=>'p','ρ'=>'r','σ'=>'s','τ'=>'t','υ'=>'y','ύ'=>'y','ϋ'=>'y','ΰ'=>'y','φ'=>'f','χ'=>'x','ψ'=>'ps','ω'=>'o','ώ'=>'o','А'=>'a','Б'=>'b','В'=>'v','Г'=>'g','Д'=>'d','Е'=>'e','Ё'=>'yo','Ж'=>'zh','З'=>'z','И'=>'i','Й'=>'j','К'=>'k','Л'=>'l','М'=>'m','Н'=>'n','О'=>'o','П'=>'p','Р'=>'r','С'=>'s','Т'=>'t','У'=>'u','Ф'=>'f','Х'=>'x','Ц'=>'cz','Ч'=>'ch','Ш'=>'sh','Щ'=>'shh','Ъ'=>'','Ы'=>'yi','Ь'=>'','Э'=>'e','Ю'=>'yu','Я'=>'ya','а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'yo','ж'=>'zh','з'=>'z','и'=>'i','й'=>'j','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'x','ц'=>'cz','ч'=>'ch','ш'=>'sh','щ'=>'shh','ъ'=>'','ы'=>'yi','ь'=>'','э'=>'e','ю'=>'yu','я'=>'ya',' ' => '-');

// Пробуем увеличить время выполнения скрипта
set_time_limit(600);
// Уровень ошибок
//ini_set('display_errors', 0);

require_once dirname(dirname(dirname(dirname(__FILE__)))).'/core/config/config.inc.php';
include_once MODX_CORE_PATH . 'model/modx/modx.class.php';

$modx= new modX();
$modx->initialize('web');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');

$miniShop = $modx->getService('minishop','miniShop',$modx->getOption('minishop.core_path',null,$modx->getOption('core_path').'components/minishop/').'model/minishop/');
if (!($miniShop instanceof miniShop)) return '';

$file = 'import.csv';	// Данные
$cat_root = 2;			// Каталог по категориям
$vend_root = 3;			// Каталог по производителям
$wid = 1;				// Номер склада
$main_tpl = 1;			// Номер обычного шаблона
$cat_tpl = 2;			// Номер шаблона категорий
$goods_tpl = 3;			// Номер шаблона товаров
$cat1 = 'catalog1/';	// Корневой алиас первого каталога
$cat2 = 'catalog2/';	// Корневой алиас второго каталога

die('disabled');		// Отключен - защита от случайного запуска
$save = 0;				// 1 - сохраняем, 0 - нет



// Работаем над импортом файла
if (($tmp = fopen($file, "r")) !== false) {
	while (($csv = fgetcsv($tmp, 0, ';')) !== false) {

	$article = $csv[0];											// Артикул
	$vendor = trim($csv[1]);									// Производитель
	//$vendor_img = 'assets/images/vendors/'.$csv[2];			// Логотип производителя
	$category = trim($csv[3]);									// Категория товара
	$sub_category = trim($csv[4]);								// Подкатегория товара
	$pagetitle = trim($csv[5]);									// Название товара
	$longtitle = trim($csv[6]);									// Расширенное название
	$content = $csv[7];											// Содержимое
	$img = 'assets/images/products/'.$csv[0].'.png';			// Изображение


	////////////////////////////////////////////////////////////
	// Шаг 1
	// Производитель товара
	if (!$res_vendor = $modx->getObject('modResource', array('parent' => $vend_root, 'pagetitle' => $vendor))) {
		$res_vendor = $modx->newObject('modResource');
		
		$alias = strtolower(strtr($vendor, $translit_arr));
		$uri = strtolower($cat2.$alias.'/');
		
		$res_vendor->fromArray(array(
			'pagetitle' => $vendor
			,'published' => 1
			,'parent' => $vend_root
			,'template' => $cat_tpl
			,'isfolder' => 1
			,'uri' => $uri
			,'alias' => $alias
		));
		if ($save) {
			$res_vendor->save();
		}
	}
	$id_vendor = $res_vendor->get('id');
	////////////////////////////////////////////////////////////


	////////////////////////////////////////////////////////////
	// Шаг 2
	// Категория товара
	if (!$res_category = $modx->getObject('modResource', array('parent' => $cat_root, 'pagetitle' => $category))) {
		$res_category = $modx->newObject('modResource');
		
		$alias = strtolower(strtr($category, $translit_arr));
		$uri = strtolower($cat1.$alias.'/');
		
		$res_category->fromArray(array(
			'pagetitle' => $category
			,'published' => 1
			,'parent' => $cat_root
			,'template' => $main_tpl
			,'isfolder' => 1
			,'uri' => $uri
			,'alias' => $alias
		));
		if ($save) {
			$res_category->save();
		}
	}
	$id_category = $res_category->get('id');
	$alias_category = $res_category->get('alias');
	////////////////////////////////////////////////////////////


	////////////////////////////////////////////////////////////
	// Шаг 3
	// ПодКатегория товара
	if (!$res_sub_category = $modx->getObject('modResource', array('parent' => $id_category, 'pagetitle' => $sub_category))) {
		$res_sub_category = $modx->newObject('modResource');
		
		$alias = strtolower(strtr($sub_category, $translit_arr));
		$uri = strtolower($cat1.$alias_category.'/'.$alias.'/');
		
		$res_sub_category->fromArray(array(
			'pagetitle' => $sub_category
			,'published' => 1
			,'parent' => $id_category
			,'template' => $cat_tpl
			,'isfolder' => 1
			,'uri' => $uri
			,'alias' => $alias
		));
		if ($save) {
			$res_sub_category->save();
		}
	}
	$id_sub_category = $res_sub_category->get('id');
	$alias_sub_category = $res_sub_category->get('alias');
	////////////////////////////////////////////////////////////


	////////////////////////////////////////////////////////////
	// Шаг 4
	// Товар
	if (!$res_product = $modx->getObject('modResource', array('parent' => $id_sub_category, 'pagetitle' => $pagetitle))) {
		$res_product = $modx->newObject('modResource');
		
		$alias = strtolower(strtr($pagetitle, $translit_arr));
		$uri = strtolower($cat1.$alias_category.'/'.$alias_sub_category.'/'.$alias.'.html');
		
		$res_product->fromArray(array(
			'pagetitle' => $pagetitle
			,'longtitle' => $longtitle
			,'published' => 1
			,'parent' => $id_sub_category
			,'template' => $goods_tpl
			,'isfolder' => 0
			,'uri' => $uri
			,'alias' => $alias
			,'content' => $content
		));
		if ($save) {
			$res_product->save();
		}
	}
	$id_product = $res_product->get('id');
	////////////////////////////////////////////////////////////


	////////////////////////////////////////////////////////////
	// Шаг 5
	// Дополнительная связь товара и производителя
	if (!$res_categories = $modx->getObject('ModCategories', array('cid' => $id_vendor, 'gid' => $id_product))) {
		$res_categories = $modx->newObject('ModCategories');
		
		$res_categories->fromArray(array(
			'cid' => $id_vendor
			,'gid' => $id_product
		));
		if ($save) {
			$res_categories->save();
		}
	}
	////////////////////////////////////////////////////////////


	////////////////////////////////////////////////////////////
	// Шаг 6
	// Дополнительные параметры товара
	if (!$res_goods = $modx->getObject('ModGoods', array('gid' => $id_product, 'wid' => $wid))) {
		$res_goods = $modx->newObject('ModGoods');
		
		$res_goods->fromArray(array(
			'wid' => $wid
			,'gid' => $id_product
			,'img' => $img
			,'price' => 0
			,'remains' => 1
			,'article' => $article
		));
		if ($save) {
			$res_goods->save();
		}
	}
	////////////////////////////////////////////////////////////
	
	/*
	echo '<pre>';
	print_r($res_goods->toArray());
	echo '</pre>';
	die;
	*/

	// Вывод номера созданного продукта
	echo $id_product.'<br/>';
	}
}
?>