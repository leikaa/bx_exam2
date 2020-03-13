<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>
<ul>
    <? foreach($arResult["AUTHOR"] as $authorId => $author): ?>
        <li>
            <span>[<?= $authorId; ?>] - <?= $author["LOGIN"]; ?></span>
            <ul>
                <? foreach($author["NEWS"] as $news): ?>
                    <li><?= $news; ?></li>
                <? endforeach; ?>
            </ul>
        </li>
    <? endforeach; ?>
</ul>