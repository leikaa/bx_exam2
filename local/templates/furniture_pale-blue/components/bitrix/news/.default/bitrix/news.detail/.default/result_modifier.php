<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (!empty($arParams["SET_CANONICAL"])) {
    $arFilter = array(
        "IBLOCK_ID" => $arParams["SET_CANONICAL"],
        "PROPERTY_NEWS" => $arResult["ID"]
    );
    $arSelect = array("IBLOCK_ID", "ID", "NAME");
    $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
    if ($r = $res->Fetch()) {
        $arResult["CANONICAL"] = $r["NAME"];
    }

    $this->__component->SetResultCacheKeys(array('CANONICAL'));
}