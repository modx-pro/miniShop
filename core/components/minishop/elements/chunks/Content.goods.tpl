[[!msGetGoodsPlaceholders]]
<div id="content" class="goods">
  <h1>[[*longtitle:default=`[[*pagetitle]]`]]</h1>
  <hr />
  <div class="row show-grid">
    <div class="span4" data-original-title="">
      [[+img:isnot=``:then=`<img src="[[+img]]" alt="" title="" />`:else=`nophoto`]]
    </div>
    <div class="span8">
      <div class="row show-grid">
	<div class="span2" data-original-title="">Артикул:</div>
	<div class="span4" data-original-title="">[[+article]]</div>
      </div>
      <div class="row show-grid">
	<div class="span2" data-original-title="">Цена:</div>
	<div class="span4" data-original-title=""><strong>[[+price:default=`0`]]</strong> [[+currency:default=`руб.`]]</div>
      </div>
      <div class="row show-grid">
	<div class="span2" data-original-title="">Вес:</div>
	<div class="span4" data-original-title=""><strong>[[+weight:default=`0`]]</strong> кг</div>
      </div>
      <div class="row show-grid">
	<div class="span2" data-original-title="">Текущий склад:</div>
	<div class="span4" data-original-title="">[[+warehouse]]</div>
      </div> 
      <div class="row show-grid">
	<div class="span2" data-original-title="">В наличии:</div>
	<div class="span4" data-original-title="">[[+remains:gt=`0`:then=`Да`:else=`Нет`]]</div>
      </div>   
      
      <br />
      [[+remains:gt=`0`:then=`<i class="icon-barcode"></i> <a href="#" class="addToCartLink" data-gid="[[*id]]">Добавить в корзину</a>`]]
    </div>
  </div>
  
  [[*content]]
</div>