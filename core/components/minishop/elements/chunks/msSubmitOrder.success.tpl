<script type='text/javascript'>
  var time = 5;
  
  $(document).ready(function() {
    var timer = setInterval(function() {
      var t = $('#time').text() - 1;
      $('#time').text(t)

      if (t <= 0) {
	clearInterval(timer);
	document.location.href = document.location.href;
      }
    }, 1000)
  })
</script>


<h3>Ваш заказ успешно отправлен!</h3>
<p>Скоро с вами свяжется менеджер для уточнения деталей доставки.</p>
<p>Эта страница обновится через <strong><span id="time">5</span></strong> сек.</p>