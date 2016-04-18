<?php

/**
 * Class MenuItem represents a particular item in a navigation menu
 */
class MenuItem {

    public $title;
    public $link;
    public $class;
    public $counter;
    public $extra_html;
    public $display;

    /**
     * MenuItem constructor.
     * @param $title
     * @param null $link
     * @param null $class
     * @param null $counter
     * @param null $extra_html
     */
    public function __construct($title, $link=null, $class=null, $counter=null, $extra_html=null, $display=null) {
        $this->title = $title;
        $this->link = $link;
        $this->class = $class;
        $this->counter = $counter;
        $this->extra_html = $extra_html;
        if ($display === null)
            $this->display = true;
        else
            $this->display = $display;
    }
}

/**
 * Recursively generates multilevel menus
 * @param MenuItem[]|array $menu_items
 */
function create_navigation_menu_items($menu_items) {
    $current_script_name = basename($_SERVER["SCRIPT_FILENAME"]);
    foreach ($menu_items as $item) {
        if ($item['parent']->display) {
            $class_list = 'menu-item ' .
                ($current_script_name == $item['parent']->link ? 'current-menu-item ' : '') .
                (isset($item['child']) ? 'menu-item-has-children ' : '') .
                $item['parent']->class;

            $link = (isset($item['parent']->link) ? ' href="' . ROOT . $item['parent']->link . '" ' : '');

            echo '<li id="" class="' . $class_list . '">';
                echo '<a' . $link . '>' . $item['parent']->title . '</a>';

                // extras html
                if (isset($item['parent']->extra_html)) echo $item['parent']->extra_html;

                // Recursively create children in sub-menu
                if (isset($item['child'])) {
                    echo '<ul class="sub-menu">';
                    create_navigation_menu_items($item['child']);
                    echo '</ul>';
                }

            echo '</li>';
        }
    }
}

//            echo $_SERVER['SERVER_NAME'].'<br>';
//            echo $_SERVER['PHP_SELF'].'<br>';
//            echo $_SERVER['SCRIPT_NAME'].'<br>';
//            echo $_SERVER['REQUEST_URI'].'<br>';
//            echo dirname(__FILE__).'<br>';
//            echo __DIR__.'<br>';
//            echo '//'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'<br>';
//            echo basename($_SERVER["SCRIPT_FILENAME"]);
