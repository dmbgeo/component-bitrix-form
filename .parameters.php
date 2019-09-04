<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arCurrentValues */

if (!CModule::IncludeModule("iblock"))
	return;



$arTypesEx = CIBlockParameters::GetIBlockTypes(array("-" => " "));

$arIBlocks = array();
$db_iblock = CIBlock::GetList(array("SORT" => "ASC"), array("SITE_ID" => $_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_TYPE"] != "-" ? $arCurrentValues["IBLOCK_TYPE"] : "")));
while ($arRes = $db_iblock->Fetch())
	$arIBlocks[$arRes["ID"]] = "[" . $arRes["ID"] . "] " . $arRes["NAME"];

$prop_param = array();
$prop_param['NAME'] = array(
	"PARENT" => "DATA_SOURCE",
	"NAME" => GetMessage("IBLOCK_ELEMENT_NAME"),
	"TYPE" => "STRING",
	"DEFAULT" => '',
);


$rsProp = CIBlockProperty::GetList(array("sort" => "asc", "name" => "asc"), array("ACTIVE" => "Y", "IBLOCK_ID" => (isset($arCurrentValues["IBLOCK_ID"]) ? $arCurrentValues["IBLOCK_ID"] : $arCurrentValues["ID"])));
while ($arr = $rsProp->Fetch()) {
	$arProperty[$arr["CODE"]] = "[" . $arr["CODE"] . "] " . $arr["NAME"];
	if (in_array($arr["PROPERTY_TYPE"], array("L", "N", "S"))) {

		$prop_param[$arr["CODE"]] = array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => "PROPERTY_VALUES[" . $arr["CODE"] . "] " . $arr["NAME"],
			"TYPE" => "STRING",
			"DEFAULT" => '',
		);
	}
}



$arComponentParameters = array(
	"PARAMETERS" => array(
		"SEND_EMAIL" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_SEND_EMAIL"),
			"TYPE" => "STRING",
			"DEFAULT" => COption::GetOptionString("main", "email_from"),
		),
		"MESSAGE_SUCCESS" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SUCCESS_MESSAGE_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => GetMessage("SUCCESS_MESSAGE"),
		),
		"MESSAGE_ERROR" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("ERROR_MESSAGE_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => GetMessage("ERROR_MESSAGE"),
		),
		"SUBMIT_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SUBMIT_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => GetMessage("SUBMIT_VALUE"),
		),
		"IBLOCK_TYPE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_DESC_LIST_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arTypesEx,
			"DEFAULT" => "",
			"REFRESH" => "Y",
		),
		"IBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_DESC_LIST_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"DEFAULT" => '',
			"REFRESH" => "Y",
		),
	),
);

$arComponentParameters['PARAMETERS'] = array_merge($arComponentParameters['PARAMETERS'], $prop_param);
