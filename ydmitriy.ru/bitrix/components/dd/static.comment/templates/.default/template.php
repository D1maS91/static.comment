<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<!--версткой заниматься я не буду-->
<?if ($arResult["CAN_WRITE"] == "Y"):?>
    <form method="POST">
        <table>
            <tr>
                <td>Текст: </td>
                <td><textarea type="text" name="comment"></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="Добавить"></td>
            </tr>
        </table>
    </form>
    <hr>
<?endif;?>

<?foreach ($arResult["ITEMS"] as $comment):?>
    <div>
        <div>
            <?=$comment["NAME"]?>
        </div>
        <div>
            <?=$comment["TIME"]?> <?=$comment["DATE"]?>
        </div>
        <div>
            <?=$comment["TEXT"]?>
        </div>
    </div>
    <hr>
<?endforeach?>