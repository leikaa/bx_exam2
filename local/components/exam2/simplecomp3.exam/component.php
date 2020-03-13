<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

global $USER;
if (!$USER->IsAuthorized()) {
    return;
}

if (!isset($arParams["CACHE_TIME"])) {
    $arParams["CACHE_TIME"] = 36000000;
}

$arResult["NEWS_COUNT"] = 0;
$currentUserType = "";
$currentUserId = $USER->GetId();
$arFilter = array("ID" => $currentUserId);
$arSelect = array("SELECT" => array($arParams["UF_AUTHOR_TYPE"]));
$res = CUser::GetList(
    $by = "id",
    $order = "asc",
    $arFilter,
    $arSelect
);
if ($arUser = $res->Fetch()) {
    $currentUserType = $arUser[$arParams["UF_AUTHOR_TYPE"]];
}

if ($this->StartResultCache(false, ($currentUserId)) && $currentUserType) {
    if (!CModule::IncludeModule("iblock")) {
        $this->AbortResultCache();
        ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
        return;
    }

    // Получаем всех пользователей такого же типа, как у текущего
    $arFilter = array(
        $arParams["UF_AUTHOR_TYPE"] => $currentUserType,
        "ACTIVE" => "Y"
    );
    $arSelect = array("SELECT" => array("LOGIN", "ID"));
    $arUserType = CUser::GetList(
        $by = "id",
        $order = "asc",
        $arFilter,
        $arSelect
    );
    $usersList = [];
    $usersListIds = [];
    while ($arUser = $arUserType->Fetch()) {
        $usersListIds[] = $arUser['ID'];
        $usersList[$arUser['ID']]['LOGIN'] = $arUser['LOGIN'];
    }
    // Получаем все новости, принадлежащие пользователям такого же типа, что и у текущего
    $arFilter = array("IBLOCK_ID" => $arParams["NEWS_IBLOCK_ID"], "ACTIVE" => "Y", "PROPERTY_" . $arParams["PROPERTY_AUTHOR"] => $usersListIds);
    $arSelect = array("IBLOCK_ID", "ID", "NAME", "PROPERTY_" . $arParams["PROPERTY_AUTHOR"]);
    $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
    // Сортируем новости по авторам
    $newsToHideList = [];
    while ($obElement = $res->GetNextElement(true, false)) {
        $el = $obElement->GetFields();

        $usersList[$el["PROPERTY_AUTHOR_VALUE"]]["NEWS"][$el["ID"]] = $el["NAME"];
        if ($el["PROPERTY_AUTHOR_VALUE"] === intval($currentUserId)) {
            $newsToHideList[] = $el["ID"];
        }
    }

    // Удаляем новости, как у текущего автора и самого автора
    $uniqueNewsIds = [];
    foreach ($usersList as $authorId => &$author) {
            if ($authorId === intval($currentUserId)) {
                unset($usersList[$authorId]);
            }
        foreach ($author["NEWS"] as $newsId => $news) {
            if (in_array($newsId, $newsToHideList)) {
                unset($author["NEWS"][$newsId]);
            } else {
                $uniqueNewsIds[$newsId] = $newsId;
            }
        }
    }

    $arResult["NEWS_COUNT"] = count($uniqueNewsIds);
    $arResult["AUTHOR"] = $usersList;

    $this->SetResultCacheKeys(array("NEWS_COUNT"));
    $this->IncludeComponentTemplate();
} else {
    $this->AbortResultCache();
}

$APPLICATION->SetTitle(GetMessage("NEWS_COUNT") . $arResult["NEWS_COUNT"]);