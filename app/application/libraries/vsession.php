<?php

class Vsession {

    public static $cmessage = array();

    private static $_instance = null;

    /**
     * Add messages to que
     *
     * Add messages to flash session que,
     * the input color determines the color of the
     * output status messages on page
     * 
     * @param  string $color   Color to be displayed (r,g,b,y)
     * @param  string $message The message to be displayed
     * @return self            Instance of self for method chaining
     */
    public static function cadd($color='y', $message)
    {
        self::$cmessage[$color][] = $message;
        
        if (self::$_instance === null)
        {
            self::$_instance = new self;
        }

        return self::$_instance;
    }


    /**
     * Initialize flash session with message que
     * @param  string $name Name of the session flash
     */
    public static function cflash($name)
    {
        Session::flash($name, self::$cmessage);
    }


    /**
     * Generate the HTML alert box
     *
     * This method generates the HTML alert boxes
     * based on the session flashes content,
     * which describes what color they need to be
     * 
     * @param  string $name Name of the sesion flash
     */
    public static function cprint($name)
    {
        if(Session::get($name) == null)
        {
            echo '';
            return false;
        }

        foreach (Session::get($name) as $color => $messages)
        {
            switch ($color)
            {
                case 'y':
                    $class = 'alert';
                    break;

                case 'r':
                    $class = 'alert alert-error';
                    break;

                case 'g':
                    $class = 'alert alert-success';
                    break;

                case 'b':
                    $class = 'alert alert-info';
                    break;
                
                default:
                    $class = 'alert';
                    break;
            }

            foreach ($messages as $message)
            {
                echo '<p class="' . $class . '">' . $message . '</p>';
            }
        }

        Session::flash($name, null);
    }

}