<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<!-- <script>
    console.log(document.FormParam);
</script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
<?
CJSCore::Init(array("jquery"));
$APPLICATION->SetAdditionalCSS($templateFolder . '/style.css');
$APPLICATION->AddHeadScript($templateFolder . '/ajax.js');
$APPLICATION->AddHeadScript($templateFolder . '/script.js');
// var_dump($arResult);
?>
<div class="b-sec-btm-consult-inner">
    <div class="b-head"><?= $arResult['NAME'] ?></div>
    <div class="b-prc-form">
        <form onsubmit="return false;">
            <input type="text" required name="NAME" class="b-input NAME" placeholder="Ваше имя*">
            <input type="text" required name="PROPERTY_VALUES[ATT_PHONE]" class="b-input ATT_PHONE" placeholder="Номер телефона*">
            <div class="b-form-rules">Отправляя форму, я подтверждаю свое согласие на обработку персональных данных в соответствии с <a href="">Политикой конфиденциальности</a></div>
            <button class="b-btn-send" id="<?= $arResult['SUBMIT_ID'] ?>">ПОЛУЧИТЬ БЕСПЛАТНУЮ КОНСУЛЬТАЦИЮ</button>
        </form>
    </div>
</div>