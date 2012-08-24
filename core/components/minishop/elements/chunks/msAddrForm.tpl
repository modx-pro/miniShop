[[!FormIt?
	&hooks=`hook_msSaveForm,redirect`
	&redirectTo=`[[*id]]`
		&redirectParams=`{"action":"submitOrder"}`
	&placeholderPrefix=``
	&validate=`email:email:required,
		receiver:required,
		street:required,
		comment:stripTags`
]]

<form id="addrForm" method="post" action="[[~[[*id]]]]" class="center" data-target="#addrForm">
	<table id="newAddress">
	<tr>
		<th class="right">Email</th>
		<td>
		<input type="text" name="email" value="[[+email]]" />
		</td>
		<td class="error">[[+error.email]]</td>
	</tr>
	<tr>
		<th class="right">Ф.И.О. получателя</th>
		<td>
		<input type="text" name="receiver" value="[[+receiver]]" />
		</td>
		<td class="error">[[+error.receiver]]</td>
	</tr>
	
	<tr>
		<th class="right">Доставка</th>
		<td>
		<select name="delivery" value="[[+delivery]]">
		[[!miniShop?action=`getDelivery`]]
	</select>
		</td>
		<td class="error">[[+error.delivery]]</td>
	</tr>	 
	
	<tr>
		<th class="right">Телефон</th>
		<td>
		<input type="text" name="phone" value="[[+phone]]" maxlength="12" />
		</td>
		<td class="error">[[+error.phone]]</td>
	</tr>
	<tr>
		<th class="right">Страна</th>
		<td>
		<input type="text" name="country" value="[[+country]]" />
		</td>
		<td class="error">[[+error.country]]</td>
	</tr>
	<tr>
		<th class="right">Индекс</th>
		<td>
		<input type="text" name="index" value="[[+index]]" maxlength="6" />
		</td>
		<td class="error">[[+error.index]]</td>
	</tr>
	<tr>
		<th class="right">Область</th>
		<td>
		<input type="text" name="region" value="[[+region]]" />
		</td>
		<td class="error">[[+error.region]]</td>
	</tr>
	<tr>
		<th class="right">Город</th>
		<td>
		<input type="text" name="city" value="[[+city]]" />
		</td>
		<td class="error">[[+error.city]]</td>
	</tr>
	<tr>
		<th class="right">Метро</th>
		<td>
		<input type="text" name="metro" value="[[+metro]]" />
		</td>
		<td class="error">[[+error.metro]]</td>
	</tr>
	<tr>
		<th class="right">Улица</th>
		<td>
		<input type="text" name="street" value="[[+street]]" />
		</td>
		<td class="error">[[+error.street]]</td>
	</tr>
	<tr>
		<th class="right">Дом/строение</th>
		<td>
		<input type="text" name="building" value="[[+building]]" />
		</td>
		<td class="error">[[+error.building]]</td>
	</tr>
	<tr>
		<th class="right">Квартира/офис</th>
		<td>
		<input type="text" name="room" value="[[+room]]" />
		</td>
		<td class="error">[[+error.room]]</td>
	</tr>
	<tr>
		<th class="right">Комментарий</th>
		<td>
		<textarea name="comment">[[+comment]]</textarea>
		</td>
		<td class="error">[[+error.comment]]</td>
	</tr>	
	</table>
	
	<!--<input type="hidden" name="action" value="saveAddrForm" />-->
	<input type="submit" class="btn btn-primary" value="Отправить заказ">
</form>