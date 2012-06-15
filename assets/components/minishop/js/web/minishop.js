url = '/cart.html';

$(document).ready(function() {

	// Проверка состояния корзины
	$.post(url, {action: 'getCartStatus'}, function(data) {
		data = $.parseJSON(data);
		cartStatus(data);
	})

	// Добавление товара в корзину
	$('.addToCartLink').live('click', function() {
		var gid = $(this).data('gid');
                var params = {};
                $('.params').each(function(id, param) {
                        params[param.name] = param.value;			
                });
		$.post(url, {action: 'addToCart', gid: gid, num: 1, data: params}, function(data) {
			data = $.parseJSON(data);
			showResponse(data);
			cartStatus(data);
		})
		
		return false;
	})
	
	// Изменение кол-ва товара в корзине
	$('.changeCartCount').live('change', function() {
		var key = $(this).data('key');
		var val = $(this).val();
		
		if (val <= 0) {
			$(this).parent().parent().remove();
			remFromCart(key);
			return;
		}
		
		var price = $(this).data('price');
		var parent = $(this).parent();
		
		$.post(url, {action: 'changeCartCount', key: key, val: val}, function(data) {
			data = $.parseJSON(data);
			
			var sum = val * price;
			$(parent).next().find('span').text(sum);
			
			$('#cartCount').text(data.count);
			$('#cartTotal').text(data.total);
			$('#cartWeight').text(data.weight);
			
			showResponse(data);
			cartStatus(data);
		})
	})
	
	// Кнопка удаления из корзины
	$('.remFromCartLink').live('click', function() {
		var key = $(this).data('key');
		$(this).parent().parent().remove();
		remFromCart(key);
		return false;
	})
	
	/*
	// Выбор или заполнение адреса
	if ($('#addrForm [name=address]').length > 0) {
		var address = $('#addrForm [name=address]:checked').val();
		if (address != 0) {
			$('#newAddress').hide();
		}
	}
	else {
		$('#newAddress').show();
	}

	
	$('#addrForm [name=address]').live('change', function() {
		if ($(this).val() == 0) {
			$('#newAddress').fadeIn();
		}
		else {
			$('#newAddress').fadeOut();
		}
	})
	
	// Выбор способа доставки
	
	$('.selectDelivery').live('change', function() {
		var delivery = $(this).val();
		$.post(url, {action: 'saveDelivery', id: delivery}, function(data) {
			data = $.parseJSON(data);
			showResponse(data);
			
			$('#link2step3').fadeIn();
		})
	})
	*/
	
	// Отправка заказа через ajaxForm
	$(document).on('submit', '.ajaxForm', function() {
		var target = $(this).data('target');
		$(this).ajaxSubmit({
			target: target
			,data: {json_encode: false}
			,replaceTarget: true
		})
		return false;
	})

})



/*--------------------------*/
/*	Всплывающие сообщения	*/
/*--------------------------*/
function showResponse(data) {
	if (data.status == 'success') {
		success(data.message);
	}
	else if (data.status == 'notice') {
		message(data.message);
	}
	else {
		error(data.message)
	}
}

function error(text) {
	$.stickr({
		note: text,
		//time: 5000,
		className: 'stickr_error',
		position:{right:20,top:20},
		sticked:true
	});
}

function success(text) {
	$.stickr({
		note: text,
		time: 5000,
		className: 'stickr_success',
		position:{right:20,top:20}
	});
}

function message(text) {
	$.stickr({
		note: text,
		time: 5000,
		className: 'stickr_message',
		position:{right:20,top:20}
	});
}

/*------------------------------------*/
// Удаление товара из корзины
function remFromCart(key) {
	$.post(url, {action: 'remFromCart', key: key}, function(data) {
		data = $.parseJSON(data);
		if (data.total <= 0) {
			document.location.href = document.location.href;
		}

		$('#cartCount').text(data.count);
		$('#cartTotal').text(data.total);
		$('#cartWeight').text(data.weight);
		
		showResponse(data);
		cartStatus(data);
	})
}
// Склонение чистительных
function decOfNum(number, titles)  
{  
    cases = [2, 0, 1, 1, 1, 2];  
    return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];  
}  

// Обновление корзиты в подвале
function cartStatus(data) {
	var total = data.total;
	var count = data.count;
	var dec = decOfNum(data.count, ['товар', 'товара', 'товаров']);
	if (count > 0) {
		if ($("#cartLink").find('span').length > 0) {
			$("#cartLink").find('span').text('('+count+')')
		}
		else {
			$("#cartLink").html($("#cartLink").text() + ' <span>('+count+')</span>')
		}
		$("#cartLink").attr('title', 'В корзине сейчас '+count+' '+dec+', на сумму: '+total+'руб.')
	}
	else {
		$("#cartLink").find('span').remove()
		$("#cartLink").attr('title', 'Корзина пуста')
	}
}