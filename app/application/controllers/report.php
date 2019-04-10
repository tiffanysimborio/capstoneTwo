<?php

class Report_Controller extends Base_Controller {

    // Buttons above the main content
    public $item_buttons = null;

    // Buttons above the main content
    public $submenu = null;

    // Item ID
    public $iid = null;

    
    /**
     * Construct
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();

        $this->submenu = Navigation::submenu('report');
    }

    /**
     * Index
     *     
     * @return void
     */
    public function get_index()
    {
        return Redirect::to_action('report@volume');
    }


    public function get_volume()
    {
        return View::make('layout.index')
                    ->nest('header', 'layout.blocks.header', array(
                        'submenu' => $this->submenu,
                    ))
                    ->nest('main', 'report.volume', array(

                    ));
    }

}