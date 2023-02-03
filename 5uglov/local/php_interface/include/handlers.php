<?

AddEventHandler('main', 'OnBeforeEventSend', array("FeedbackFormSend", "Send"));
class FeedbackFormSend
{ 
   function Send($arFields, $arTemplate)
   {
      if ($arTemplate["ID"]==7) //Проверка на нужный шаблон
      {
            if(CModule::IncludeModule("iblock"))
         {
               $arElFields=array(
                  "IBLOCK_ID" => 9, //ID информационного блока куда пишем
                  "NAME" => $arFields["AUTHOR"], //В название элемента пишем автора
                  "DETAIL_TEXT" => $arFields["TEXT"], //Текст сообщения
                  "PROPERTY_VALUES" => array( //
                     "EMAIL" => $arFields["AUTHOR_EMAIL"],//емейл
                     "PHONE" => $arFields["PHONE"],//Телефон
                  ),
               );
               $oElement = new CIBlockElement();
               $idElement = $oElement->Add($arElFields, false, false, false);//Пишем в инфоблок
         }
      }
      return false;
   }
} 
