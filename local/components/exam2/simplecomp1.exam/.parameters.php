<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
    "PARAMETERS" => array(
        "IBLOCK_PRODUCTS" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("IBLOCK_PRODUCTS"),
            "TYPE" => "string",
            "DEFAULT" => "",
        ),
        "IBLOCK_NEWS" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("IBLOCK_NEWS"),
            "TYPE" => "string",
            "DEFAULT" => "",
        ),
        "UF_NEWS_LINK" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("UF_NEWS_LINK"),
            "TYPE" => "string",
            "DEFAULT" => "",
        ),
        "CACHE_TIME"  =>  Array("DEFAULT"=>36000000)
    ),
);
