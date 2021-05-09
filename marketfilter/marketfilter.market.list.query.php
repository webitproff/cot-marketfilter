<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=market.list.query
 * [END_COT_EXT]
 */
defined('COT_CODE') or die('Wrong URL');
/* ====================
  Copyright (c) 2010-2015, Kolev Andrew, alteraweb.ru, Pluginspro.ru.
  All rights reserved.
  ==================== */

/* ========== Сортирока ========== */
if (!empty($_POST["saveFilter"]) && !empty($_POST["sort"])) {
    setcookie("sort", $_POST["sort"]);
} elseif (!empty($_POST["saveFilter"]) && empty($_POST["sort"])) {
    setcookie("sort", 0);
}
if (!empty($_COOKIE["sort"]) && empty($_POST["saveFilter"])) {
    $_POST["sort"] = $_COOKIE["sort"];
}
/* ========== /Сортирока ========== */

/* ========== Категории ========== */
    if (!empty($_POST["saveFilter"]) && !empty($_POST["rcats"])) {
        setcookie("rcats", json_encode($_POST["rcats"]));
    } elseif (!empty($_POST["saveFilter"]) && empty($_POST["rcats"])) {
        setcookie("rcats", 0);
    }
    if (!empty($_COOKIE["rcats"]) && empty($_POST["saveFilter"])) {
        $_POST["rcats"] = json_decode($_COOKIE["rcats"], 1);
    }
/* ========== /Категории ========== */


$sort = cot_import('sort', 'P', 'ALP');
$rcats = cot_import("rcats", "P", "ARR");
$price1 = cot_import("price1", "P", "TXT");
$price2 = cot_import("price2", "P", "TXT");


if (!empty($rcats)) {
//    $catsub = cot_structure_children('market', $c);
//    $where['cat'] = "item_cat IN ('" . implode("','", $catsub) . "')";
//    

    foreach ($rcats as $k => $v) {
        $catsub = cot_structure_children('market', $k);
        $where['cat'] .= " || item_cat IN ('" . implode("','", $catsub) . "')";
    }
    $where['cat'] = "(" . substr($where['cat'], 0) . ")";
    $where['cat'] = str_replace("( || ", "(", $where['cat']);
}
if (!empty($price1)) {
    $where['price'] = "item_cost BETWEEN '{$price1}' AND '{$price2}'";
}

$order = array();
switch ($sort) {
    case 'costasc':
        $order['cost'] = 'item_cost ASC';
        break;

    case 'costdesc':
        $order['cost'] = 'item_cost DESC';
        break;

    default:
        $order['date'] = 'item_date DESC';
        break;
}