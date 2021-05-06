<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult)) return;

$arMenu = array();
$first = true;
foreach ($arResult as $itemIndex => $arItem) {
    if ($arItem["PERMISSION"] > "D" && $arItem["DEPTH_LEVEL"] == 1) {
        $className = 'nav-item';
        if ($first) {
            $className .= ' first-item';
            $first = false;
        }
        if ($arItem['SELECTED']) {
            $className .= ' selected';
        }

        $arItem['CLASS'] = $className;
        $arMenu[] = $arItem;
    }
}

if (empty($arMenu)) return;

$arMenu[count($arMenu) - 1]['CLASS'] .= ' last-item';
?>
<ul class="nav justify-content-center">
            <?
            foreach ($arMenu as $arItem):
                ?>
                <li<?
                if ($arItem['CLASS']) echo " class=\"" . trim($arItem['CLASS']) . "\"" ?>>
                    <a href="<?= $arItem["LINK"] ?>" class="nav-link "><?= $arItem["TEXT"] ?></a>
                </li>
            <?
            endforeach;
            ?>
</ul>