[[!msGetOrdersPlaceholders?oid=`[[+id]]`]]

<h1>Заказ #[[+order.num]]</h1>

<h2>Выбранные товары</h2>
<table id="Cart">
  <tr align="center">
      <th>Наименование</th>
      <th>Цена</th>
      <th>Кол-во</th>
      <th>Стоимость</th>
  </tr>
  [[+cart.rows]]
  <tr align="center">
    <td colspan="2"><b>Итого с учетом доставки:</b></td>
    <td><b id="cartCount">[[+cart.count]]</b> шт.</td>
    <td><b id="cartTotal">[[+cart.total]]</b> руб.</td>
    <td>&nbsp;</td>
  </tr>
</table>

<br/>
<p><b>Если возник сбой при оплате, или вы еще по какой то причине не смогли оплатить заказ -   продолжите процесс покупки <a href="[[++site_url]]cart.html?action=redirectCustomer&oid=[[+order.id]]&email=[[+user.email]]">по этой ссылке</a></b></p>
<br/>

<p><b>Способ доставки:</b> [[+order.delivery_name]] ([[+order.delivery_price]]руб.)</p>

<br/><br/>
  <table>
    <tr>
      <th>Email</th>
      <td>[[+user.email]]</td>
    </tr>
    <tr>
      <th>Ф.И.О. получателя</th>
      <td>[[+addr.receiver]]</td>
    </tr>
    <tr>
      <th>Телефон</th>
      <td>[[+addr.phone]]</td>
    </tr>
    <tr>
      <th>Индекс</th>
      <td>[[+addr.index]]</td>
    </tr>
    <tr>
      <th>Область</th>
      <td>[[+addr.region]]</td>
    </tr>
    <tr>
      <th>Город</th>
      <td>[[+addr.city]]</td>
    </tr>
    <tr>
      <th>Метро</th>
      <td>[[+addr.metro]]</td>
    </tr>
    <tr>
      <th>Улица</th>
      <td>[[+addr.street]]</td>
    </tr>
    <tr>
      <th>Дом/строение</th>
      <td>[[+addr.building]]</td>
    </tr>
    <tr>
      <th>Квартира/офис</th>
      <td>[[+addr.room]]</td>
    </tr>
    <tr>
      <th>Комментарий</th>
      <td>[[+addr.comment]]</td>
    </tr>  
  </table>