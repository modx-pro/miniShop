<script type='text/javascript'>
  
  var time = 5;
  $(document).ready(function() {
    var timer = setInterval(function() {
      var t = $('#time').text() - 1;
      $('#time').text(t)

      if (t <= 0) {
	clearInterval(timer);
	document.location.href = '[[~[[*id]]]]?action=redirectCustomer&oid=[[+id]]&email=[[+email]]';
      }
    }, 1000)
  })
  
</script>


<h3>Ваш заказ #[[+num]] успешно отправлен!</h3>
<p>Скоро с вами свяжется менеджер для уточнения деталей доставки.</p>

<p>Через <strong><span id="time">5</span></strong> сек. вы будуте отправлены на сайт платежного агрегатора <a href="http://z-payment.ru" target="_blank">z-payment.tu</a> для оплаты заказа.
<br/>Ссылка на оплату заказа также есть в почтовом уведомлении о новом заказе.</p>

<p>Кликните по <a href="[[~[[*id]]]]?action=redirectCustomer&oid=[[+oid]]&email=[[+email]]">ссылке</a>, если надоело ждать.</p>

