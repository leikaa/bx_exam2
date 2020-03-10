<?php
use \Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);

AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", array("ExamClass", "OnBeforeIBlockElementUpdateHandler"));

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
}