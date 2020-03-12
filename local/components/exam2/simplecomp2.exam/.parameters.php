<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
    "PARAMETERS" => array(
        "IBLOCK_PRODUCTS" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("IBLOCK_PRODUCTS"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ),
        "IBLOCK_CLASSIFIER" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("IBLOCK_CLASSIFIER"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ),
        "ELEMENT_PROP_CODE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("ELEMENT_PROP_CODE"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ),
        "TEMPLATE_DETAIL_URL" => array(
            "PARENT" => "URL_TEMPLATES",
            "NAME" => GetMessage("TEMPLATE_DETAIL_URL"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ),
        "CACHE_TIME"  =>  Array("DEFAULT"=>36000000),
    ),
);
