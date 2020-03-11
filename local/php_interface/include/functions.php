<?php

function setMeta() {
    if (!CModule::IncludeModule("iblock")) {
        return;
    }
    global $APPLICATION;
    $arFilter = array("NAME" => $APPLICATION->GetCurPage(), "IBLOCK_ID" => IBLOCK_META);
    $arSelect = array("IBLOCK_ID", "ID", "PROPERTY_META_TITLE", "PROPERTY_META_DESCRIPTION");
    $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
    if ($el = $res->Fetch()) {
        $APPLICATION->SetPageProperty('title', $el["PROPERTY_META_TITLE_VALUE"]);
        $APPLICATION->SetPageProperty('description', $el["PROPERTY_META_DESCRIPTION_VALUE"]);
    }
}

setMeta();