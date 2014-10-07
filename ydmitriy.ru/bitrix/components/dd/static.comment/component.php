<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

    if (!CModule::IncludeModule("iblock"))
    {
        ShowError(GetMessage("IBLOCK_ERROR"));
        return false;
    }

    if (!$arParams["IBLOCK_ID"])
    {
        ShowError(GetMessage("IBLOCK_ID_ERROR"));
        return false;
    }

    //текущая страница
    $page_url = $APPLICATION->GetCurPage();
    //права текущего пользователя
    $currentUserGroups = $USER->GetUserGroup();

//добавление нового комментария
//проверка прав на добалвение комментариев
if (count(array_intersect($currentUserGroups, $arParams["WRITE_USER_GROUPS"])) > 0 || $USER->IsAdmin())
{
    //добаление нового элемента
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["comment"]))
    {
        $comment = new CIBlockElement();
        $properties = array(
            $arParams["PAGE_PROPERTY"] => $page_url
        );
        $arLoadArray = Array(
            "MODIFIED_BY"       => $USER->GetID(),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"         => $arParams["IBLOCK_ID"],
            "PROPERTY_VALUES"   => $properties,
            "NAME"              => $_POST["name"],
            "ACTIVE"            => ($arParams["MODERATION"] == "Y") ? "N":"Y",
            "DETAIL_TEXT"       => $_POST["comment"],
        );
        if ($ID = $comment->Add($arLoadArray))
        {
            if ($arParams["MODERATION"] == "Y")
                ShowNote(GetMessage("MODERATION_NOTE"));
            else
                ShowNote(GetMessage("OK_NOTE"));
        } else {
            ShowMessage(GetMessage("ERROR_NOTE"));
        }
    }

    //выставляем флаг для отображения формы добавления комментария
    $arResult["CAN_WRITE"] = "Y";
}


//проверка групп пользователей
    if (count(array_intersect($currentUserGroups, $arParams["READ_USER_GROUPS"])) < 1)
    {
        ShowError(GetMessage("RULES_ERROR"));
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
        $arResult["ITEMS"][] = array(
            "ID"    =>  $arComments["ID"],
            "NAME"  =>  $arComments["NAME"],
            "TEXT"  =>  $arComments["DETAIL_TEXT"],
            "TIME"  =>  substr($arComments["TIMESTAMP_X"], 11, 5),
            "DATE"  =>  substr($arComments["TIMESTAMP_X"], 0, 10)
        );
    }

    $this->IncludeComponentTemplate();
?>