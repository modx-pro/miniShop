<table style="width:80%; margin:auto;" id="Cart">
  <tr align="center">
      <td>&nbsp;</td>
      <td>Наименование</td>
      <td>Цена</td>
      <td>Кол-во</td>
      <td>Стоимость</td>
      <td>&nbsp;</td>
  </tr>                  
  
  [[+rows]]
  
  <tr align="center">
    <td colspan="3"><b>Итого без стоимости доставки:</b></td>
    <td><b id="cartCount">[[+count]]</b> шт.</td>
    <td><b id="cartTotal">[[+total]]</b> руб.</td>
    <td>&nbsp;</td>
  </tr>
</table>

[[+total:gt=`0`:then=`
  <br/><br/>
  <h2>Оформление заказа</h2>
  [[!$tpl.msAddrForm]]
`:else=``]]
