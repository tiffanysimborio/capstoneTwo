<?php 

class Contact_Controller extends Base_Controller {

    // Buttons above the main content
    public $item_buttons = null;

    // Buttons above the main content
    public $submenu = null;

    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();

        $this->submenu = Navigation::submenu('inventory');

        // Generating buttons
        $this->item_buttons = Navigation::item_buttons()
                                ->add_item_button(array(
                                    'icon' => 'icon-plus-sign',
                                    'link' => 'contact@add',
                                    'text' => __('site.add_contact')))
                                ->get_item_buttons();
    }


    /**
     * Category index
     * @return redirect Redirecting to contact list
     */
    public function get_index()
    {
        return Redirect::to_action('contact@list');
    }


    /**
     * Get contact list
     * @return view Contact list
     */
    public function get_list()
    {
        if( ! Auth::can('view_contacts'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('site@status');
        }

        return View::make('layout.index')
                ->nest('header', 'layout.blocks.header', array(
                    'submenu' => $this->submenu,
                ))
                ->nest('main', 'contact.list', array(
                    'list' => $this->fetch_contacts(),
                    'status' => $this->status,
                    'item_buttons' =>  $this->item_buttons
                ));
    }


    /**
     * Add contact page
     * @return Response
     */
    public function get_add()
    {
        if( ! Auth::can('add_contacts'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('contacts@list');
        }

        return View::make('layout.index')
                        ->nest('header', 'layout.blocks.header', array(
                            'submenu' => $this->submenu,
                        ))
                        ->nest('main', 'contact.add', array(
                            'status' => $this->status,
                            'item_buttons' =>  $this->item_buttons
                    ));
    }


    /**
     * Add contact Form submission
     * @return Response
     */
    public function post_add()
    {
        if( ! Auth::can('add_contacts'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('contacts@list');
        }

        if(Input::get('submit'))
        {
            $rules = array(
                'name'          => 'required|max:200',
            );

            $input = Input::all();

            $validation = Validator::make($input, $rules);

            if($validation->fails())
            {
                Vsession::cadd('r',  $validation->errors->first())->cflash('status');
            }
            else
            {
                $items['name']          = Input::get('name');
                $items['description']   = Input::get('description');

                foreach ($items as $key => $value)
                {
                    $items[$key] = ($value !== '') ? trim(filter_var($value, FILTER_SANITIZE_STRING)) : null;
                }


                try
                {
                    $date = new \DateTime;

                    $id = DB::table('contacts')->insert_get_id(array(
                                            'name'          => $items['name'],
                                            'description'   => $items['description'],
                                            'created_at'    => $date,
                                            'updated_at'    => $date
                    ));
                }
                catch(Exception $e)
                {
                    Vsession::cadd('r', __('site.st_contact_not_saved'))->cflash('status');
                    return Redirect::to_action('contact@add');
                }

                Vsession::cadd('g', __('site.st_contact_saved'))->cflash('status');
                return Redirect::to_action('contact@add');
            }
        }

        return View::make('layout.index')
                        ->nest('header', 'layout.blocks.header', array(
                            'submenu' => $this->submenu,
                        ))
                        ->nest('main', 'contact.add', array(
                            'status' => $this->status,
                            'item_buttons' =>  $this->item_buttons 
                        ));
    }


    /**
     * Add contact page
     * @return Response
     */
    public function get_edit($id = null)
    {
        if( ! Auth::can('edit_contacts'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('contacts@list');
        }

        if($id !== null)
        {
            $id = trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT));
        }
        else
        {
            Redirect::to_action('contact@list');
        }

        if(null === $contact = $this->fetch_contact($id))
        {
            return Redirect::to_action('contact@list');
        }

        // Generating buttons
        $this->item_buttons = Navigation::item_buttons()
                                ->reset_item_buttons()
                                ->add_item_button(array(
                                    'icon'  => 'icon-minus-sign icon-white',
                                    'link'  => 'contact@delete/'.$id,
                                    'text'  => __('site.delete_contact'),
                                    'class' => 'btn-danger delete',
                                ))
                                ->get_item_buttons();

        return View::make('layout.index')
                        ->nest('header', 'layout.blocks.header', array(
                            'submenu' => $this->submenu,
                        ))
                        ->nest('main', 'contact.edit', array(
                            'contact' => $contact,
                            'status' => $this->status,
                            'item_buttons' =>  $this->item_buttons
                    ));
    }

    
    /**
     * Edit contact Form submission
     * @return Response
     */
    public function post_edit($id = null)
    {

        if( ! Auth::can('edit_contacts'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('contacts@list');
        }

        if(Input::get('submit'))
        {
            // ID
            if($id !== null)
            {
                $id = trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT));
            }
            else
            {
                Redirect::to_action('contact@list');
            }

            $rules = array(
                'name'          => 'required|max:200',
            );

            $input = Input::all();

            $validation = Validator::make($input, $rules);

            if($validation->fails())
            {
                Vsession::cadd('r',  $validation->errors->first())->cflash('status');
            }
            else
            {
                $items['name']          = Input::get('name');
                $items['description']   = Input::get('description');

                foreach ($items as $key => $value)
                {
                    $items[$key] = ($value !== '') ? trim(filter_var($value, FILTER_SANITIZE_STRING)) : null;
                }

                try
                {
                    $date = new \DateTime;

                    DB::table('contacts')
                        ->where_id($id)
                        ->update(array(
                            'name'          => $items['name'],
                            'description'   => $items['description'],
                            'created_at'    => $date,
                            'updated_at'    => $date
                    ));
                }
                catch(Exception $e)
                {
                    Vsession::cadd('r', __('site.st_contact_not_saved'))->cflash('status');
                    return Redirect::to_action('contact@add');
                }

                Vsession::cadd('g', __('site.st_contact_edited'))->cflash('status');
                return Redirect::to_action('contact@edit/' . $id);
            }
        }

        return $this->get_edit($id);
    }


    public function get_delete($id = null)
    {
        if( ! Auth::can('delete_contacts'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('contacts@list');
        }

        // ID
        if($id !== null)
        {
            $id = trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT));
        }
        else
        {
            Redirect::to_action('contact@list');
        }

        if($delete = DB::table('contacts')->delete($id))
        {
            Vsession::cadd('g', __('site.st_contact_deleted'))->cflash('status');
        }
        else
        {
            Vsession::cadd('g', __('site.st_contact_not_deleted'))->cflash('status');
        }

        return Redirect::to_action('contact@list');
    }


    /*
    * Private helper functions
    */


    /**
     * Getting contact list from DB
     * @return array Category names with ID as key
     */
    private function fetch_contacts()
    {
        $catlist = array();

        $contacts = DB::table('contacts')
                            ->get(array(
                                'contacts.id',
                                'contacts.name AS name',
                                'contacts.description',
                                'contacts.created_at'
                            ));

        return $contacts;
    }

    /**
     * Get contact data by ID
     * @param  int $id Contact ID
     * @return Response
     */
    private function fetch_contact($id)
    {
        $contact = DB::table('contacts')->where_id($id)->first();

        return $contact;
    }


}


