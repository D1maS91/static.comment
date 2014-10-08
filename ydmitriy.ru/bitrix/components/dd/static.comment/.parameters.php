<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (!CModule::IncludeModule("iblock"))
{
    ShowMessage(GetMessage("IBLOCK_ERROR"));
    return false;
}
//получение списка типов инфоблоков
$dbIBlockTypes = CIBlockType::GetList(array("SORT"=>"ASC"), array("ACTIVE"=>"Y"));
while ($arIBlockTypes = $dbIBlockTypes->GetNext())
{
    $paramIBlockTypes[$arIBlockTypes["ID"]] = $arIBlockTypes["ID"];
}

//получение списка инфоблоков заданного типа
$dbIBlocks = CIBlock::GetList(
    array(
        "SORT"  =>  "ASC"
    ),
    array(
        "ACTIVE"        =>  "Y",
        "TYPE"          => $arCurrentValues["IBLOCK_TYPE"],
    ));
while ($arIBlocks = $dbIBlocks->GetNext())
{
    $paramIBlocks[$arIBlocks["ID"]] = "[" . $arIBlocks["ID"] . "] " . $arIBlocks["NAME"];
}

//получение списка свойств
$dbProperties = CIBlockProperty::GetList(
    array(
        "NAME"  =>  "ASC"
    ),
    array(
        "ACTIVE"    =>  "Y",
        "IBLOCK_ID" =>  $arCurrentValues["IBLOCK_ID"]
    )
);
while ($arProperties = $dbProperties->GetNext())
{
    $paramProperties[$arProperties["CODE"]] = $arProperties["NAME"];
}

//получение списка групп пользователей

$dbGroups = CGroup::GetList(($by="name"), ($order="asc"), array("ACTIVE"=>"Y"), "Y");
while ($arGroups = $dbGroups->Fetch())
{
    $paramGroups[$arGroups["ID"]] = $arGroups["NAME"] . " (" . $arGroups["USERS"] . ")";
}

//формирование массива параметров
$arComponentParameters = array(
    "GROUPS" => array(
        "RIGHTS"    =>  array(
            "NAME"  =>  GetMessage("RULES"),
            "SORT"  =>  "200",
        ),
        "ADDITIONAL"    =>  array(
            "NAME"  =>  GetMessage("ADDITIONAL"),
            "SORT"  =>  "300",
        ),
    ),
    "PARAMETERS" => array(
        "IBLOCK_TYPE"   =>  array(
            "PARENT"    =>  "BASE",
            "NAME"      =>  GetMessage("IBLOCK_TYPE"),
            "TYPE"      =>  "LIST",
            "VALUES"    =>  $paramIBlockTypes,
            "REFRESH"   =>  "Y",
            "MULTIPLE"  =>  "N",
        ),
        "IBLOCK_ID" =>  array(
            "PARENT"    =>  "BASE",
            "NAME"      =>  GetMessage("IBLOCK_ID"),
            "TYPE"      =>  "LIST",
            "VALUES"    =>  $paramIBlocks,
            "REFRESH"   =>  "Y",
            "MULTIPLE"  =>  "N",
        ),
        "PAGE_PROPERTY" =>  array(
            "PARENT"    =>  "BASE",
            "NAME"      =>  GetMessage("PAGE_PROPERTY"),
            "TYPE"      =>  "LIST",
            "VALUES"    =>  $paramProperties,
            "MULTIPLE"  =>  "N"
        ),
        "READ_USER_GROUPS"  =>  array(
            "PARENT"    =>  "RIGHTS",
            "NAME"      =>  GetMessage("READ_USER_GROUP"),
            "TYPE"      =>  "LIST",
            "VALUES"    =>  $paramGroups,
            "MULTIPLE"  =>  "Y",
        ),
        "WRITE_USER_GROUPS"  =>  array(
            "PARENT"    =>  "RIGHTS",
            "NAME"      =>  GetMessage("WRITE_USER_GROUP"),
            "TYPE"      =>  "LIST",
            "VALUES"    =>  $paramGroups,
            "MULTIPLE"  =>  "Y",
        ),
        "MODERATION"    =>  array(
            "PARENT"    =>  "ADDITIONAL",
            "NAME"      =>  GetMessage("MODERATION"),
            "TYPE"      =>  "CHECKBOX",
            "DEFAULT"   =>  "N"
        )
    ),
);
?>