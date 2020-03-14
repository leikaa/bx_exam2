<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arProductPrices = [];
foreach ($arResult["NEWS"] as $news) {
    foreach ($news["PRODUCTS"] as $product) {
        $arProductPrices[] = $product["PROPERTY_PRICE_VALUE"];
    }
}

if (!empty($arProductPrices)) {
    $arResult["MIN_PRICE"] = min($arProductPrices);
    $arResult["MAX_PRICE"] = max($arProductPrices);
    $this->__component->SetResultCacheKeys(array("MIN_PRICE", "MAX_PRICE"));
}
