<?php

function CheckUserCount() {
    $lastDate = COption::GetOptionString("main", "lastDateSendNewUsers");
    $lastDateTimestamp = strtotime($lastDate);
    $dateObj = \Bitrix\Main\Type\DateTime::createFromTimestamp(time());
    $currentDate = $dateObj->toString();

    $arUsers = CUser::GetList(
        $by = "date_register",
        $order = "desc"
    );
    $usersToCount = [];
    while ($user = $arUsers -> Fetch()) {
        if ($lastDate && $lastDateTimestamp < strtotime($user["DATE_REGISTER"])) {
            $usersToCount[] = $user;
        }
        if (!$lastDate) {
            $usersToCount[] = $user;
        }
    }
    if (!$lastDate) {
        $lastDate = $usersToCount[0]["DATE_REGISTER"];
    }

    $daysDiff = intval(abs(strtotime($lastDate) - strtotime($currentDate)));
    $daysDiff = round($daysDiff / (3600 * 24));
    $newUsersCount = count($usersToCount);

    $arFilter = array("GROUPS_ID" => GROUP_ADMIN, "ACTIVE" => "Y");
    $arUsers = CUser::GetList(
        $by = "id",
        $order = "desc",
        $arFilter
    );
    while ($admin = $arUsers -> Fetch()) {
        CEvent::Send(
            "COUNT_NEW_USERS",
            "s1",
            array(
                "COUNT" => $newUsersCount,
                "DAYS" => $daysDiff,
                "EMAIL_TO" => $admin["EMAIL"]
            ),
            "N",
            30
        );
    }
    COption::SetOptionString("main", "lastDateSendNewUsers", $currentDate);

    return "CheckUserCount();";
}

