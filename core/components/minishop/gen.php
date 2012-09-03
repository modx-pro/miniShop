<?php

function rrmdir($dir) { 
	if (is_dir($dir)) { 
		$objects = scandir($dir); 
		foreach ($objects as $object) { 
			if ($object != "." && $object != "..") { 
				if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
			} 
		} 
	reset($objects); 
	rmdir($dir); 
	} 
} 


// Имя Класса. Так будет потом называться Класс при вызове $modx->loadClass()
$obj = 'minishop';
$tablePrefix='modx_ms_';

// Папка, где будет записана XML-схема и все файлы создаваемого объекта
// Путь к файлам класса вы будете потом прописывать в вызове метода $modx->loadClass();
$Model = dirname(__FILE__).'/model/';
$Schema = dirname(__FILE__).'/model/schema/';
// Удаляем старые файлы
rrmdir($Model.$obj .'/mysql');
unlink($Schema.$obj.'.mysql.schema.xml');
// Файл-схема
$xml = $Schema.$obj.'.mysql.schema.xml';

/*******************************************************/


// Подгружаем основной файл-конфиг сайта или самим придется прописывать все основные настройки
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/core/config/config.inc.php';

// Подружаем основной  класс MODx
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

// Инициализируем класс MODx
$modx= new modX();

// Устанавливаем настройки логирования
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');

// !!! Обязательно!
// Подгружаем основной класс-пакер
$modx->loadClass('transport.modPackageBuilder', '', false, true);

// Указатель типа базы данных (MySQL / MsSQL и т.п.)
$manager = $modx->getManager();

// Класс-генератор схем
$generator = $manager->getGenerator();


// Генерируем файл-XML
$generator->writeSchema($xml, $obj, 'xPDOObject', $tablePrefix, $restrictPrefix=true  );

// Создает классы и мапы (php) по схеме xml
$tmp = str_replace('table="mod', 'table="ms_mod', file_get_contents($xml));
file_put_contents($xml, $tmp);

$generator->parseSchema($xml, $Model);

print "<br /><br />Выполнено";

?>