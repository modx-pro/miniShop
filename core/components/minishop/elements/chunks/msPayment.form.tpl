<!DOCTYPE html>
<html>
  
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  </head>

  <body>
    <form id="payForm" method="post" action="https://z-payment.ru/merchant.php">
      <input name="LMI_PAYMENT_NO" type="hidden" value="[[+id]]" />
      <input name="LMI_PAYMENT_AMOUNT" type="hidden" value="[[+sum]]" />
      <input name="CLIENT_MAIL" type="hidden" value="[[+email]]" />
      <input name="LMI_PAYMENT_DESC" type="hidden" value="Order #[[+num]]" />
      <input name="LMI_PAYEE_PURSE" type="hidden" value="[[++minishop.payment_shopid]]" />
      <input name="ZP_DEVELOPER" type="hidden" value="ZP97337015" />
    </form>
    
    <script type='text/javascript'>
      document.getElementById("payForm").submit();
    </script>

  </body>

</html>