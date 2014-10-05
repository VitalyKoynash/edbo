var options = {
  valueNames: [ 'Id_PersonRequest', 'PersonCodeU', 'SpecClasifierCode',
      'OriginalDocumentsAdd', 'CodeOfBusines','_req', '_fio', '_dir', 
      '_dir_code','_date_enter','_code_busines' ,'_konkurs_value','_od']
};

// Init list
var contactList = new List('requests', options);

//var idField = $('#id-field'),
    //nameField = $('#name-field'),
    //dirField = $('#dir-field'),
    //odField = $('#od-field'),
    //onBtn = $('#on-btn'),
    //offBtn = $('#off-btn').hide();
    //removeBtns = $('.remove-item-btn'),
var    editBtns = $('.btn_od_change');

// Sets callbacks to the buttons in the list
refreshCallbacks();
/*
addBtn.click(function() {
  contactList.add({
    id: Math.floor(Math.random()*110000),
    name: nameField.val(),
    age: ageField.val(),
    city: cityField.val()
  });
  clearFields();
  refreshCallbacks();
});
*/
/*
editBtn.click(function() {
  var item = contactList.get('id', idField.val())[0];
  item.values({
    id:idField.val(),
    name: nameField.val(),
    age: ageField.val(),
    city: cityField.val()
  });
  //clearFields();
  //editBtn.hide();
  //addBtn.show();
});
*/
function refreshCallbacks() {
  // Needed to add new buttons to jQuery-extended object
  //removeBtns = $(removeBtns.selector);
  editBtns = $(editBtns.selector);
  /*
  removeBtns.click(function() {
    var itemId = $(this).closest('tr').find('.id').text();
    contactList.remove('id', itemId);
  });
  */
  editBtns.click(function() {
    var itemId = $(this).closest('tr').find('.Id_PersonRequest').text();
    var itemValues = contactList.get('Id_PersonRequest', itemId)[0];//.values();
    
    var sessionId = $('#sessionId').val();
    var Id_PersonRequest= itemValues.values().Id_PersonRequest;
	var PersonCodeU= itemValues.values().PersonCodeU;
	var OriginalDocumentsAdd= itemValues.values().OriginalDocumentsAdd;
	var CodeOfBusines= itemValues.values().CodeOfBusines;
    //alert (sessionId);
    //alert (Id_PersonRequest);
    
    var od_vals = new Array ('Копия','Оригинал');
// Отсылаем паметры
    $.ajax({
        type: "POST",
        url: "edbo-action_edit.php",
        data: "action=od_change&sessionId="+sessionId+"&Id_PersonRequest="+Id_PersonRequest+"&OriginalDocumentsAdd="+OriginalDocumentsAdd,
        timeout: 30000,
		cache: false,
		async: false,
		// Выводим то что вернул PHP
        success: function(res) {
            //предварительно очищаем нужный элемент страницы
            //$("#result").empty();
            //и выводим ответ php скрипта
            //$("#result").append(html);
            //alert("result: "+res);
            //alert(res.length);
            if (res=="[FAILED]" || res.length ==0) {
				alert("result: "+res);
              return;  
            }

            if (res!=0 && res!=1) {
				alert("result: "+res);
              return;  
            }
            
            //alert("Save values");
            itemValues.values({
                OriginalDocumentsAdd: res,
                _od: od_vals[res]
            });


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

    
    //alert (itemValues.values().od);
    //itemValues.od.val('+');
    //idField.val(itemValues.id);
    //nameField.val(itemValues.name);
    //ageField.val(itemValues.age);
    /*
      itemValues.values({
        od:'+'
    });
    */
    //cityField.val(itemValues.city);
    
    //editBtn.show();
    //addBtn.hide();
  });
}

function clearFields() {
  //nameField.val('');
  //ageField.val('');
  //cityField.val('');
}
