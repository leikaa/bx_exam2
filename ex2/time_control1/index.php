<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оценка производительности 1");
?>Самая ресурсоемкая страница: /products/index.php - 36.69%<br>
Разница при режиме работы компонента по умолчанию и в случае с передачей только требуемых данных составила 22КБ.<br>
<!-- не забываем, что передача требуемых параметров в кэше идет как в component, так и в result_modifier, перед замером default режима работы необходимо закомментить -->
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>