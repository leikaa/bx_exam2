<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div><?= time();?></div>
<br/>
<div>Фильтр: <a href="<?= $APPLICATION->GetCurPage(); ?>?F=Y"><?= $APPLICATION->GetCurPage(); ?>?F=Y</a></div>
<div>---</div>
<div class="bold"><?= GetMessage('CATALOG');?></div>
<ul>
    <? foreach($arResult["NEWS"] as $news): ?>
        <?
        $this->AddEditAction($news['ID'], $news["ITEMS"]['EDIT_LINK'], CIBlock::GetArrayByID($news["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($news['ID'], $news["ITEMS"]['DELETE_LINK'], CIBlock::GetArrayByID($news["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <li id="<?=$this->GetEditAreaId($news['ID']);?>">
            <span class="bold"><?= $news["NAME"]; ?></span><span> - <?= $news["DATE_ACTIVE_FROM"]; ?> (<?= implode(", ", $news["SECTIONS"]); ?>)</span>
            <ul>
                <? foreach($news["PRODUCTS"] as $product): ?>
                    <li><?= $product["NAME"]; ?> - <?= $product["PROPERTY_PRICE_VALUE"]; ?> - <?= $product["PROPERTY_MATERIAL_VALUE"]; ?>
                    - <?= $product["PROPERTY_ARTNUMBER_VALUE"]; ?></li>
                <? endforeach; ?>
            </ul>
        </li>
    <? endforeach; ?>
</ul>
