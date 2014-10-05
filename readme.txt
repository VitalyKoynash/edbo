ajax-jQuery
http://phpworking.ru/javascript/ajax/otpravka-post-zaprosa-bez-perezagruzki-stranicy-ajax/
http://javascript.ru/blog/gordon-freeman/Primery-raboty-s-AJAX-Otpravka-GET-POST-HEAD-zaprosov-cherez-AJAX-javascript
http://habrahabr.ru/post/42426/
http://javascript.ru/forum/showthread.php?p=328325#post328325
http://www.fpublisher.ru/cms_fpublisher/javascript_develop/new753
http://javascript.ru/basic/closure#scope


javascript
http://learn.javascript.ru/xhr-forms

tree
http://www.jstree.com/docs/json/
http://habrahabr.ru/post/151239/

css
http://htmlbook.ru/samcss
http://www.w3schools.com/css/css_table.asp



/*
    $("#koatuu_tree")
    .bind("before.jstree", function (e, data) {
        // байндинг на событие перед загрузкой дерева
        alert(e);
        alert(data);
    })
    .jstree({ 
        // Подключаем плагины
        "plugins" : [ 
            "themes","json_data", "search"
        ],
        'core' : { 
            "ajax" : {
                'url' : "/edbo/edbo-getkoatuu-json.php", // получаем наш JSON
                'data' : function (n) { 
                    // необходиый коллбэк
                    alert (n);
                }
            }
        //},
    })
	*/
    /*
     * [
       { "id" : "ajson1", "parent" : "#", "text" : "Simple root node" },
       { "id" : "ajson2", "parent" : "#", "text" : "Root node 2" },
       { "id" : "ajson3", "parent" : "ajson2", "text" : "Child 1" },
       { "id" : "ajson4", "parent" : "ajson2", "text" : "Child 2" },
    ]
     */
    /*
    .bind("select_node.jstree", function(e, data){
        // байндинг на выделение нода
                // укажем ему открытие документа на основе якорей
                window.location.hash = "view_" + data.rslt.obj.attr("id").replace("node_","");
    })
    */