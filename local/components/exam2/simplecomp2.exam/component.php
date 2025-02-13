<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (!isset($arParams["CACHE_TIME"])) {
    $arParams["CACHE_TIME"] = 36000000;
}

if ($USER->IsAuthorized() && CModule::includeModule("iblock")) {
    $arButtons = CIBlock::GetPanelButtons($arParams["IBLOCK_PRODUCTS"]);
    $this->AddIncludeAreaIcon(
        array(
            "TITLE" => "ИБ в админке",
            "URL" => $arButtons['submenu']['element_list']['ACTION_URL'],
            "IN_PARAMS_MENU" => true,
        )
    );
}

$arNavParams = array(
    "nPageSize" => $arParams["PAGE_ELEMENT_COUNT"],
    "bShowAll" => false,
);
$arNavigation = CDBResult::GetNavParams($arNavParams);

if ($this->StartResultCache(false, array($USER->GetGroups(), $arNavigation))) {
    if(!CModule::IncludeModule("iblock"))
    {
        $this->AbortResultCache();
        ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
        return;
    }

    // выбираем элементы классификатора
    $arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_CLASSIFIER"], "CHECK_PERMISSIONS" => "Y", "ACTIVE" => "Y");
    $arSelect = array("IBLOCK_ID", "ID", "NAME");
    $res = CIBlockElement::GetList(array("SORT"=>"ASC"), $arFilter, false, $arNavParams, $arSelect);
    $arCompany = [];
    $arCompanyIds = [];
    $arResult["NAV_STRING"] = $res->GetPageNavString("Странички");
    while ($el = $res-> Fetch()) {
        $arCompany[$el["ID"]] = $el;
        $arCompanyIds[] = $el["ID"];
    }

    // выбираем элементы каталога
    $arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_PRODUCTS"], "CHECK_PERMISSIONS" => "Y", "ACTIVE" => "Y", "PROPERTY_" . $arParams["ELEMENT_PROP_CODE"] => $arCompanyIds);
    $arSelect = array("IBLOCK_ID", "ID", "NAME", "PROPERTY_PRICE", "PROPERTY_MATERIAL", "PROPERTY_ARTNUMBER", "IBLOCK_SECTION_ID", "CODE");
    $arSort = array("NAME" => "ASC", "SORT" => "ASC");
    $res = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
    $arProduct = [];
    $arProductIds[] = [];
    while ($obElement = $res-> GetNextElement()) {
        $el = $obElement->GetFields();
        $elProp = $obElement->GetProperties();
        foreach ($elProp["COMPANY"]["VALUE"] as $companyID) {
            $detailUrl = $arParams["TEMPLATE_DETAIL_URL"];
            $detailUrl = str_replace("#SECTION_ID#", $el["IBLOCK_SECTION_ID"], $detailUrl);
            $detailUrl = str_replace("#ELEMENT_CODE#", $el["CODE"], $detailUrl);

            $arCompany[$companyID]["PRODUCTS"][$el["ID"]] = $el;
            $arCompany[$companyID]["PRODUCTS"][$el["ID"]]["DETAIL_URL"] = "/" . $detailUrl . ".php";
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
