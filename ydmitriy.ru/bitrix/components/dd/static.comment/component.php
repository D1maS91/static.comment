<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//TODO:вынести в lang файлы
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
            "MODIFIED_BY"    => $USER->GetID(),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"      => $arParams["IBLOCK_ID"],
            "PROPERTY_VALUES"=> $properties,
            "NAME"           => $_POST["name"],
            "ACTIVE"         => $arParams["MODERATION"],
            "DETAIL_TEXT"    => $_POST["comment"],
        );
        if ($ID = $comment->Add($arLoadArray))
        {
            if ($arParams["MODERATION"] == "Y")
                ShowNote("Комментарий будет добавлен после модерации");
            else
                ShowNote("Комментарий успешно добавлен");
        } else {
            ShowMessage("Ошибка при добавлении комментария");
        }
    }

    //выставляем флаг для отображения формы добавления комментария
    $arResult["CAN_WRITE"] = "Y";
}


//проверка групп пользователей
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

    $this->IncludeComponentTemplate();
?>