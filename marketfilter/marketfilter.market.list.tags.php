<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=market.list.tags
 * [END_COT_EXT]
 */
defined('COT_CODE') or die('Wrong URL');
/* ====================
  Copyright (c) 2010-2015, Kolev Andrew, alteraweb.ru, Pluginspro.ru.
  All rights reserved.
  ==================== */
include_once cot_incfile("marketfilter", "plug");

if (!$l = licensed("marketfilter", $cfg["plugin"]["marketfilter"]["license"])) {
    $t1 = new XTemplate(cot_tplfile(array('marketfilter', $structure['market'][$c]['tpl']), "plug"));

    $rcats = cot_import("rcats", "P", "ARR");
    $price1 = cot_import("price1", "P", "TXT");
    $price2 = cot_import("price2", "P", "TXT");

    $structure['market'] = (is_array($structure['market'])) ? $structure['market'] : array();

    $t1->assign(array(
        "SEARCH_ACTION_URL" => cot_url('market', cot_xg(), '', true),
        "SEARCH_SORTER" => cot_selectbox($sort, "sort", array('', 'costasc', 'costdesc'), array($L['market_mostrelevant'], $L['market_costasc'], $L['market_costdesc']), false),
        "SEARCH_SQ" => cot_inputbox('text', 'sq', htmlspecialchars($sq), 'class="schstring"'),
        "SEARCH_CAT" => cot_marketfilter_tree('', $rcats, 0),
        "SEARCH_SUBCAT" => cot_marketfilter_tree($c, $rcats, 0),
        "SEARCH_PRICE1" => cot_inputbox('text', 'price1', $price1 ? $price1 : 0),
        "SEARCH_PRICE2" => cot_inputbox('text', 'price2', $price2 ? $price2 : cot_marketfilter_maxprice()),
        "SEARCH_PRICE_VALUES1" => $price1 ? $price1 : 0,
        "SEARCH_PRICE_VALUES2" => $price2 ? $price2 : cot_marketfilter_maxprice(),
        "SEARCH_PRICE_MAX" => cot_marketfilter_maxprice(),
        "SEARCH_SAVE" => cot_inputbox('hidden', 'saveFilter', 1),
    ));

    $t1->parse("MAIN");

    $t->assign(array(
        "PRD_FILTER" => $t1->text("MAIN")
    ));
} else {
    $t->assign(array(
        "PRD_FILTER" => cot_licens_message($l["status"])
    ));
}