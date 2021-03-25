<?

CModule::IncludeModule('iblock');
CModule::IncludeModule('sale');
$iblock_products=2; // IBLOCK товаров
$iblock_torg=20; // IBLOCK торговых предложений

$ciBlockElement = new CIBlockElement;

// Добавляем товар-родитель, у которго будут торг. предложения
$product_id = $ciBlockElement->Add(
    array(
        'IBLOCK_ID' => $iblock_products, 
        'NAME' => "Ferrari", //Red bull
        "ACTIVE" => "Y",
        // Прочие параметры товара
    )
);
// проверка на ошибки
if (!empty($ciBlockElement->LAST_ERROR)) {
    echo "Ошибка добавления товара: ". $ciBlockElement->LAST_ERROR;
    die();
}
// добавляем нужное кол-во торговых предложений
$arLoadProductArray = array(
    "IBLOCK_ID"      => $iblock_torg, 
    "NAME"           => "Торговое предложение 1",
    "ACTIVE"         => "Y",
    'PROPERTY_VALUES' => array(
        'CML2_LINK' => $product_id, // Свойство типа "Привязка к товарам (SKU)", связываем торг. предложение с товаром
    )
    // Прочие параметры товара 
);
$product_offer_id = $ciBlockElement->Add($arLoadProductArray);
// проверка на ошибки
if (!empty($ciBlockElement->LAST_ERROR)) {
    echo "Ошибка добавления торгового предложения: ". $ciBlockElement->LAST_ERROR;
    die();
}
// Добавляем параметры к торг. предложению
CCatalogProduct::Add(
    array(
        "ID" => $product_offer_id,
        "QUANTITY" => 9999
    )
);
// Добавляем цены к торг. предложению
CPrice::Add(
    array(
        "CURRENCY" => "RUB",
        "PRICE" => 999,
        "CATALOG_GROUP_ID" => 1,
        "PRODUCT_ID" => $product_offer_id,
    )
);
?>