<?php
// $_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . "/..");
// $DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

// define("NO_KEEP_STATISTIC", true);
// define("NOT_CHECK_PERMISSIONS", true);
// define('CHK_EVENT', true);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

global $DMBGEO;

@set_time_limit(0);
@ignore_user_abort(true);
CModule::IncludeModule("iblock");
?>

<?
if (!empty($_POST['IBLOCK_ID']) &&  !empty($_POST['NAME']) &&  !empty($_POST['SEND_EMAIL'])) {

    if (empty($_POST['PROPERTY_VALUES'])) {
        $PROPERTY_VALUES = array();
    } else {
        $PROPERTY_VALUES = $_POST['PROPERTY_VALUES'];
    }

    $arLoadProductArray = array(
        "MODIFIED_BY"    => $USER->GetID(),
        "IBLOCK_SECTION_ID" => false,
        "IBLOCK_ID"      => (int) $_POST['IBLOCK_ID'],
        "PROPERTY_VALUES" => $PROPERTY_VALUES,
        "NAME"           => $_POST['NAME'],
        "ACTIVE"         => "Y"
    );
    $el = new CIBlockElement;
    if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
        CModule::IncludeModule("iblock");
        $mess = GetIBlock($_POST['IBLOCK_ID'])['DESCRIPTION'];

        $element = array();
        $res = CIBlockElement::GetByID($PRODUCT_ID);
        while ($ob = $res->GetNextElement()) {
            $arEl = $ob->GetFields();
            $arEl['PROPERTIES'] = $ob->GetProperties();
            $element = $arEl;
        }
        $mess = str_replace('{{NAME}}', $element['NAME'], $mess);
        if (!empty($element['PROPERTIES'])) {
            foreach ($element['PROPERTIES'] as $key => $prop) {
                $mess = str_replace('{{' . $key . '}}', $prop['VALUE'], $mess);
            }
        }
        $headers = "Content-Type: text/html; charset=utf-8";
        $headers .= "Content-Transfer-Encoding: 8bit";
        mail($_POST['SEND_EMAIL'], $element['IBLOCK_NAME'], $mess, $headers);
        echo 1;
    } else {
        echo "Error: " . $el->LAST_ERROR;
    }
} else {
    echo "Error: not fount name or iblock_id ";
}



?>


