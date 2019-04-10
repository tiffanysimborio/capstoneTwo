<?php 

class Setting_Controller extends Base_Controller {

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
        return Redirect::to_action('setting@site');
    }


    /**
     * Site Setting index
     * @return redirect Redirecting to user list
     */
    public function get_site()
    {
        if( ! Auth::can('edit_settings'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('site@status');
        }

        return View::make('layout.index')
                    ->nest('header', 'layout.blocks.header', array(
                        'submenu' => $this->submenu,
                    ))
                    ->nest('main', 'setting.site', array(
                    ));
    }


    /**
     * Site Setting post
     * @return redirect Redirecting to user list
     */
    public function post_site()
    {
        if( ! Auth::can('edit_settings'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('site@status');
        }
        
        if(Input::get('submit'))
        {   
            // Registering language validator
            Validator::register('language_exists', function($attribute, $value, $parameters)
            {
                if(array_key_exists($value, Config::get('site.languages'))) return true;
            });

            // So these are the rules
            $rules = array(
                'language'      => 'required|language_exists'
            );

            $input = Input::all();

            $validation = Validator::make($input, $rules);

            if($validation->fails())
            {
                Vsession::cadd('r',  $validation->errors->first())->cflash('status');
            }
            else
            {
                foreach ($input as $field => $value)
                {
                    if(! empty($value))
                    {
                        $value = trim(filter_var($value, FILTER_SANITIZE_STRING));

                        DB::table('settings')
                            ->where_field($field)
                            ->take(1)
                            ->update(array('value' => $value));
                    }
                }

                Vsession::cadd('g', __('site.st_settings_up'))->cflash('status');

                return Redirect::to_action('setting@site');
            }
        }

        return $this->get_site();
    }
}