<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (isset($arResult["CANONICAL"])) {
    $APPLICATION->SetPageProperty('canonical', $arResult["CANONICAL"]);
}

if ($_REQUEST["TYPE"] === "REPORT_RESULT") {
    if ($_REQUEST["ID"]) {
        echo '<script>
            var complaintResult = document.getElementById("complaint_result");
            complaintResult.innerText = "Ваше мнение учтено, №' . $_REQUEST["ID"] . '";
            window.history.pushState(null, null, "' . $APPLICATION->GetCurPage() . '");
        </script>';
    } else {
        echo '<script>
            var complaintResult = document.getElementById("complaint_result");
            complaintResult.innerText = "Ошибка!";
            window.history.pushState(null, null, "' . $APPLICATION->GetCurPage() . '");
        </script>';
    }
} else if ($_REQUEST["ID"] && CModule::IncludeModule('iblock')) {
    $author = "Не авторизован";
    global $USER;
    if ($USER->IsAuthorized()) {
        $author = $USER->GetID() . ", " . $USER->GetLogin() . ", " . $USER->GetFullName();
    }

    $el = new CIBlockElement;
    $arLoadProductArray = Array(
        "IBLOCK_ID" => IBLOCK_COMPLAINT,
        "NAME" => "Жалоба на новость " . $_REQUEST["ID"],
        "ACTIVE_FROM"    => \Bitrix\Main\Type\DateTime::createFromTimestamp(time()),
        "PROPERTY_VALUES" => array(
            "USER" => $author,
            "NEWS" => $_REQUEST["ID"]
        )
    );

    if ($complaintId = $el->Add($arLoadProductArray)) {
        if ($_REQUEST["TYPE"] === "REPORT_AJAX") {
            $APPLICATION->RestartBuffer();
            echo json_encode($complaintId);
            die();
        } else if ($_REQUEST["TYPE"] === "REPORT_GET") {
            localRedirect($APPLICATION->GetCurPage() . "?TYPE=REPORT_RESULT&ID=". $complaintId);
        }
    } else {
        localRedirect($APPLICATION->GetCurPage() . "?TYPE=REPORT_RESULT");
    }
}
