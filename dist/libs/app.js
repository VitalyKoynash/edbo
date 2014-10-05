/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */ 
require.config({
    baseUrl: "dist/libs",
    waitSeconds: 300,
    //sessionId : $('#sessionId').val(),
    //urlArgs: "sessionId="+$('#sessionId').val(),
    paths: {
        //"calendar" : "zeitproject.calendar",
        "jquery"   : "jquery",
        //"mymodule"   : "mymodule",
	'jstree' : 'jstree'
        /*"formatter": "/utils/formatter",
        "tooltip"  : "/controls/zeitproject.tooltip",
        "tiptip"   : "/controls/jquery.tiptip.min"   */ }
  }); 
  /*
  require(
    ['jquery' ],
    function( $){
      require.config.sessionId =  $('#sessionId').val(); 
      alert('req sessionId = '+config.sessionId);
     }

    );
    */
  
  require(
    ['jquery', 'jstree', 'koatuu' ],
    function( $, jstree,  koatuu ){
        //alert('app get: '+ koatuu);
        console.log(koatuu);
        $('#koatuu').jstree({
            'core' : {
                'data' : eval( koatuu.data)
            }
        });

    }
);

/*
require(
    ['jquery', 'jstree', 'mymodule', ],
    function( $, jstree,  module ){
 		$('#koatuu').jstree({
			'core' : {
				'data' : [
            'Simple root node',
            {
                'id' : 'node_2',
                'text' : 'Root node with options',
                'state' : { 'opened' : true, 'selected' : true },
                'children' : [ { 'text' : 'Child 1' }, 'Child 2']
            }
        ]
			}
		});

    }
);
*/

/*
require(
    ['jquery', 'jstree', 'mymodule', ],
    function( $, jstree,  module ){
        //$('body').append( module.foo );
		$('#koatuu').jstree({
			'core' : 
			{
				'data' : 
				{
					'url' : function (node)  {
						console.log('url');
						console.log(node);
						return node.id === '#' ? 
						'./data/tree.json' :
						'./data/tree2.json' ;
						
					
					},
					'data' : function (node) {
						console.log('data');
						console.log(node);
						return {'id' : node.id};
					}
					
				}
				
			}
		});

    }
);
*/
