<script type="text/javascript">
  $(document).ready(function() {

    $('#change_warehouse').live('change', function() {
      if (confirm('[[%ms.cart_empty.warning]]')) {
        $('#change_warehouse_form').submit();
      }
      else {
	return false;
      }
    })
  })
</script>
<form action="[[~[[*id]]]]" method="post" id="change_warehouse_form" class="navbar-text">
  <select name="warehouse" id="change_warehouse">
    [[+options]]
  </select>
  <input type="hidden" name="action" value="changeWarehouse">
</form>