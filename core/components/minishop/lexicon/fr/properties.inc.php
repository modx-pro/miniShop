<?php

$_lang['ms.action'] = 'Action for miniShop snippet. This is mode of snippet work.';
$_lang['ms.tplCartOuter'] = 'Chunk of cart table';
$_lang['ms.tplCartRow'] = 'One row of cart table';
$_lang['ms.tplDeliveryRow'] = 'Chunk of one delivery method';
$_lang['ms.tplPaymentRow'] = 'Chunk of one payment method';
$_lang['ms.tplAddrForm'] = 'Chunk of order form';
$_lang['ms.tplOrderEmailUser'] = 'Email to customer';
$_lang['ms.tplOrderEmailManager'] = 'Email to manager';
$_lang['ms.tplOrderEmailRow'] = 'One ordered product in email';
$_lang['ms.tplSubmitOrderSuccess'] = 'Success message after completing order';
$_lang['ms.tplMyOrdersList'] = 'Chunk for dispaying customer orders in private office';
$_lang['ms.tplPaymentForm'] = 'Chunk of payment form';
$_lang['ms.tplMiniCart'] = 'Chunk of mini cart';
$_lang['ms.debug'] = 'Enable displaying errors?';
$_lang['ms.userGroups'] = 'Comma-separated list of existing groups for registering new customers.';

$_lang['ms.id'] = 'Id of product';
$_lang['ms.tpl'] = 'Chunk for templating results';
$_lang['ms.limit'] = 'Query limit';
$_lang['ms.offset'] = 'Query offset';
$_lang['ms.outputSeparator'] = 'Separator of results';
$_lang['ms.totalVar'] = 'Name for placeholder with results count';
$_lang['ms.sortby'] = 'Sorting order';
$_lang['ms.sortdir'] = 'Sorting direction';
$_lang['ms.onlyImg'] = 'Display only images?';


$_lang['gr.tpl'] = 'Name of a chunk serving as a resource template. NOTE: if not provided, properties are dumped to output for each resource.';
$_lang['gr.tplOdd'] = 'Name of a chunk serving as resource template for resources with an odd idx value (see idx property).';
$_lang['gr.tplFirst'] = 'Name of a chunk serving as resource template for the first resource (see first property).';
$_lang['gr.tplLast'] = 'Name of a chunk serving as resource template for the last resource (see last property).';
$_lang['gr.sortby'] = 'A field name to sort by or JSON object of field names and sortdir for each field, e.g. {"publishedon":"ASC","createdon":"DESC"}. Defaults to publishedon.';
$_lang['gr.sortbyTVType'] = 'An optional type to indicate how to sort on the Template Variable value.';
$_lang['gr.sortbyAlias'] = 'Query alias for sortby field. Defaults to an empty string.';
$_lang['gr.sortbyEscaped'] = 'Determines if the field name specified in sortby should be escaped. Defaults to 0.';
$_lang['gr.sortdir'] = 'Order which to sort by. Defaults to DESC.';
$_lang['gr.sortdirTV'] = 'Order which to sort a Template Variable by. Defaults to DESC.';
$_lang['gr.limit'] = 'Limits the number of resources returned. Defaults to 5.';
$_lang['gr.offset'] = 'An offset of resources returned by the criteria to skip.';
$_lang['gr.tvFilters'] = 'Delimited-list of TemplateVar values to filter resources by. Supports two delimiters and two value search formats. THe first delimiter || represents a logical OR and the primary grouping mechanism.  Within each group you can provide a comma-delimited list of values. These values can be either tied to a specific TemplateVar by name, e.g. myTV==value, or just the value, indicating you are searching for the value in any TemplateVar tied to the Resource. An example would be &tvFilters=`filter2==one,filter1==bar%||filter1==foo`. <br />NOTE: filtering by values uses a LIKE query and % is considered a wildcard. <br />ANOTHER NOTE: This only looks at the raw value set for specific Resource, i. e. there must be a value specifically set for the Resource and it is not evaluated.';
$_lang['gr.depth'] = 'Integer value indicating depth to search for resources from each parent. Defaults to 10.';
$_lang['gr.parents'] = 'Optional. Comma-delimited list of ids serving as parents.';
$_lang['gr.includeContent'] = 'Indicates if the content of each resource should be returned in the results. Defaults to false.';
$_lang['gr.includeTVs'] = 'Indicates if TemplateVar values should be included in the properties available to each resource template. Defaults to false.';
$_lang['gr.includeTVList'] = 'Limits included TVs to those specified as a comma-delimited list of TV names. Defaults to empty.';
$_lang['gr.showHidden'] = 'Indicates if Resources that are hidden from menus should be shown. Defaults to false.';
$_lang['gr.showUnpublished'] = 'Indicates if Resources that are unpublished should be shown. Defaults to false.';
$_lang['gr.showDeleted'] = 'Indicates if Resources that are deleted should be shown. Defaults to false.';
$_lang['gr.resources'] = 'A comma-separated list of resource IDs to exclude or include. IDs with a - in front mean to exclude. Ex: 123,-456 means to include Resource 123, but always exclude Resource 456.';
$_lang['gr.processTVs'] = 'Indicates if TemplateVar values should be rendered as they would on the resource being summarized. Defaults to false.';
$_lang['gr.processTVList'] = 'Limits processed TVs to those specified as a comma-delimited list of TV names; note only includedTVs will be available for processing if specified. Defaults to empty.';
$_lang['gr.tvPrefix'] = 'The prefix for TemplateVar properties. Defaults to: tv.';
$_lang['gr.idx'] = 'You can define the starting idx of the resources, which is an property that is incremented as each resource is rendered.';
$_lang['gr.first'] = 'Define the idx which represents the first resource (see tplFirst). Defaults to 1.';
$_lang['gr.last'] = 'Define the idx which represents the last resource (see tplLast). Defaults to the number of resources being summarized + first - 1';
$_lang['gr.toPlaceholder'] = 'If set, will assign the result to this placeholder instead of outputting it directly.';
$_lang['gr.toSeparatePlaceholders'] = 'If set, will assign EACH result to a separate placeholder named by this param suffixed with a sequential number (starting from 0).';
$_lang['gr.debug'] = 'If true, will send the SQL query to the MODX log. Defaults to false.';
$_lang['gr.where'] = 'A JSON expression of criteria to build any additional where clauses from, e.g. &where=`{{"alias:LIKE":"foo%", "OR:alias:LIKE":"%bar"},{"OR:pagetitle:=":"foobar", "AND:description:=":"raboof"}}`';
$_lang['gr.dbCacheFlag'] = 'Determines how result sets are cached if cache_db is enabled in MODX. 0|false = do not cache result set; 1 = cache result set according to cache settings, any other integer value = number of seconds to cache result set';
$_lang['gr.context'] = 'A comma-delimited list of context keys for limiting results. Default is empty, i.e. do not limit results by context.';

$_lang['ms.sortbyMS'] = 'A field name of ModGoods table to sort by or JSON object of field names and sortdir for each field, e.g. {"price:>":500,"remains:<":15}';


