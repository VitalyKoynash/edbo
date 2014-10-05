var options = {
  valueNames: [ 'Id_PersonRequest', 'PersonCodeU', 'SpecClasifierCode',
      'OriginalDocumentsAdd', 'UniversitySpecialitiesKode',
	  'Id_PersonDocument', 'IsCheckForPaperCopy', 'EntrantDocumentValue',
	  '_fio', '_dir_code', '_dir', '_edv'
      ]
};

// Init list
var contactList = new List('requests-edit-konkurs', options);

//var idField = $('#id-field'),
    //nameField = $('#name-field'),
    //dirField = $('#dir-field'),
    //odField = $('#od-field'),
    //onBtn = $('#on-btn'),
    //offBtn = $('#off-btn').hide();
    //removeBtns = $('.remove-item-btn'),
var    editBtns = $('.btn_ochange_edv');

var editExam = $('.btn_ochange_exam');
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

//http://justcodeit.wordpress.com/2013/03/05/dynamically-add-buttons-to-a-jqueryui-dialog/

function refreshCallbacks() {
  //$("input:text").focus(function() { $(this).select(); } );
  /*
  $("#sel").focus(function(){
        // Select input field contents
        this.select();
    });  
   */ 
  editBtns = $(editBtns.selector);
  
  editExam = $(editExam.selector);
  
  editExam.click(function() {
      //alert(1);
      console.log($(this));
	  
    
				 
	  
	  
	  //return;
	  var context = $(this)['context'];
	  var dataset = context['dataset'];
	  //var offsetParent = context['offsetParent'];
	  
	  
	var id_personrequest = dataset['id_personrequest'];
	var id_universityspecialitiessubject = dataset['id_universityspecialitiessubject'];  
	  
	var Id_Qualification = dataset['id_qualification']; 
	var Id_PersonEducationForm = dataset['id_personeducationform']; 
        
        var OriginalDocumentsAdd = dataset['originaldocumentsadd'];
        var IsNeedHostel  = dataset['isneedhostel'];
        var IsBudget = dataset['isbudget'];
        var IsContract = dataset['iscontract'];
        var IsHigherEducation = dataset['ishighereducation'];
        var CodeOfBusiness = dataset['codeofbusiness'];
	var IsForeignWay = dataset['isforeignway'];
         
	console.log(id_personrequest);
	console.log(id_universityspecialitiessubject);
	//console.log($('.Id_UniversitySpecialitiesSubject'+id_personrequest+''+id_universityspecialitiessubject));
	var name = 'Id_UniversitySpecialitiesSubject_'+id_universityspecialitiessubject+'_'+id_personrequest;
	
	console.log(name);
	/*
	console.log('input[name='+name+']');
	console.log($('input[name='+name+']'));
	console.log($('input[name='+name+']').val());
	*/
	
	console.log($('.'+name).val());
	var examval = $('.'+name).val();
	//alert('examval = '+examval);
	//return;
	
	  var sessionId = $('#sessionId').val();

	     $.ajax({
        type: "POST",
        url: "edbo-action_edit.php",
        data: "action=exam_change"+
                "&sessionId="+sessionId+
                "&Id_PersonRequest="+id_personrequest+
                "&Id_UniversitySpecialitiesSubject="+id_universityspecialitiessubject+
				"&Id_Qualification="+Id_Qualification+
				"&Id_PersonEducationForm="+Id_PersonEducationForm+
                                
                                "&OriginalDocumentsAdd="+OriginalDocumentsAdd+
                                "&IsNeedHostel="+IsNeedHostel+
                                "&CodeOfBusiness="+CodeOfBusiness+
                                "&IsBudget="+IsBudget+
                                "&IsContract="+IsContract+
                                "&IsHigherEducation="+IsHigherEducation+
                                "&IsForeignWay="+IsForeignWay+
                                
                "&PersonRequestExaminationValue="+examval,
            dataType: 'json',
            timeout: 30000,
            cache: false,
            async: false,
        
        // Выводим то что вернул PHP
        success: function(res) {
            console.log('result');
            console.log(res);		
			//if (res == undefined) return;
            //alert("result: "+res);
            return;
            if (res.search("[FAILED]") != -1) {
                alert("result: "+res);
                //txt.val(777);
                return;  
            }
 

        },
        errrep:true,//отображение ошибок error если true
        error: function(jqXHR, exception) {
                if (jqXHR.status === 0) {
                    alert('Not connect.\n Verify Network.');
                } else if (jqXHR.status == 404) {
                    alert('Requested page not found. [404]');
                } else if (jqXHR.status == 500) {
                    alert('Internal Server Error [500].');
                } else if (exception === 'parsererror') {
                    alert('Requested JSON parse failed.');
                } else if (exception === 'timeout') {
                    alert('Time out error.');
                } else if (exception === 'abort') {
                    alert('Ajax request aborted.');
                } else {
                    alert('Uncaught Error.\n' + jqXHR.responseText);
                }
            }
      });
	  
  });
  /*
  removeBtns.click(function() {
    var itemId = $(this).closest('tr').find('.id').text();
    contactList.remove('id', itemId);
  });
  */
    editBtns.click(function() {
        //console.log(editBtns);
        //return;
        var sessionId = $('#sessionId').val();
        //alert('sessionId = '+ sessionId);
        var UniversityKode = $('#UniversityKode').val();
       // alert('UniversityKode = '+ UniversityKode);
	var itemId = $(this).closest('tr').find('.Id_PersonRequest').text();
        
        // объект - поле с данными о среднем балле аттестата
	var EntrantDocumentValue = $(this).closest('tr').find('._EntrantDocumentValue');
	//alert(txt.val());
	//txt.val(777);
        var itemValues = contactList.get('Id_PersonRequest', itemId)[0];//.values();
    
       // alert(1);
    
        var Id_PersonRequest= itemValues.values().Id_PersonRequest;
	var PersonCodeU= itemValues.values().PersonCodeU;
	var Id_PersonDocument = itemValues.values().Id_PersonDocument;
	var UniversitySpecialitiesKode = itemValues.values().UniversitySpecialitiesKode;
        var IsCheckForPaperCopy = itemValues.values().IsCheckForPaperCopy;
        //alert(Id_PersonDocument);
        //alert(UniversitySpecialitiesKode);
        
       
    $.ajax({
        type: "POST",
        url: "edbo-action_edit.php",
        data: "action=EntrantDocumentValue_change"+
                "&sessionId="+sessionId+
                "&Id_PersonDocument="+Id_PersonDocument+
                "&UniversityKode="+UniversityKode+
                "&EntrantDocumentValue="+EntrantDocumentValue.val()+
                "&IsCheckForPaperCopy="+IsCheckForPaperCopy,
        async: false,
        // Выводим то что вернул PHP
        success: function(res) {
            console.log(res);
            //alert("result: "+res);
            //return;
            if (res.search("[FAILED]") != -1) {
                alert("result: "+res);
                //txt.val(777);
                return;  
            }
 

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
