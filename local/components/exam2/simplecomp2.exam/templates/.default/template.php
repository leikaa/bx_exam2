<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div>---</div>
<div class="bold"><?= GetMessage("CATALOG"); ?></div>
<ul>
    <? foreach($arResult["COMPANY"] as $company): ?>
        <? if (isset($company["ID"])): ?> <!-- без этой проверки постраничка будет выводить лишние элементы, входящие в состав классификатора -->
            <li>
                <span class="bold"><?= $company["NAME"]; ?></span>
                <ul>
                        <? foreach($company["PRODUCTS"] as $product): ?>
                            <li><?= $product["NAME"]; ?> - <?= $product["PROPERTY_PRICE_VALUE"]; ?> - <?= $product["PROPERTY_MATERIAL_VALUE"]; ?>
                                - <?= $product["PROPERTY_ARTNUMBER_VALUE"]; ?> (<?= $product["DETAIL_URL"]; ?>)</li>
                        <? endforeach; ?>
                </ul>
            </li>
        <? endif; ?>
    <? endforeach; ?>
</ul>

<?= $arResult["NAV_STRING"]; ?>