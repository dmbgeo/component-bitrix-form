<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

use Bitrix\Main\Loader;

$headers = "Content-Type: text/html; charset=utf-8";
$headers .= "Content-Transfer-Encoding: 8bit";

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
if (strlen($arParams["IBLOCK_TYPE"]) <= 0)
	$arParams["IBLOCK_TYPE"] = "";
$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);


if (!Loader::includeModule("iblock")) {
	return;
}
$predResult = array();
$fields = array();
if (is_numeric($arParams["IBLOCK_ID"])) {
	$predResult = GetIBlock($arParams["IBLOCK_ID"], $arParams["IBLOCK_TYPE"]);
	$predResult['FIELDS'] = CIBlock::GetFields($arParams["IBLOCK_ID"]);
	$properties = CIBlockProperty::GetList(array("sort" => "asc", "name" => "asc"), array("ACTIVE" => "Y", "IBLOCK_ID" => $arParams["IBLOCK_ID"]));
	while ($prop_fields = $properties->GetNext()) {
		$fields[$prop_fields['CODE']] = $prop_fields;
	}
	if (is_array($fields)) {
		$predResult['PROPERTIES'] = $fields;
	} else {
		return;
	}



	foreach ($predResult['PROPERTIES'] as $key => $val) {
		if ($val['PROPERTY_TYPE'] == 'L') {
			$prop_list = array();
			$property_enums = CIBlockPropertyEnum::GetList(array("DEF" => "DESC", "SORT" => "ASC"), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "CODE" => $val['CODE']));
			while ($enum_fields = $property_enums->GetNext()) {
				$prop_list[] = $enum_fields;
			}

			$predResult['PROPERTIES'][$key]['LIST_PARAM'] = $prop_list;
		}
	}


	$arResult = array();
	$arResult['NAME'] = $predResult['NAME'];
	$arResult['DESCRIPTION'] = $predResult['DESCRIPTION'];
	$arResult['ACTION'] = $componentPath . '/ajax.php';
	$arResult['IBLOCK_TYPE'] = empty($arParams['IBLOCK_TYPE']) ? "" : $arParams['IBLOCK_TYPE'];
	$arResult['IBLOCK_ID'] = empty($arParams['IBLOCK_ID']) ? "" : $arParams['IBLOCK_ID'];
	$arResult['SEND_EMAIL'] = empty($arParams['SEND_EMAIL']) ? "" : $arParams['SEND_EMAIL'];
	$arResult['MESSAGE_SUCCESS'] = empty($arParams['MESSAGE_SUCCESS']) ? "" : $arParams['MESSAGE_SUCCESS'];
	$arResult['MESSAGE_ERROR'] = empty($arParams['MESSAGE_ERROR']) ? "" : $arParams['MESSAGE_ERROR'];
	$arResult['SUBMIT_ID'] = empty($arParams['SUBMIT_ID']) ? "" : $arParams['SUBMIT_ID'];

	foreach ($predResult['PROPERTIES'] as $key => &$val) {
		$arResult['PROPERTIES'][$key] = $val;
		$arResult['PROPERTIES'][$key]['MASK'] = empty($arParams[$val['CODE']]) ? "" : $arParams[$val['CODE']];
		unset($val);
	}

	$arResult['MASK'] = array();
	if (strlen($arParams['NAME']) > 0) {
		$arResult['MASK'][] = array('name' => 'NAME', 'value' => $arParams['NAME']);
	}
	foreach ($arResult['PROPERTIES'] as $key => $prop) {
		if (strlen($prop['MASK']) > 0) {
			$arResult['MASK'][] = array('name' => $key, 'value' => $prop['MASK']);
		}
	}
	unset($predResult);


	?>
	<script>
		document.FormParam = {
			ACTION: '<?= $arResult['ACTION']; ?>',
			IBLOCK_ID: '<?= $arResult['IBLOCK_ID']; ?>',
			SEND_EMAIL: '<?= $arResult['SEND_EMAIL']; ?>',
			MESSAGE_SUCCESS: '<?= $arResult['MESSAGE_SUCCESS']; ?>',
			MESSAGE_ERROR: '<?= $arResult['MESSAGE_ERROR']; ?>',
			SUBMIT_ID: '<?= $arResult['SUBMIT_ID']; ?>',
			MASK: JSON.parse('<?= json_encode($arResult['MASK']); ?>'),
		};
	</script>
<?
	$this->includeComponentTemplate();
}
