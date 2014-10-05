
var    editBtns = $('#btn_koatuu_search');

// Sets callbacks to the buttons in the list
refreshCallbacks();

function refreshCallbacks() {
  // Needed to add new buttons to jQuery-extended object
  editBtns = $(editBtns.selector);

  editBtns.click(function() {
    //var itemId = $(this).closest('tr').find('.Id_PersonRequest').text();
    //var itemValues = contactList.get('Id_PersonRequest', itemId)[0];//.values();
	//alert('koatuu search begin');
    $("#koatuu").empty();
    var sessionId = $('#sessionId').val();
	var search = $('#search').val();
    //var Id_PersonRequest= itemValues.values().Id_PersonRequest;

    //alert (sessionId);
    //alert (Id_PersonRequest);
    
    //alert(search);
// Отсылаем паметры
    $.ajax({
        type: "POST",
        url: "./edbo-koatuu-search_module.php",
        data: "search="+search+"&sessionId=" + sessionId,
        // Выводим то что вернул PHP
        success: function(res) {
            //предварительно очищаем нужный элемент страницы
            $("#koatuu").empty();
            //и выводим ответ php скрипта
            $("#koatuu").append(res);

        },
        errrep:true,//отображение ошибок error если true
        error:function(num) {//ошибки запроса
            var arr=['Your browser does not support Ajax',
                    'Request failed',
                    'Address does not exist',
                    'The waiting time left'];
            alert("Error");
            //alert(arr[num]);
      }
      });

  
  });
}

function clearFields() {
  //nameField.val('');

}
