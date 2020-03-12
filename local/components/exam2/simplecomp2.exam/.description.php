<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => GetMessage("SIMPLECOMP_NAME"),
    "DESCRIPTION" => GetMessage("SIMPLECOMP_DESC"),
    "CACHE_PATH" => "Y",
    "SORT" => 2,
    "PATH" => array(
        "ID" => "simplecomp1.exam",
        "NAME" => GetMessage("SIMPLECOMP_PATH_NAME"),
        "SORT" => 2,
    ),
);
