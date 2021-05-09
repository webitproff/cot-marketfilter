<?php

defined('COT_CODE') or die('Wrong URL');

include_once cot_langfile("marketfilter", "plug", "en");

function cot_marketfilter_maxprice(){
    global $db_market, $db;
    
    $inf = $db->query(" SELECT MAX(item_cost) as cost FROM `{$db_market}` ")->fetch();
    
    return number_format($inf["cost"], 0, "", "");
}

function cot_marketfilter_tree($parent = '', $selected = '', $level = 0, $template = '') {
    global $structure, $cfg, $db, $sys;
    global $i18n_notmain, $i18n_locale, $i18n_write, $i18n_admin, $i18n_read, $db_i18n_pages;

    $urlparams = array();

    /* === Hook === */
    foreach (cot_getextplugins('marketfilter.tree.first') as $pl) {
        include $pl;
    }
    /* ===== */

    if (empty($parent)) {
        $i18n_enabled = $i18n_read;
        $children = array();
        foreach ($structure['market'] as $i => $x) {
            if (mb_substr_count($structure['market'][$i]['path'], ".") == 0) {
                $children[] = $i;
            }
        }
    } else {
        $i18n_enabled = $i18n_read && cot_i18n_enabled($parent);
        $children = $structure['market'][$parent]['subcats'];
    }

    $t1 = new XTemplate(cot_tplfile(array('marketfilter', 'tree', $template), 'plug'));

    /* === Hook === */
    foreach (cot_getextplugins('marketfilter.tree.main') as $pl) {
        include $pl;
    }
    /* ===== */

    if (count($children) == 0) {
        return false;
    }

    $t1->assign(array(
        "TITLE" => htmlspecialchars($structure['market'][$parent]['title']),
        "DESC" => $structure['market'][$parent]['desc'],
        "COUNT" => $structure['market'][$parent]['count'],
        "ICON" => $structure['market'][$parent]['icon'],
        "HREF" => cot_url("marketfilter", $urlparams + array('c' => $parent)),
        "LEVEL" => $level,
    ));

    $jj = 0;

    /* === Hook - Part1 : Set === */
    $extp = cot_getextplugins('marketfilter.tree.loop');
    /* ===== */

    foreach ($children as $row) {
        $jj++;
        $urlparams['c'] = $row;
        $subcats = $structure['market'][$row]['subcats'];
        $t1->assign(array(
            "ROW_TITLE" => htmlspecialchars($structure['market'][$row]['title']),
            "ROW_DESC" => $structure['market'][$row]['desc'],
            "ROW_COUNT" => $structure['market'][$row]['count'],
            "ROW_ICON" => $structure['market'][$row]['icon'],
            "ROW_HREF" => cot_url("marketfilter", $urlparams),
            "ROW_KEY" => $urlparams['c'],
            "ROW_SELECTED" => $selected[$urlparams['c']] ? "checked" : "",
            "ROW_SUBCAT" => (count($subcats) > 0) ? cot_marketfilter_tree($row, $selected, $level + 1) : '',
            "ROW_LEVEL" => $level,
            "ROW_ODDEVEN" => cot_build_oddeven($jj),
            "ROW_JJ" => $jj
        ));

        if ($i18n_enabled && $i18n_notmain) {
            $x_i18n = cot_i18n_get_cat($row, $i18n_locale);
            if ($x_i18n) {
                if (!$cfg['plugin']['i18n']['omitmain'] || $i18n_locale != $cfg['defaultlang']) {
                    $urlparams['l'] = $i18n_locale;
                }
                $t1->assign(array(
                    'ROW_URL' => cot_url('marketfilter', $urlparams),
                    'ROW_TITLE' => $x_i18n['title'],
                    'ROW_DESC' => $x_i18n['desc'],
                ));
            }
        }

        /* === Hook - Part2 : Include === */
        foreach ($extp as $pl) {
            include $pl;
        }
        /* ===== */

        $t1->parse("MAIN.CATS");
    }
    if ($jj == 0) {
        return false;
    }

    $t1->parse("MAIN");
    return $t1->text("MAIN");
}
