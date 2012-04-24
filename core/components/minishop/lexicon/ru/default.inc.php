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
 * Default Russian Lexicon Entries for miniShop
 *
 * @package minishop
 * @subpackage lexicon
 */
$_lang['minishop'] = 'miniShop';
$_lang['ms.menu_desc'] = 'Удобный интернет-магазин для MODX Revolution';
$_lang['ms.order'] = 'Заказ';
$_lang['ms.orders'] = 'Заказы';
$_lang['ms.goods'] = 'Товары';
$_lang['ms.warehouse'] = 'Склад';
$_lang['ms.payment'] = 'Оплата';
$_lang['ms.warehouses'] = 'Склады';
$_lang['ms.payments'] = 'Способы оплаты';
$_lang['ms.category'] = 'Категория';
$_lang['ms.categories'] = 'Категории';
$_lang['ms.address'] = 'Адрес';
$_lang['ms.orderhistory'] = 'История';
$_lang['ms.delivery'] = 'Доставка';
$_lang['ms.email'] = 'Email';
$_lang['ms.currency'] = 'Валюта';
$_lang['ms.receiver'] = 'Получатель';
$_lang['ms.phone'] = 'Телефон';
$_lang['ms.index'] = 'Индекс';
$_lang['ms.region'] = 'Область';
$_lang['ms.city'] = 'Город';
$_lang['ms.metro'] = 'Метро';
$_lang['ms.street'] = 'Улица';
$_lang['ms.building'] = 'Дом/строение';
$_lang['ms.room'] = 'Офис/квартира';
$_lang['ms.description'] = 'Описание';
$_lang['ms.main'] = 'Основное';
$_lang['ms.price'] = 'Цена';
$_lang['ms.weight'] = 'Вес';
$_lang['ms.name'] = 'Название';
$_lang['ms.article'] = 'Артикул';
$_lang['ms.remains'] = 'Остаток';
$_lang['ms.img'] = 'Изображение';
$_lang['ms.iid'] = 'ItemID';
$_lang['ms.type'] = 'Тип';
$_lang['ms.color'] = 'Цвет';
$_lang['ms.content'] = 'Описание';
$_lang['ms.enabled'] = 'Вкл.';
$_lang['ms.operation'] = 'Операция';
$_lang['ms.properties'] = 'Свойства';
$_lang['ms.permission'] = 'Право доступа';
$_lang['ms.required_field'] = 'Это поле обязательно';
$_lang['ms.no_permission'] = 'У вас нет прав для выполнения этой операции.';
$_lang['ms.permission.description'] = 'Разрешение, которое будет требовать объект от пользователя. Права создаются в разделе "Безопасность &rarr; Контроль доступа"';
$_lang['ms.duplicate'] = 'Сделать копию';
$_lang['ms.duplicate_confirm'] = 'Сделать копию этого товара?';
$_lang['ms.reserved'] = 'Зарезервировано';
$_lang['ms.gallery'] = 'Галерея';
$_lang['ms.file'] = 'Файл';
$_lang['ms.dir'] = 'Директория';


$_lang['ms.warehouse.select'] = 'Выберите склад';
$_lang['ms.warehouse.create'] = 'Создать склад';
$_lang['ms.warehouse.update'] = 'Изменить свойства склада';
$_lang['ms.warehouse.remove'] = 'Удалить склад';
$_lang['ms.warehouse.remove_confirm'] = 'Вы уверены, что хотите удалить <b>склад</b>? <span style="color: brown;">Это также удалит <b>все</b> остатки на этом складе и его методы доставки!</span>';
$_lang['ms.warehouse.err_save'] = 'Ошибка при сохранении склада.';
$_lang['ms.warehouse.err_remove'] = 'Ошибка удаления склада.';
$_lang['ms.warehouse.err_ae'] = 'Склад с таким именем уже существует.';
$_lang['ms.warehouse.err_nf'] = 'Склад не найден.';
$_lang['ms.warehouses.desc.currency'] = 'Валюта в любом виде. Например: <b>руб.</b> или <b>$</b>. В дальнейшем, в зависимости от этого параметра вы сможете формировать цену.';

$_lang['ms.payment.select'] = 'Выберите оплату';
$_lang['ms.payment.create'] = 'Новый способ оплаты';
$_lang['ms.payment.update'] = 'Изменить оплату';
$_lang['ms.payment.remove'] = 'Удалить оплату';
$_lang['ms.payment.remove_confirm'] = 'Вы уверены, что хотите удалить этот способ оплаты?';
$_lang['ms.payment.err_save'] = 'Ошибка при сохранении способа оплаты.';
$_lang['ms.payment.err_remove'] = 'Ошибка удаления способа оплаты.';
$_lang['ms.payment.err_ae'] = 'Способ оплаты с таким именем уже существует.';
$_lang['ms.payment.err_nf'] = 'Способ оплаты не найден.';

$_lang['ms.delivery.create'] = 'Создать доставку';
$_lang['ms.delivery.remove'] = 'Удалить доставку';
$_lang['ms.delivery.update'] = 'Изменить свойства доставки';
$_lang['ms.delivery.remove_confirm'] = 'Вы уверены, что хотите удалить способ доставки?';
$_lang['ms.delivery.err_save'] = 'Ошибка при сохранении доставки.';
$_lang['ms.delivery.err_remove'] = 'Ошибка удаления доставки.';
$_lang['ms.delivery.err_ae'] = 'Доставка с таким названием уже существует.';
$_lang['ms.delivery.err_nf'] = 'Доставка не найдена.';

$_lang['ms.status'] = 'Статус';
$_lang['ms.statuses'] = 'Статусы';
$_lang['ms.statusname'] = 'Статус';
$_lang['ms.status.create'] = 'Создать статус';
$_lang['ms.status.update'] = 'Изменить свойства статуса';
$_lang['ms.status.remove'] = 'Удалить статус';
$_lang['ms.status.remove_confirm'] = 'Вы действительно хотите удалить этот статус?';
$_lang['ms.email2user'] = 'Уведомление покупателю';
$_lang['ms.subject2user'] = 'Тема письма покупателю';
$_lang['ms.body2user'] = 'Чанк письма покупателю';
$_lang['ms.email2manager'] = 'Уведомление менеджеру';
$_lang['ms.subject2manager'] = 'Тема письма менеджеру';
$_lang['ms.body2manager'] = 'Чанк письма менеджеру';
$_lang['ms.status.err_ae'] = 'Это имя уже используется';
$_lang['ms.status.err_save'] = 'Ошибка при сохранении статуса';

$_lang['ms.num'] = 'Номер';
$_lang['ms.id'] = 'ID';
$_lang['ms.uid'] = 'UserID';
$_lang['ms.oid'] = 'OrderID';
$_lang['ms.gid'] = 'GoodsID';
$_lang['ms.cid'] = 'CatID';
$_lang['ms.ip'] = 'IP';
$_lang['ms.timestamp'] = 'Метка времени';
$_lang['ms.username'] = 'Логин';
$_lang['ms.fullname'] = 'Имя';

$_lang['ms.sum'] = 'Сумма';
$_lang['ms.created'] = 'Создан';
$_lang['ms.updated'] = 'Обновлен';
$_lang['ms.comment'] = 'Комментарий';

$_lang['ms.orders.intro_msg'] = 'Раздел управления заказами';
$_lang['ms.goods.intro_msg'] = 'Раздел управления товарами.<br/>В этом разделе вы управляете ресурсами из дерева слева, то есть, все изменения "здесь" меняют ресурсы сайта.';
$_lang['ms.warehouses.intro_msg'] = 'Раздел управления складами и их свойствами';
$_lang['ms.payments.intro_msg'] = '<p>Раздел для работы со способами оплаты. Здесь вы можете создать эти способы и задавть им свойства. Сниппет, если вы его укажете, будет вызываться после создания заказа, для обработки способа оплаты. </p><p>Эти способы оплаты обязательно нужно привязать к методам доставки в соседнем раздел "Склады"</p>';
$_lang['ms.status.intro_msg'] = 'Здесь вы должны создать статусы заказов для дальнейшей работы.';

$_lang['ms.orders.item_err_save'] = 'Заказ с таким id не найден';
$_lang['ms.orders.edit'] = 'Свойства заказа';
$_lang['ms.orders.remove'] = 'Удалить заказ';
$_lang['ms.orders.remove_confirm'] = 'Вы действительно хотите удалить этот заказ?';
$_lang['ms.orders.goods_err_save'] = 'Товар с таким id не найден';
$_lang['ms.orders.search'] = 'Поиск заказа по номеру...';
$_lang['ms.orders.filter_clear'] = 'Очистить';
$_lang['ms.status.select'] = 'Выберите статус заказа';
$_lang['ms.category.select'] = 'Выберите категорию товаров';
$_lang['ms.combo.all'] = 'Все';
$_lang['ms.combo.select'] = 'Выберите';

$_lang['ms.window.editorder'] = 'Изменение свойств заказа';
$_lang['ms.window.editgoods'] = 'Изменение свойств товара';
$_lang['ms.order_err_nf'] = 'Заказ с указанным id не найден';

$_lang['ms.address.createnew'] = 'Заполнить новый адрес получения';

$_lang['ms.goods.price'] = 'Цена';
$_lang['ms.goods.name'] = 'Наименование';
$_lang['ms.goods.num'] = 'Кол-во';
$_lang['ms.goods.sum'] = 'Сумма';
$_lang['ms.goods.create'] = 'Создать товар';
$_lang['ms.goods.change'] = 'Изменить свойства товара';
$_lang['ms.goods.goto_manager_page'] = 'Открыть страницу товара';
$_lang['ms.goods.goto_site_page'] = 'Просмотреть товар на сайте';
$_lang['ms.goods.delete'] = 'Удалить товар';
$_lang['ms.goods.delete_confirm'] = 'Вы действительно хотите удалить товар?<br/>Это поставит метку "удален" и товар исчезнет из списка. В дальнейшем, вы сможете окончательно удалить его из дерева ресурсов, или восстановить.<br/>Также, это удалит дополнительные свойства товара: цену, изображение, артикул и остаток.';
$_lang['ms.goods.err_delete'] = 'Ошибка при удалении товара.';
$_lang['ms.goods.err_ns'] = 'Не указан ID товара.';
$_lang['ms.goods.err_nf'] = 'Товар с указанным ID не найден.';
$_lang['ms.goods.err_wh_ns'] = 'Не указан ID склада товара.';
$_lang['ms.goods.err_save'] = 'Ошибка при сохранении товара.';
$_lang['ms.goods.wh_err_nf'] = 'Не указан склад товара.';
$_lang['ms.goods.duplicate'] = 'Для всех складов';
$_lang['ms.goods.duplicate.desc'] = 'Если вы отметите это чекбокс, то текущее сохранение перезапишет свойства этого товара на всех доступных складах.';
$_lang['ms.goods.add1'] = 'Дополнительное поле 1';
$_lang['ms.goods.add2'] = 'Дополнительное поле 2';
$_lang['ms.goods.add3'] = 'Дополнительное поле 3';
$_lang['ms.goods.data'] = 'Параметры товара';
$_lang['ms.goods.cat0_confirm'] = 'Вы не выбрали категорию товара, вы действительно хотите сохранить его в корень сайта?';


$_lang['ms.gallery.create'] = 'Добавить изображение';
$_lang['ms.gallery.update'] = 'Изменить изображение';
$_lang['ms.gallery.remove'] = 'Удалить изображение';
$_lang['ms.gallery.load'] = 'Загрузить из директории';
$_lang['ms.gallery.load_description'] = '<p>Укажите директорию для поиска изображений.<br/>Вы можете использовать <i>{assets_path}, {base_path}</i>.</p><br/><p>Пример: <i>{base_path}inc/images/products/10/</i></p>';
$_lang['ms.gallery.remove_confirm'] = 'Вы действительно хотите удалить изображение товара? Это не затронет физический файл.';
$_lang['ms.gallery.err_nf'] = 'Не указан id товара для вывода галереи.';
$_lang['ms.gallery.item.err_nf'] = 'Изображение не найдено.';

$_lang['ms.tv.err_nf'] = 'TV параметр не найден';
$_lang['ms.tv.update'] = 'Обновить TV параметр';

$_lang['ms.orderedgoods.add'] = 'Добавить товар';
$_lang['ms.orderedgoods.add_desc'] = 'Выберите товар для добавления в заказ. Если вы оставите пустыми цену и вес - они добавятся автоматически, как при покупке.<br/>Дополнительные параметры необходимо вносить в виде json-строки, например: {"color":"Красный","country":"Россия"}';
$_lang['ms.orderedgoods.remove'] = 'Удалить товар';
$_lang['ms.orderedgoods.remove_confirm'] = 'Вы действиетльно хотите удалить этот товар из заказа?';
$_lang['ms.orderedgoods.update'] = 'Изменить товар';
$_lang['ms.orderedgoods.err_data'] = 'Ошибка сохранения дополнительных параметров';

$_lang['ms.log.old'] = 'Старое значение';
$_lang['ms.log.new'] = 'Значение';

$_lang['ms.addToCart.success'] = 'Товар успешно добавлен в корзину.';
$_lang['ms.addToCart.error'] = 'Ошибка добавления товара.';
$_lang['ms.remFromCart.success'] = 'Товар удален из корзины.';
$_lang['ms.remFromCart.error'] = 'Ошибка удаления товара.';
$_lang['ms.Cart.empty'] = 'Ваша корзина пуста.';

$_lang['ms.delivery.self'] = 'Самовывоз';
$_lang['ms.delivery.err_save'] = 'Ошибка сохранения варианта доставки.';
$_lang['ms.delivery.create'] = 'Создать доставку';
$_lang['ms.delivery.err_ae'] = 'Это название уже существует.';

$_lang['ms.changeCartCount.success'] = 'Изменения успешно сохранены.';
$_lang['ms.changeCartCount.error'] = 'Ошибка при изменении.';

$_lang['ms.saveDelivery.success'] = 'Вы выбрали способ доставки.';

$_lang['ms.validate.address_select'] = 'Вы должны выбрать сохраненный адрес, или ввести новый.';
$_lang['ms.validate.email'] = 'Ваш Email необходим для оформления заказа.';
$_lang['ms.validate.notempty'] = 'Это поле обязательно для заполнения';
$_lang['ms.validate.receiver'] = 'Ф.И.О. должно состоять из букв';
$_lang['ms.validate.index'] = 'Почтовый индекс должен состоять из 6 цифр';
$_lang['ms.validate.phone'] = 'Номер телефона должен состоять из цифр.';

$_lang['ms.captcha.error'] = 'Неверное проверочное число! Попробуйте еще раз.';
$_lang['ms.cart_hash.error'] = 'Вы изменили содержимое корзины в процессе оформления товара. Подтвердите заказ.';

$_lang['ms.vieworder'] = 'Подробности';
$_lang['ms.chunk.select'] = 'Выберите чанк';
$_lang['ms.snippet.select'] = 'Выберите сниппет';
$_lang['ms.template.select'] = 'Выберите шаблон';

$_lang['ms.cart_empty.warning'] = 'При смене склада корзина будет очищена! Продолжить?';

$_lang['ms.payment.error'] = 'Ошибка проведения платежа';
