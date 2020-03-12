<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (!isset($arParams["CACHE_TIME"])) {
    $arParams["CACHE_TIME"] = 36000000;
}

if ($this->StartResultCache(false, ($USER->GetGroups()))) {
    if(!CModule::IncludeModule("iblock"))
    {
        $this->AbortResultCache();
        ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
        return;
    }

    // выбираем элементы классификатора
    $arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_CLASSIFIER"], "CHECK_PERMISSIONS" => "Y", "ACTIVE" => "Y");
    $arSelect = array("IBLOCK_ID", "ID", "NAME");
    $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
    $arCompany = [];
    $arCompanyIds = [];
    while ($el = $res-> Fetch()) {
        $arCompany[$el["ID"]] = $el;
        $arCompanyIds[] = $el["ID"];
    }

    // выбираем элементы каталога
    $arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_PRODUCTS"], "CHECK_PERMISSIONS" => "Y", "ACTIVE" => "Y", "PROPERTY_" . $arParams["ELEMENT_PROP_CODE"] => $arCompanyIds);
    $arSelect = array("IBLOCK_ID", "ID", "NAME", "PROPERTY_PRICE", "PROPERTY_MATERIAL", "PROPERTY_ARTNUMBER");
    $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
    $arProduct = [];
    $arProductIds[] = [];
    while ($obElement = $res-> GetNextElement()) {
        $el = $obElement->GetFields();
        $elProp = $obElement->GetProperties();
        foreach ($elProp["COMPANY"]["VALUE"] as $companyID) {
            $arCompany[$companyID]["PRODUCTS"][] = $el;
        }
    }

    $arResult["COUNT"] = count($arCompanyIds);
    $arResult["COMPANY"] = $arCompany;

    $this->SetResultCacheKeys(array('COUNT'));
    $this->IncludeComponentTemplate();
} else {
    $this->AbortResultCache();
}

$APPLICATION->SetTitle(GetMessage("COUNT") . $arResult["COUNT"]);
