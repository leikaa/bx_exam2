<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент 2");
?><?$APPLICATION->IncludeComponent(
	"exam2:simplecomp2.exam",
	"",
	Array(
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"ELEMENT_PROP_CODE" => "COMPANY",
		"IBLOCK_CLASSIFIER" => "7",
		"IBLOCK_PRODUCTS" => "2",
		"TEMPLATE_DETAIL_URL" => "catalog_exam/#SECTION_ID#/#ELEMENT_CODE#"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>