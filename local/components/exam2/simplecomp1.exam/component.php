<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (!isset($arParams["CACHE_TIME"])) {
    $arParams["CACHE_TIME"] = 36000000;
}

$cacheId = md5(serialize($arParams));
$cacheDir = "/simpleCompTagged";

if ($this->StartResultCache(false, array($_REQUEST["F"], $cacheId), $cacheDir)) {
    if (!CModule::IncludeModule("iblock")) {
        $this->AbortResultCache();
        ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
        return;
    }

    if ($APPLICATION->GetShowIncludeAreas()) {
        $arButtons = CIBlock::GetPanelButtons(
            $arParams["IBLOCK_NEWS"],
            0
        );
        $this->addIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arButtons));
    }

    global $CACHE_MANAGER;
    $CACHE_MANAGER->StartTagCache($cacheDir);
    // получаем новости
    $arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_NEWS"], "ACTIVE" => "Y");
    $arSelect = array("IBLOCK_ID", "ID", "NAME", "DATE_ACTIVE_FROM");
    $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
    $arNews = [];
    $arNewsId = [];
    while ($el = $res->Fetch()) {
        $arButtons = CIBlock::GetPanelButtons(
            $el["IBLOCK_ID"],
            $el["ID"],
            0,
            array("SECTION_BUTTONS"=>false, "SESSID"=>false)
        );
        $arItem["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
        $arItem["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];
        $el["ITEMS"] = $arItem;

        $CACHE_MANAGER->RegisterTag("simle_comp_cache_tag");

        $arNews[$el["ID"]] = $el;
        $arNewsId[] = $el["ID"];
    }
    $CACHE_MANAGER->EndTagCache();

    // получаем разделы продукции
    $arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_PRODUCTS"], "ACTIVE" => "Y", $arParams["UF_NEWS_LINK"] => $arNewsId);
    $arSelect = array("IBLOCK_ID", "ID", "NAME", $arParams["UF_NEWS_LINK"]);
    $res = CIBlockSection::GetList(array(), $arFilter, false, $arSelect, false);
    $arProdSections = [];
    $arProdSectionIds = [];
    while ($s = $res->Fetch()) {
        $arProdSections[$s["ID"]] = $s;
        foreach ($arProdSections[$s["ID"]]["UF_NEWS_LINK"] as $newsId) {
            $arNews[$newsId]["SECTIONS"][] = $s["NAME"];
        }
        $arProdSectionIds[] = $s["ID"];
    }

    // получаем продукцию
    $arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_PRODUCTS"], "ACTIVE" => "Y", "SECTION_ID" => $arProdSectionIds);
    $arSelect = array("IBLOCK_ID", "ID", "NAME", "IBLOCK_SECTION_ID", "PROPERTY_MATERIAL", "PROPERTY_ARTNUMBER", "PROPERTY_PRICE");
    if ($_REQUEST["F"]) {
        $arFilter[] = array(
            array("<=PROPERTY_PRICE" => "1700", "PROPERTY_MATERIAL" => "Дерево, ткань"),
            array("<PROPERTY_PRICE" => "1500", "PROPERTY_MATERIAL" => "Металл, пластик"),
            "LOGIC" => "OR"
        );
        $this->abortResultCache();
    }
    $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
    $productsCount = 0;
    while ($el = $res->Fetch()) {
        foreach ($arProdSections[$el["IBLOCK_SECTION_ID"]]["UF_NEWS_LINK"] as $newsId) {
            $arNews[$newsId]["PRODUCTS"][] = $el;
        }
        $productsCount++;
    }

    $arResult["PROD_COUNT"] = $productsCount;
    $arResult["NEWS"] = $arNews;
    $this->SetResultCacheKeys(array("PROD_COUNT"));
    $this->IncludeComponentTemplate();
} else {
    $this->AbortResultCache();
}

$APPLICATION->SetTitle(GetMessage("PROD_COUNT") . $arResult["PROD_COUNT"]);
