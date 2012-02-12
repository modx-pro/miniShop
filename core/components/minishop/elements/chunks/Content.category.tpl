<div id="content" class="category">
  <h1>[[*longtitle:default=`[[*pagetitle]]`]]</h1>
  
  [[!getPage?
    &element=`msGetResources`
    &tpl=`tpl.msGoods.row`
    &limit=`15`
    &sortbyMS=`price`
    &sortdir=`ASC`
  ]]
  
  <div class="pagination">
    <ul>
      [[!+page.nav]]
    </ul>
  </div>
</div>