require.config({
    baseUrl: "/edbo/dist/",
    waitSeconds: 300,
    //sessionId : $('#sessionId').val(),
    //urlArgs: "sessionId="+$('#sessionId').val(),
    paths: {
        //"calendar" : "zeitproject.calendar",
        "jquery"   : "libs/jquery/jquery",
        "jquery-ui"   : "libs/jquery/jquery-ui",
        //"mymodule"   : "mymodule",
	//'jstree' : 'jstree'
        /*"formatter": "/utils/formatter",
        "tooltip"  : "/controls/zeitproject.tooltip",
        "tiptip"   : "/controls/jquery.tiptip.min"   */ 
    }
  }); 

  
  require(
    ['jquery','jquery-ui'], 
    function( $ ){
        console.log(_,$);
        alert('app-edbo-requests get: '+ $);
        //console.log(koatuu);
    }
);
