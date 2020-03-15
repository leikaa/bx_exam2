<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="news-detail">
	<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])):?>
		<img class="detail_picture" border="0" src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>" height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>" alt="<?=$arResult["NAME"]?>"  title="<?=$arResult["NAME"]?>" />
	<?endif?>
	<?if($arParams["DISPLAY_DATE"]!="N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
		<div class="news-date"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></div>
	<?endif;?>
	<?if($arParams["DISPLAY_NAME"]!="N" && $arResult["NAME"]):?>
		<h3><?=$arResult["NAME"]?></h3>
        <? if ($arParams["SET_AJAX_COMPLAINT"] === "Y"): ?>
            <script>
                (function(BX) {
                    BX.ready(function () {
                        var complaintEntry = document.getElementById("complaint");
                        complaintEntry.onclick = function() {
                            BX.ajax.loadJSON(
                                '<? $APPLICATION->GetCurPage(); ?>',
                                {
                                    "TYPE": "REPORT_AJAX",
                                    "ID": <?=$arResult["ID"];?>
                                },
                                function(data) {
                                    var complaintResult = document.getElementById("complaint_result");
                                    complaintResult.innerText = "Ваше мнение учтено, №" + data;
                                },
                                function() {
                                    var complaintResult = document.getElementById("complaint_result");
                                    complaintResult.innerText = "Ошибка!"
                                }
                            );
                        }
                    });
                })(BX);
            </script>
            <span id="complaint" class="complaint">Пожаловаться на новость!</span>
            <span id="complaint_result" class="complaint_result"></span>
        <? else: ?>
            <a id="complaint" class="complaint" href="<? $APPLICATION->GetCurPage();?>?ID=<?=$arResult["ID"];?>&TYPE=REPORT_GET">Пожаловаться на новость!</a>
            <span id="complaint_result" class="complaint_result"></span>
        <? endif; ?>
	<?endif;?>
	<div class="news-detail">
	<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arResult["FIELDS"]["PREVIEW_TEXT"]):?>
		<p><?=$arResult["FIELDS"]["PREVIEW_TEXT"];unset($arResult["FIELDS"]["PREVIEW_TEXT"]);?></p>
	<?endif;?>
	<?if($arResult["NAV_RESULT"]):?>
		<?if($arParams["DISPLAY_TOP_PAGER"]):?><?=$arResult["NAV_STRING"]?><br /><?endif;?>
		<?echo $arResult["NAV_TEXT"];?>
		<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?><br /><?=$arResult["NAV_STRING"]?><?endif;?>
 	<?elseif(strlen($arResult["DETAIL_TEXT"])>0):?>
		<?echo $arResult["DETAIL_TEXT"];?>
 	<?else:?>
		<?echo $arResult["PREVIEW_TEXT"];?>
	<?endif?>
	<div style="clear:both"></div>
	<br />
	<?foreach($arResult["FIELDS"] as $code=>$value):?>
			<?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
			<br />
	<?endforeach;?>
	<?foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>

		<?=$arProperty["NAME"]?>:&nbsp;
		<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
			<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
		<?else:?>
			<?=$arProperty["DISPLAY_VALUE"];?>
		<?endif?>
		<br />
	<?endforeach;?>
	</div>
</div>
