<?php
use \Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);

AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", array("ExamClass", "OnBeforeIBlockElementUpdateHandler"));
AddEventHandler("main", "OnProlog", array("ExamClass", "Error404Handler"));
AddEventHandler("main", "OnBeforeEventAdd", Array("ExamClass", "OnBeforeEventAddHandler"));

class ExamClass
{
    function OnBeforeIBlockElementUpdateHandler(&$arFields)
    {
        if ($arFields["IBLOCK_ID"] === IBLOCK_PRODUCT && $arFields["ACTIVE"] !== "Y" && CModule::IncludeModule("iblock")) {
            $info = [];
            $res = CIBlockElement::GetByID($arFields["ID"]);
            if ($r = $res->Fetch()) {
                $info["SHOW_COUNTER"] = $r["SHOW_COUNTER"];
            }

            if ($info["SHOW_COUNTER"] > 2) {
                global $APPLICATION;
                $APPLICATION->throwException(Loc::GetMessage("ALERT_PRE_COUNT") . $info["SHOW_COUNTER"] . Loc::GetMessage("ALERT_POST_COUNT"));
                return false;
            }
        }

        return true;
    }

    function Error404Handler()
    {
        if (defined('ERROR_404') && ERROR_404 === "Y") {
            global $APPLICATION;
            $APPLICATION->RestartBuffer();

            include($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/header.php");
            include($_SERVER["DOCUMENT_ROOT"] . "404.php");
            include($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/footer.php");

            CEventLog::Add(array(
                "SEVERITY" => "INFO",
                "AUDIT_TYPE_ID" => "ERROR_404",
                "MODULE_ID" => "main",
                "DESCRIPTION" => $APPLICATION->GetCurPageParam("", array(), true),
            ));
        }
    }

    function OnBeforeEventAddHandler(&$event, &$lid, &$arFields)
    {
        if ($event === "FEEDBACK_FORM") {
            global $USER;
            if (!$USER->IsAuthorized()) {
                $arFields["AUTHOR"] = Loc::GetMessage("USER_NOT_AUTHORIZED") . $arFields["AUTHOR"];
            } else {
                $arFields["AUTHOR"] = Loc::GetMessage("USER_AUTHORIZED_START") . $USER->GetID() . " (" . $USER->GetLogin() . ") "
                . $USER->GetFullName() . ", " . Loc::GetMessage("USER_AUTHORIZED_END") . $arFields["AUTHOR"];
            }

            CEventLog::Add(array(
                "SEVERITY" => "SECURITY",
                "AUDIT_TYPE_ID" => Loc::GetMessage("MAIL_DATA_CHANGED"),
                "MODULE_ID" => "main",
                "ITEM_ID" => $event,
                "DESCRIPTION" => Loc::GetMessage("MAIL_DATA_CHANGED") . " – " . $arFields["AUTHOR"],
            ));
        }
    }
}