<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

    if (!CModule::IncludeModule("iblock"))
    {
        ShowError("Модуль iblock не установлен");
        return false;
    }

    if (!$arParams["IBLOCK_ID"])
    {
        ShowError("Инфоблок не определен");
        return false;
    }

    //текущая страница
    $page_url = $APPLICATION->GetCurPage();

//проверка групп пользователей
    $currentUserGroups = $USER->GetUserGroup();
    if (count(array_intersect($currentUserGroups, $arParams["READ_USER_GROUPS"])) < 1)
    {
        ShowError("Нет прав для просмотра комментариев");
        return false;
    }

    //получение списка комментриев для страницы
    $dbComments = CIBlockelement::GetList(
        array(
            "ID"    =>  "DESC"
        ),
        array(
            "ACTIVE"                                =>  "Y",
            "PROPERTY_".$arParams["PAGE_PROPERTY"]  =>  $page_url,
        ),
        false,
        false,
        array(
            "ID",
            "NAME",
            "DETAIL_TEXT",
            "TIMESTAMP_X",
            "PROPERTY_".$arParams["PAGE_PROPERTY"]
        )
    );
    while ($arComments = $dbComments->GetNext())
    {
        $arResult["ITEMS"][] = $arComments;
    }

//добавление нового комментария
//проверка прав на добалвение комментариев
    if (count(array_intersect($currentUserGroups, $arParams["WRITE_USER_GROUPS"])) > 0 || $USER->IsAdmin())
    {
        //добаление нового элемента
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["comment"]))
        {

        }

        //выставляем флаг для отображения формы добавления комментария
        $arResult["CAN_WRITE"] = "Y";
    }

    $this->IncludeComponentTemplate();
?>