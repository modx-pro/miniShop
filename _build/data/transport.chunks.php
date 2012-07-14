<?php
/**
 * Add chunks to build
 * 
 * @package minishop
 * @subpackage build
 */
$chunks = array();

$chunks[0]= $modx->newObject('modChunk');
$chunks[0]->fromArray(array(
    'id' => 0,
    'name' => 'Content.category',
    'description' => 'Main chunk for listing goods category',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/Content.category.tpl'),
),'',true,true);

$chunks[1]= $modx->newObject('modChunk');
$chunks[1]->fromArray(array(
    'id' => 0,
    'name' => 'Content.goods',
    'description' => 'Main chunk for goods page',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/Content.goods.tpl'),
),'',true,true);

$chunks[2]= $modx->newObject('modChunk');
$chunks[2]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msAddrForm',
    'description' => 'Order form.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msAddrForm.tpl'),
),'',true,true);

$chunks[3]= $modx->newObject('modChunk');
$chunks[3]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msCart.outer',
    'description' => 'Shopping cart outer.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msCart.outer.tpl'),
),'',true,true);

$chunks[4]= $modx->newObject('modChunk');
$chunks[4]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msCart.row',
    'description' => 'Shopping cart row.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msCart.row.tpl'),
),'',true,true);

$chunks[5]= $modx->newObject('modChunk');
$chunks[5]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msChangeWarehouse',
    'description' => 'Warehouse switch on webpages.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msChangeWarehouse.tpl'),
),'',true,true);

$chunks[6]= $modx->newObject('modChunk');
$chunks[6]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msGoods.row',
    'description' => 'Chunk for getResouces, that listing godds in categories',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msGoods.row.tpl'),
),'',true,true);

$chunks[7]= $modx->newObject('modChunk');
$chunks[7]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msOrderEmail.manager',
    'description' => 'Email notice to manager. This should be selected in status settings in manager.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msOrderEmail.manager.tpl'),
),'',true,true);

$chunks[8]= $modx->newObject('modChunk');
$chunks[8]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msOrderEmail.row',
    'description' => 'One ordrered goods in email notice',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msOrderEmail.row.tpl'),
),'',true,true);

$chunks[9]= $modx->newObject('modChunk');
$chunks[9]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msOrderEmail.user',
    'description' => 'Email notice to customer. This should be selected in status settings in manager.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msOrderEmail.user.tpl'),
),'',true,true);

$chunks[10]= $modx->newObject('modChunk');
$chunks[10]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msSubmitOrder.success',
    'description' => 'Thank you for your order!',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msSubmitOrder.success.tpl'),
),'',true,true);

$chunks[11]= $modx->newObject('modChunk');
$chunks[11]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msMyOrdersList',
    'description' => 'Chunk for rich private office of customer. It uses ExtJS from MODX manager and load ExtJS styles from cdn.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msMyOrdersList.tpl'),
),'',true,true);

$chunks[12]= $modx->newObject('modChunk');
$chunks[12]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msDelivery.row',
    'description' => 'Chunk for one delivery method',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msDelivery.row.tpl'),
),'',true,true);

$chunks[13]= $modx->newObject('modChunk');
$chunks[13]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msPayment.form',
    'description' => 'Payment form for z-payment.ru',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msPayment.form.tpl'),
),'',true,true);

$chunks[14]= $modx->newObject('modChunk');
$chunks[14]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msGallery.row',
    'description' => 'One image of product',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msGallery.row.tpl'),
),'',true,true);

$chunks[15]= $modx->newObject('modChunk');
$chunks[15]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msCart.mini',
    'description' => 'Chunk of minicart',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msCart.mini.tpl'),
),'',true,true);

$chunks[16]= $modx->newObject('modChunk');
$chunks[16]->fromArray(array(
    'id' => 0,
    'name' => 'tpl.msPayment.row',
    'description' => 'Chunk for one payment method',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/chunks/msPayment.row.tpl'),
),'',true,true);

return $chunks;
