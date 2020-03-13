<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div>Фильтр: <a href="<?= $APPLICATION->GetCurPage(); ?>?F=Y"><?= $APPLICATION->GetCurPage(); ?>?F=Y</a></div>
<div>---</div>
<div class="bold"><?= GetMessage('CATALOG');?></div>
<ul>
    <? foreach($arResult["NEWS"] as $news): ?>
        <li>
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
