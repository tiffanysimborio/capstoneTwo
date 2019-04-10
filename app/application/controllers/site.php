<?php 

class Site_Controller extends Base_Controller {

    // Buttons above the main content
    public $submenu = null;

    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();

        $this->submenu = Navigation::submenu('inventory');
    }


    /**
     * Settings index
     * @return redirect Redirecting to user list
     */
    public function get_index()
    {
        return Redirect::to('/');
    }


    /**
     * Showin status messages
     * @return redirect Redirecting to user list
     */
    public function get_status()
    {
        return View::make('layout.index')
                ->nest('header', 'layout.blocks.header', array(
                    'submenu' => $this->submenu,
                ))
                ->nest('main', 'layout.status', array());
    }
}