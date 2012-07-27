<div id="miniCart">
	<div id="cart_1" [[+count:gt=`0`:then=`style="display:none"`]]>
		<h4>Корзина</h4>
		Ваша корзина пуста
	</div>
	<div id="cart_2" [[+count:eq=`0`:then=`style="display:none"`]]>
		<h4>Корзина</h4>
		<p>Товаров: <strong id="cart_count">[[+count]]</strong> шт.</p>
		<p>На сумму: <strong id="cart_total">[[+total]]</strong> руб.</p>
		<p><a href="[[~16]]" class="right">Оформить заказ</a></p>
	</div>
</div>