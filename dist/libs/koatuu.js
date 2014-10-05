define(

    ['jquery'],
    function( $ ){
        //alert('koatuu begin ajax');
        
        var sessionId = $('#sessionId').val();
        //alert ('sessionId = '+ sessionId);
        var data = 0;
	$.ajax({
        type: "GET",
        async: false,
        url: "./edbo-getkoatuu-json.php",
        data: 'sessionId='+sessionId,
        // Выводим то что вернул PHP
        success: function(res) {
            alert('koatuu result:'+res);
            /*
            function koatuu() {
                var self = this;
                self.data = res;
            }
            */
            data = res;
            //return new koatuu();

        },
        errrep:true,//отображение ошибок error если true
        error:function(num) {//ошибки запроса
            var arr=['Your browser does not support Ajax',
                    'Request failed',
                    'Address does not exist',
                    'The waiting time left',
					'ERROR1',
					'ERROR2',
					'ERROR3'];
            alert("Error");
            //alert(arr[num]);
      }
      });
      
      return {'data': data};
        
});
