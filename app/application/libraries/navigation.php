<?php 

class Navigation {


    private static $item_buttons = array();

    private static $_instance = null;


    private static function factory()
    {
        if (self::$_instance === null)
        {
            self::$_instance = new self;
        }

        return self::$_instance;
    }


    public static function item_buttons()
    {
        return self::factory();
    }

    public function add_item_button($button)
    {
        $icon  = array_key_exists('icon', $button) ? $button['icon'] : null;
        $link  = array_key_exists('link', $button) ? $button['link'] : null;
        $text  = array_key_exists('text', $button) ? $button['text'] : null;
        $class = array_key_exists('class', $button) ? ' '. $button['class'] : null;

        self::$item_buttons[] = array(
                    'icon' => $icon,
                    'link' => $link,
                    'text' => $text,
                    'class' => $class
        );

        return self::factory();
    }

    public function get_item_buttons()
    {
        $buttons = array();

        foreach (self::$item_buttons as $value)
        {
            $buttons[] = '<a class="btn' . $value['class'] . '" href="' . action($value['link']) . '"><i class="' . $value['icon'] . '"></i> ' . $value['text'] . '</a>';
        }

        return implode("\r\n", $buttons);
    }

    public function reset_item_buttons()
    {
        self::$item_buttons = array();

        return self::factory();
    }

    /**
     * Submenu creation
     *     
     * @param  string $type Which set of submenu to return
     * @return string       Submenu HTML
     */
    public static function submenu($type='inventory')
    {
        $submenu = array();

        switch ($type) {
            case 'inventory':
                $submenu = array(
                    Lang::line('site.items')->get()      => 'item@list',
                    Lang::line('site.categories')->get() => 'category@list',
                    Lang::line('site.contacts')->get()   => 'contact@list'
                );
                break;

            case 'transactions':
                $submenu = array(
                    Lang::line('site.overview')->get()      => 'transaction@list/overview',
                    Lang::line('site.advanced_view')->get() => 'transaction@list/advanced',
                );
                break;

            case 'report':
                $submenu = array(
                    Lang::line('site.volume')->get()      => 'report@volume',
                    Lang::line('site.value')->get() => 'report@value',
                );
                break;
            
            default:
                $submenu = array();
                break;
        }

        return $submenu;
    }

}

?>