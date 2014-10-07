<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>

<?if ($arResult["CAN_WRITE"] == "Y"):?>
    <form method="POST">
        <table>
            <tr>
                <td></td>
                <td><textarea name="comment"></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="Добавить"></td>
            </tr>
        </table>
    </form>
<?endif;?>

<?foreach ($arResult["ITEMS"] as $comment):?>
    <div>
        <div>
            <?=$comment["NAME"]?>
        </div>
        <div>
            <?=$comment["TIMESTAMP_X"]?>
        </div>
        <div>
            <?=$comment["DETAIL_TEXT"]?>
        </div>
    </div>
<?endforeach?>