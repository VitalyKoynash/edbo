<?php

class AdminPanel {

    private $menu = array();
    
    private $header = array();
    
    
    public function addMenu($group, $label, $url, $params) {
        if (is_null($params)) $params = array();
        if (is_null($group)) $group = 'Menu';
        
        $item = array();
        $item['url'] = $url;
        $item['label'] = is_null($label)?'&nbsp;':$label ;
        
        $class = @$params['class'];
        $item['class'] = is_null($class)?'menu_adminpanel_item':$class;
        
        $id = @$params['id'];
        $item['id'] = is_null($id)?'':$id;
        
        $target = @$params['target'];
        $item['target'] = is_null($target) ? '_blank' : $target;
        
        /*
        if (is_null($id_menu)) {
                $this->menu[count($this->menu)] = $item;
                $item['id_menu'] = is_null($id_menu);
        } else {
                $this->menu[$id_menu] = $item;
                $item['id_menu'] = $id_menu;
        }
         
         */
         $this->menu[$group][] = $item;
		
        return $item;
    }
    /*
    public function removeMenu($id) {
        
        unset($this->menu[$id_menu]);
    }
     
     */
    /*
    public function addHeader($id_header, $name,  $class, $id) {
        $item = array();
        $item['name'] = is_null($name)?'&nbsp;':$name ;
        $item['class'] = is_null($class)?'admin_panel_header':$class;
        $item['id'] = is_null($id)?'':$id;
        
        $this->header[$id_header] = $item;
    }
    
    public function removeHeader($id_header) {
        unset($this->header[$id_header]);
    }
    */
    public function getPanel($name) {
        ob_start();
        
        echo '<div id="accordion">';
        
        foreach ($this->menu as $group => $item) {
            echo '<h3>',$group,'</h3>';
            echo '<div>';
            foreach ($item as $idx => $menu) {
                echo '<a href="',$menu['url'],'" class="menu_adminpanel_item','" target="',$menu['target'],'">';
                    echo '<div class="',$menu['class'],'">';
                    echo '<p class="menu_adminpanel_item">',$menu['label'],'</p>';
                    //echo $menu['label'];
                    echo '</div>';
                echo '</a>';
            }
            echo '</div>';
        }
        echo '</div>';  
        
        $res = ob_get_contents();
        ob_end_clean();
        return $res;
        
        echo '<table class="admin_panel" >';
        echo '<tr class="admin_panel_header"><td>',is_null($name)? 'Admin panel':$name,'</td></tr>';
        
        // header
        foreach ($this->header as $id => $item) {

            echo '<tr><td>';
            echo $item['name'];
            echo '</td></tr>';
        }

        // menu item
        foreach ($this->menu as $id => $item) {
            echo '<tr  class="',$item['class'],'"><td>';
            
            if ($item['name']=='') {
                echo '<div>&nbsp;</div>';
            } else {
                
                echo '<a href="',
                    $item['url'],
                    '" class="menu_adminpanel',//,$item['class'] ,
                    '" target="',$item['target'],'">',
                    $item['name'],
                    '</a>';
            }
            echo '</td></tr>';
            
        }
        
        $res = ob_get_contents();
        ob_end_clean();
        return $res;
    }
    
}
