<?
   //Подключается пролог ядра bitrix
   require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
   //устанавливливается заголовок страницы
   $APPLICATION->SetTitle("AJAX");

   //Подключение ядра и расширения - ajax
   CJSCore::Init(array('ajax'));
   // Переменная для проверки
   $sidAjax = 'testAjax';
   // Если в запросе есть 'ajax_form' показывается - 'HELLO'
   if(isset($_REQUEST['ajax_form']) && $_REQUEST['ajax_form'] == $sidAjax){
      $GLOBALS['APPLICATION']->RestartBuffer();
      echo CUtil::PhpToJSObject(array(
            'RESULT' => 'HELLO',
            'ERROR' => ''
      ));
      die();
   }
?>

<div class="group">
   <div id="block"></div >
   <div id="process">wait ... </div >
</div>

<script>
   //Разрешается вывод в консоль отладочной информации
   window.BXDEBUG = true;

//Функция DEMOLoad: Загружает данные
function DEMOLoad(){
   BX.hide(BX("block"));   // Скрывается div-блок с id = "block"
   BX.show(BX("process")); // Показывается div-блок с id = "process"
   // Загрузка json-объекта и вызов callback функции DEMOResponse
   BX.ajax.loadJSON(
      '<?=$APPLICATION->GetCurPage()?>?ajax_form=<?=$sidAjax?>',
      DEMOResponse
   );
}

//Функция DEMOResponse: Отображает данные
function DEMOResponse (data){
   BX.debug('AJAX-DEMOResponse ', data); // Вывод в консоль полученных данных из переменной - data
   BX("block").innerHTML = data.RESULT;  // div-блок с id = "block" заполняется содержимым data.RESULT
   BX.show(BX("block"));   // Показывается div-блок с id = "block"
   BX.hide(BX("process")); // Скрывается div-блок с id = "process"

   // Установка обработчика события DEMOUpdate для элемента DOM-дерева - BX("block")
   BX.onCustomEvent(
      BX(BX("block")),
      'DEMOUpdate'
   );
}

// Добавляется обработчик после загрузки DOM 
BX.ready(function(){
   /*
   BX.addCustomEvent(BX("block"), 'DEMOUpdate', function(){
      window.location.href = window.location.href;
   });
   */
   BX.hide(BX("block"));   // Скрывается div-блок с id = "block"
   BX.hide(BX("process")); // Скрывается div-блок с id = "process"
   
   // Устанавливается обработчик события по клику на элемент DOM с классом - css_ajax
   BX.bindDelegate(
     document.body, 'click', {className: 'css_ajax' },
     function(e){
       if(!e)
       e = window.event;
         
       DEMOLoad();
       return BX.PreventDefault(e);
     }
   );
   
});

</script>

<div class="css_ajax">click Me</div>

<?
//подключаем эпилог ядра bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>