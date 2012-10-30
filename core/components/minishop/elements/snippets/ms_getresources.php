<?php
$output = array();
$outputSeparator = isset($outputSeparator) ? $outputSeparator : "\n";

/* set default properties */
$tpl = !empty($tpl) ? $tpl : '';
$includeContent = !empty($includeContent) ? true : false;
$includeTVs = !empty($includeTVs) ? true : false;
$includeTVList = !empty($includeTVList) ? explode(',', $includeTVList) : array();
$processTVs = !empty($processTVs) ? true : false;
$processTVList = !empty($processTVList) ? explode(',', $processTVList) : array();
$prepareTVs = !empty($prepareTVs) ? true : false;
$prepareTVList = !empty($prepareTVList) ? explode(',', $prepareTVList) : array();
$tvPrefix = isset($tvPrefix) ? $tvPrefix : 'tv.';
$parents = (!empty($parents) || $parents === '0') ? explode(',', $parents) : array($modx->resource->get('id'));
array_walk($parents, 'trim');
$parents = array_unique($parents);
$depth = isset($depth) ? (integer) $depth : 10;

$tvFiltersOrDelimiter = isset($tvFiltersOrDelimiter) ? $tvFiltersOrDelimiter : '||';
$tvFiltersAndDelimiter = isset($tvFiltersAndDelimiter) ? $tvFiltersAndDelimiter : ',';
$tvFilters = !empty($tvFilters) ? explode($tvFiltersOrDelimiter, $tvFilters) : array();

$where = !empty($where) ? $modx->fromJSON($where) : array();
$showUnpublished = !empty($showUnpublished) ? true : false;
$showDeleted = !empty($showDeleted) ? true : false;

$sortby = isset($sortby) ? $sortby : 'publishedon';
$sortbyTV = isset($sortbyTV) ? $sortbyTV : '';
$sortbyMS = isset($sortbyMS) ? $sortbyMS : '';	// add by bezumkin 12.02.2012
$sortbyAlias = isset($sortbyAlias) ? $sortbyAlias : 'modResource';
$sortbyEscaped = !empty($sortbyEscaped) ? true : false;
$sortdir = isset($sortdir) ? $sortdir : 'DESC';
$sortdirTV = isset($sortdirTV) ? $sortdirTV : 'DESC';
$limit = isset($limit) ? (integer) $limit : 5;
$offset = isset($offset) ? (integer) $offset : 0;
$totalVar = !empty($totalVar) ? $totalVar : 'total';

$cacheChunks = !empty($cacheChunks) ? true : false;
$cacheTime = !empty($cacheTime) ? $cacheTime : 1800;

$dbCacheFlag = !isset($dbCacheFlag) ? false : $dbCacheFlag;
if (is_string($dbCacheFlag) || is_numeric($dbCacheFlag)) {
	if ($dbCacheFlag == '0') {
		$dbCacheFlag = false;
	} elseif ($dbCacheFlag == '1') {
		$dbCacheFlag = true;
	} else {
		$dbCacheFlag = (integer) $dbCacheFlag;
	}
}

/* multiple context support */
$contextArray = array();
$contextSpecified = false;
if (!empty($context)) {
	$contextArray = explode(',',$context);
	array_walk($contextArray, 'trim');
	$contexts = array();
	foreach ($contextArray as $ctx) {
		$contexts[] = $modx->quote($ctx);
	}
	$context = implode(',',$contexts);
	$contextSpecified = true;
	unset($contexts,$ctx);
} else {
	$context = $modx->quote($modx->context->get('key'));
}

$pcMap = array();
$pcQuery = $modx->newQuery('modResource', array('id:IN' => $parents), $dbCacheFlag);
$pcQuery->select(array('id', 'context_key'));
if ($pcQuery->prepare() && $pcQuery->stmt->execute()) {
	foreach ($pcQuery->stmt->fetchAll(PDO::FETCH_ASSOC) as $pcRow) {
		$pcMap[(integer) $pcRow['id']] = $pcRow['context_key'];
	}
}

$children = array();
$parentArray = array();
foreach ($parents as $parent) {
	$parent = (integer) $parent;
	if ($parent === 0) {
		$pchildren = array();
		if ($contextSpecified) {
			foreach ($contextArray as $pCtx) {
				if (!in_array($pCtx, $contextArray)) {
					continue;
				}
				$options = $pCtx !== $modx->context->get('key') ? array('context' => $pCtx) : array();
				$pcchildren = $modx->getChildIds($parent, $depth, $options);
				if (!empty($pcchildren)) $pchildren = array_merge($pchildren, $pcchildren);
			}
		} else {
			$cQuery = $modx->newQuery('modContext', array('key:!=' => 'mgr'));
			$cQuery->select(array('key'));
			if ($cQuery->prepare() && $cQuery->stmt->execute()) {
				foreach ($cQuery->stmt->fetchAll(PDO::FETCH_COLUMN) as $pCtx) {
					$options = $pCtx !== $modx->context->get('key') ? array('context' => $pCtx) : array();
					$pcchildren = $modx->getChildIds($parent, $depth, $options);
					if (!empty($pcchildren)) $pchildren = array_merge($pchildren, $pcchildren);
				}
			}
		}
		$parentArray[] = $parent;
	} else {
		$pContext = array_key_exists($parent, $pcMap) ? $pcMap[$parent] : false;
		if ($debug) $modx->log(modX::LOG_LEVEL_ERROR, "context for {$parent} is {$pContext}");
		if ($pContext && $contextSpecified && !in_array($pContext, $contextArray, true)) {
			$parent = next($parents);
			continue;
		}
		$parentArray[] = $parent;
		$options = !empty($pContext) && $pContext !== $modx->context->get('key') ? array('context' => $pContext) : array();
		$pchildren = $modx->getChildIds($parent, $depth, $options);
	}
	if (!empty($pchildren)) $children = array_merge($children, $pchildren);
	$parent = next($parents);
}

// added by bezumkin 22/02.2012
if (!isset($modx->miniShop) || !is_object($modx->miniShop)) {
	$modx->miniShop = $modx->getService('minishop','miniShop', $modx->getOption('core_path').'components/minishop/model/minishop/', $scriptProperties);
	if (!($modx->miniShop instanceof miniShop)) return '';
}
$incats = $modx->miniShop->getGoodsByCategories($parentArray);
// eof add
$parents = array_merge($parentArray, $children);

/* build query */
$criteria = array("modResource.parent IN (" . implode(',', $parents) . ")");
// added by bezumkin 22.02.2012
if (!empty($incats)) {
	$criteria[0] .= " OR modResource.id IN (" . implode(',', $incats) . ")"; 
}
// eof add
if ($contextSpecified) {
	$contextResourceTbl = $modx->getTableName('modContextResource');
	$criteria[] = "(modResource.context_key IN ({$context}) OR EXISTS(SELECT 1 FROM {$contextResourceTbl} ctx WHERE ctx.resource = modResource.id AND ctx.context_key IN ({$context})))";
}
if (empty($showDeleted)) {
	$criteria['deleted'] = '0';
}
if (empty($showUnpublished)) {
	$criteria['published'] = '1';
}
if (empty($showHidden)) {
	$criteria['hidemenu'] = '0';
}
if (!empty($hideContainers)) {
	$criteria['isfolder'] = '0';
}
$criteria = $modx->newQuery('modResource', $criteria);
if (!empty($tvFilters)) {
	$tmplVarTbl = $modx->getTableName('modTemplateVar');
	$tmplVarResourceTbl = $modx->getTableName('modTemplateVarResource');
	$conditions = array();
	$operators = array(
		'<=>' => '<=>',
		'===' => '=',
		'!==' => '!=',
		'<>' => '<>',
		'==' => 'LIKE',
		'!=' => 'NOT LIKE',
		'<<' => '<',
		'<=' => '<=',
		'=<' => '=<',
		'>>' => '>',
		'>=' => '>=',
		'=>' => '=>'
	);
	foreach ($tvFilters as $fGroup => $tvFilter) {
		$filterGroup = array();
		$filters = explode($tvFiltersAndDelimiter, $tvFilter);
		$multiple = count($filters) > 0;
		foreach ($filters as $filter) {
			$operator = '==';
			$sqlOperator = 'LIKE';
			foreach ($operators as $op => $opSymbol) {
				if (strpos($filter, $op, 1) !== false) {
					$operator = $op;
					$sqlOperator = $opSymbol;
					break;
				}
			}
			$tvValueField = 'tvr.value';
			$tvDefaultField = 'tv.default_text';
			$f = explode($operator, $filter);
			if (count($f) == 2) {
				$tvName = $modx->quote($f[0]);
				if (is_numeric($f[1]) && !in_array($sqlOperator, array('LIKE', 'NOT LIKE'))) {
					$tvValue = $f[1];
					if ($f[1] == (integer)$f[1]) {
						$tvValueField = "CAST({$tvValueField} AS SIGNED INTEGER)";
						$tvDefaultField = "CAST({$tvDefaultField} AS SIGNED INTEGER)";
					} else {
						$tvValueField = "CAST({$tvValueField} AS DECIMAL)";
						$tvDefaultField = "CAST({$tvDefaultField} AS DECIMAL)";
					}
				} else {
					$tvValue = $modx->quote($f[1]);
				}
				if ($multiple) {
					$filterGroup[] =
						"(EXISTS (SELECT 1 FROM {$tmplVarResourceTbl} tvr JOIN {$tmplVarTbl} tv ON {$tvValueField} {$sqlOperator} {$tvValue} AND tv.name = {$tvName} AND tv.id = tvr.tmplvarid WHERE tvr.contentid = modResource.id) " .
						"OR EXISTS (SELECT 1 FROM {$tmplVarTbl} tv WHERE tv.name = {$tvName} AND {$tvDefaultField} {$sqlOperator} {$tvValue} AND tv.id NOT IN (SELECT tmplvarid FROM {$tmplVarResourceTbl} WHERE contentid = modResource.id)) " .
						")";
				} else {
					$filterGroup =
						"(EXISTS (SELECT 1 FROM {$tmplVarResourceTbl} tvr JOIN {$tmplVarTbl} tv ON {$tvValueField} {$sqlOperator} {$tvValue} AND tv.name = {$tvName} AND tv.id = tvr.tmplvarid WHERE tvr.contentid = modResource.id) " .
						"OR EXISTS (SELECT 1 FROM {$tmplVarTbl} tv WHERE tv.name = {$tvName} AND {$tvDefaultField} {$sqlOperator} {$tvValue} AND tv.id NOT IN (SELECT tmplvarid FROM {$tmplVarResourceTbl} WHERE contentid = modResource.id)) " .
						")";
				}
			} elseif (count($f) == 1) {
				$tvValue = $modx->quote($f[0]);
				if ($multiple) {
					$filterGroup[] = "EXISTS (SELECT 1 FROM {$tmplVarResourceTbl} tvr JOIN {$tmplVarTbl} tv ON {$tvValueField} {$sqlOperator} {$tvValue} AND tv.id = tvr.tmplvarid WHERE tvr.contentid = modResource.id)";
				} else {
					$filterGroup = "EXISTS (SELECT 1 FROM {$tmplVarResourceTbl} tvr JOIN {$tmplVarTbl} tv ON {$tvValueField} {$sqlOperator} {$tvValue} AND tv.id = tvr.tmplvarid WHERE tvr.contentid = modResource.id)";
				}
			}
		}
		$conditions[] = $filterGroup;
	}
	if (!empty($conditions)) {
		$firstGroup = true;
		foreach ($conditions as $cGroup => $c) {
			if (is_array($c)) {
				$first = true;
				foreach ($c as $cond) {
					if ($first && !$firstGroup) {
						$criteria->condition($criteria->query['where'][0][1], $cond, xPDOQuery::SQL_OR, null, $cGroup);
					} else {
						$criteria->condition($criteria->query['where'][0][1], $cond, xPDOQuery::SQL_AND, null, $cGroup);
					}
					$first = false;
				}
			} else {
				$criteria->condition($criteria->query['where'][0][1], $c, $firstGroup ? xPDOQuery::SQL_AND : xPDOQuery::SQL_OR, null, $cGroup);
			}
			$firstGroup = false;
		}
	}
}
/* include/exclude resources, via &resources=`123,-456` prop */
if (!empty($resources)) {
	$resourceConditions = array();
	$resources = explode(',',$resources);
	$include = array();
	$exclude = array();
	foreach ($resources as $resource) {
		$resource = (int)$resource;
		if ($resource == 0) continue;
		if ($resource < 0) {
			$exclude[] = abs($resource);
		} else {
			$include[] = $resource;
		}
	}
	if (!empty($include)) {
		$criteria->where(array('OR:modResource.id:IN' => $include), xPDOQuery::SQL_OR);
	}
	if (!empty($exclude)) {
		$criteria->where(array('modResource.id:NOT IN' => $exclude), xPDOQuery::SQL_AND, null, 1);
	}
}
if (!empty($where)) {
	$criteria->where($where);
}
// add by bezumkin 01.04.2012
if (!empty($sortbyMS)) {
	$criteria->leftJoin('ModGoods', 'ModGoods', array(
		"ModGoods.gid = modResource.id",
		"ModGoods.wid = ".$_SESSION['minishop']['warehouse']
	));
	$criteria->sortby($sortbyMS, $sortdir);
}
// eof add
$total = $modx->getCount('modResource', $criteria);
$modx->setPlaceholder($totalVar, $total);

$fields = array_keys($modx->getFields('modResource'));
if (empty($includeContent)) {
	$fields = array_diff($fields, array('content'));
}
$columns = $includeContent ? $modx->getSelectColumns('modResource', 'modResource') : $modx->getSelectColumns('modResource', 'modResource', '', array('content'), true);
$criteria->select($columns);
if (!empty($sortbyTV)) {
	$criteria->leftJoin('modTemplateVar', 'tvDefault', array(
		"tvDefault.name" => $sortbyTV
	));
	$criteria->leftJoin('modTemplateVarResource', 'tvSort', array(
		"tvSort.contentid = modResource.id",
		"tvSort.tmplvarid = tvDefault.id"
	));
	if (empty($sortbyTVType)) $sortbyTVType = 'string';
	if ($modx->getOption('dbtype') === 'mysql') {
		switch ($sortbyTVType) {
			case 'integer':
				$criteria->select("CAST(IFNULL(tvSort.value, tvDefault.default_text) AS SIGNED INTEGER) AS sortTV");
				break;
			case 'decimal':
				$criteria->select("CAST(IFNULL(tvSort.value, tvDefault.default_text) AS DECIMAL) AS sortTV");
				break;
			case 'datetime':
				$criteria->select("CAST(IFNULL(tvSort.value, tvDefault.default_text) AS DATETIME) AS sortTV");
				break;
			case 'string':
			default:
				$criteria->select("IFNULL(tvSort.value, tvDefault.default_text) AS sortTV");
				break;
		}
	} elseif ($modx->getOption('dbtype') === 'sqlsrv') {
		switch ($sortbyTVType) {
			case 'integer':
				$criteria->select("CAST(ISNULL(tvSort.value, tvDefault.default_text) AS BIGINT) AS sortTV");
				break;
			case 'decimal':
				$criteria->select("CAST(ISNULL(tvSort.value, tvDefault.default_text) AS DECIMAL) AS sortTV");
				break;
			case 'datetime':
				$criteria->select("CAST(ISNULL(tvSort.value, tvDefault.default_text) AS DATETIME) AS sortTV");
				break;
			case 'string':
			default:
				$criteria->select("ISNULL(tvSort.value, tvDefault.default_text) AS sortTV");
				break;
		}
	}
	$criteria->sortby("sortTV", $sortdirTV);
}
if (!empty($sortby)) {
	if (strpos($sortby, '{') === 0) {
		$sorts = $modx->fromJSON($sortby);
	} else {
		$sorts = array($sortby => $sortdir);
	}
	if (is_array($sorts)) {
		while (list($sort, $dir) = each($sorts)) {
			if ($sortbyEscaped) $sort = $modx->escape($sort);
			//if (!empty($sortbyAlias)) $sort = $modx->escape($sortbyAlias) . ".{$sort}"; // commented by bezumkin 01.04.2012
			if (!empty($sortbyAlias) && $sort != 'RAND()') $sort = $modx->escape($sortbyAlias) . ".{$sort}"; // added by bezumkin 01.04.2012
			$criteria->sortby($sort, $dir);
		}
	}
}
if (!empty($limit)) $criteria->limit($limit, $offset);

if (!empty($debug)) {
	$criteria->prepare();
	$modx->log(modX::LOG_LEVEL_ERROR, $criteria->toSQL());
}
$collection = $modx->getCollection('modResource', $criteria, $dbCacheFlag);

$idx = !empty($idx) && $idx !== '0' ? (integer) $idx : 1;
$first = empty($first) && $first !== '0' ? 1 : (integer) $first;
$last = empty($last) ? (count($collection) + $idx - 1) : (integer) $last;

/* include parseTpl */
include_once $modx->getOption('getresources.core_path',null,$modx->getOption('core_path').'components/getresources/').'include.parsetpl.php';

$templateVars = array();
if (!empty($includeTVs) && !empty($includeTVList)) {
	$templateVars = $modx->getCollection('modTemplateVar', array('name:IN' => $includeTVList));
}
/** @var modResource $resource */
foreach ($collection as $resourceId => $resource) {
	$tvs = array();
	if (!empty($includeTVs)) {
		if (empty($includeTVList)) {
			$templateVars = $resource->getMany('TemplateVars');
		}
		/** @var modTemplateVar $templateVar */
		foreach ($templateVars as $tvId => $templateVar) {
			if (!empty($includeTVList) && !in_array($templateVar->get('name'), $includeTVList)) continue;
			if ($processTVs && (empty($processTVList) || in_array($templateVar->get('name'), $processTVList))) {
				$tvs[$tvPrefix . $templateVar->get('name')] = $templateVar->renderOutput($resource->get('id'));
			} else {
				$value = $templateVar->getValue($resource->get('id'));
				if ($prepareTVs && method_exists($templateVar, 'prepareOutput') && (empty($prepareTVList) || in_array($templateVar->get('name'), $prepareTVList))) {
					$value = $templateVar->prepareOutput($value);
				}
				$tvs[$tvPrefix . $templateVar->get('name')] = $value;

			}
		}
	}

	$ms_properties = array();
	$gid = $resource->get('id');
	$wid = $_SESSION['minishop']['warehouse'];
	$sql = "SELECT `goods`.`wid`,`goods`.`gid`,`article`,`weight`,`img`,`remains`,`reserved`,`add1`,`add2`,`add3`,`file`
			FROM {$modx->getTableName('ModGoods')} `goods` LEFT JOIN {$modx->getTableName('ModGallery')} `gallery`
			ON `goods`.`wid` = `gallery`.`wid` AND `goods`.`gid` = `gallery`.`gid`
			WHERE `goods`.`gid` = $gid AND `goods`.`wid` = $wid
			ORDER BY `gallery`.`fileorder` ASC
			LIMIT 1";
	$q = $modx->prepare($sql);
	$q->execute();
	$ms_properties = $q->fetch(PDO::FETCH_ASSOC);

	if (!empty($ms_properties)) {
		$ms_properties['price'] = $modx->miniShop->getPrice($gid);
		$ms_properties['weight'] = $modx->miniShop->getWeight($gid);
		if (empty($ms_properties['img'])) {$ms_properties['img'] = $ms_properties['file'];}
		//unset($ms_properties['id']);
	}

	$odd = ($idx & 1);
	$properties = array_merge(
		$scriptProperties
		,array(
			'idx' => $idx
			,'first' => $first
			,'last' => $last
			,'odd' => $odd
		)
		,$resource->toArray()
		,$tvs
		,$ms_properties
	);
	
	$resourceTpl = '';
		if ($idx == $first && !empty($tplFirst)) {
		$resourceTpl = parseTpl($tplFirst, $properties);
	}
	if ($idx == $last && empty($resourceTpl) && !empty($tplLast)) {
		$resourceTpl = parseTpl($tplLast, $properties);
	}
	$tplidx = 'tpl_' . $idx;
	if (empty($resourceTpl) && !empty($$tplidx)) {
		$resourceTpl = parseTpl($$tplidx, $properties);
	}
	if ($idx > 1 && empty($resourceTpl)) {
		$divisors = getDivisors($idx);
		if (!empty($divisors)) {
			foreach ($divisors as $divisor) {
				$tplnth = 'tpl_n' . $divisor;
				if (!empty($$tplnth)) {
					$resourceTpl = parseTpl($$tplnth, $properties);
					if (!empty($resourceTpl)) {
						break;
					}
				}
			}
		}
	}
	if ($idx == $first && empty($resourceTpl) && !empty($tplFirst)) {
		$resourceTpl = parseTpl($tplFirst, $properties);
	}
	if ($idx == $last && empty($resourceTpl) && !empty($tplLast)) {
		$resourceTpl = parseTpl($tplLast, $properties);
	}
	if ($odd && empty($resourceTpl) && !empty($tplOdd)) {
		$resourceTpl = parseTpl($tplOdd, $properties);
	}
	if (!empty($tplCondition) && !empty($conditionalTpls) && empty($resourceTpl)) {
		$conTpls = $modx->fromJSON($conditionalTpls);
		$subject = $properties[$tplCondition];
		$tplOperator = !empty($tplOperator) ? $tplOperator : '=';
		$tplOperator = strtolower($tplOperator);
		$tplCon = '';
		foreach ($conTpls as $operand => $conditionalTpl) {
			switch ($tplOperator) {
				case '!=':
				case 'neq':
				case 'not':
				case 'isnot':
				case 'isnt':
				case 'unequal':
				case 'notequal':
					$tplCon = (($subject != $operand) ? $conditionalTpl : $tplCon);
					break;
				case '<':
				case 'lt':
				case 'less':
				case 'lessthan':
					$tplCon = (($subject < $operand) ? $conditionalTpl : $tplCon);
					break;
				case '>':
				case 'gt':
				case 'greater':
				case 'greaterthan':
					$tplCon = (($subject > $operand) ? $conditionalTpl : $tplCon);
					break;
				case '<=':
				case 'lte':
				case 'lessthanequals':
				case 'lessthanorequalto':
					$tplCon = (($subject <= $operand) ? $conditionalTpl : $tplCon);
					break;
				case '>=':
				case 'gte':
				case 'greaterthanequals':
				case 'greaterthanequalto':
					$tplCon = (($subject >= $operand) ? $conditionalTpl : $tplCon);
					break;
				case 'isempty':
				case 'empty':
					$tplCon = empty($subject) ? $conditionalTpl : $tplCon;
					break;
				case '!empty':
				case 'notempty':
				case 'isnotempty':
					$tplCon = !empty($subject) && $subject != '' ? $conditionalTpl : $tplCon;
					break;
				case 'isnull':
				case 'null':
					$tplCon = $subject == null || strtolower($subject) == 'null' ? $conditionalTpl : $tplCon;
					break;
				case 'inarray':
				case 'in_array':
				case 'ia':
					$operand = explode(',', $operand);
					$tplCon = in_array($subject, $operand) ? $conditionalTpl : $tplCon;
					break;
				case '==':
				case '=':
				case 'eq':
				case 'is':
				case 'equal':
				case 'equals':
				case 'equalto':
				default:
					$tplCon = (($subject == $operand) ? $conditionalTpl : $tplCon);
					break;
			}
		}
		if (!empty($tplCon)) {
			$resourceTpl = parseTpl($tplCon, $properties);
		}
	}
	if (!empty($tpl) && empty($resourceTpl)) {
		if (!$cacheChunks) {
			$resourceTpl = parseTpl($tpl, $properties);
		}
		else {
			$key = 'msgr/'.md5($tpl.json_encode($properties));
			if (!$resourceTpl = $modx->cacheManager->get($key)) {
				$resourceTpl = parseTpl($tpl, $properties);
				$modx->cacheManager->set($key, $resourceTpl, $cacheTime);
			}
		}
	}
	if (empty($resourceTpl)) {
		$chunk = $modx->newObject('modChunk');
		$chunk->setCacheable(false);
		$output[]= $chunk->process(array(), '<pre>' . print_r($properties, true) .'</pre>');
	} else {
		$output[]= $resourceTpl;
	}
	$idx++;
}

/* output */
$toSeparatePlaceholders = $modx->getOption('toSeparatePlaceholders',$scriptProperties,false);
if (!empty($toSeparatePlaceholders)) {
	$modx->setPlaceholders($output,$toSeparatePlaceholders);
	return '';
}

$output = implode($outputSeparator, $output);
$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,false);
if (!empty($toPlaceholder)) {
	$modx->setPlaceholder($toPlaceholder,$output);
	return '';
}
return $output;