[[!msGetOrdersPlaceholders?oid=`[[+id]]`]]

<h1>Заказ #[[+num]]</h1>

<h2>Выбранные товары</h2>
<table id="Cart">
  <tr align="center">
      <th>Наименование</th>
      <th>Цена</th>
      <th>Кол-во</th>
      <th>Стоимость</th>
  </tr>
  [[+rows]]
  <tr align="center">
    <td colspan="2"><b>Итого без стоимости доставки:</b></td>
    <td><b id="cartCount">[[+count]]</b> шт.</td>
    <td><b id="cartTotal">[[+total]]</b> руб.</td>
    <td>&nbsp;</td>
  </tr>
</table>

<br/><br/>
  <table>
    <tr>
      <th>Ф.И.О. получателя</th>
      <td>[[+receiver]]</td>
    </tr>
    <tr>
      <th>Телефон</th>
      <td>[[+phone]]</td>
    </tr>
    <tr>
      <th>Индекс</th>
      <td>[[+index]]</td>
    </tr>
    <tr>
      <th>Область</th>
      <td>[[+region]]</td>
    </tr>
    <tr>
      <th>Город</th>
      <td>[[+city]]</td>
    </tr>
    <tr>
      <th>Метро</th>
      <td>[[+metro]]</td>
    </tr>
    <tr>
      <th>Улица</th>
      <td>[[+street]]</td>
    </tr>
    <tr>
      <th>Дом/строение</th>
      <td>[[+building]]</td>
    </tr>
    <tr>
      <th>Квартира/офис</th>
      <td>[[+room]]</td>
    </tr>
    <tr>
      <th>Комментарий</th>
      <td>[[+comment]]</td>
    </tr>  
  </table>