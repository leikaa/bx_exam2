<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (isset($arResult["MIN_PRICE"]) && isset($arResult["MAX_PRICE"])) {
    $textContainer = "<div style='color:red; margin: 34px 15px 35px 15px'>#text#</div>";
    $pricesMessage = GetMessage("MIN_PRICE") . $arResult["MIN_PRICE"] . ", " . GetMessage("MAX_PRICE") . $arResult["MAX_PRICE"];
    $infoMessage = str_replace("#text#", $pricesMessage, $textContainer);

    $APPLICATION->AddViewContent('min_max_prices', $infoMessage);
}
