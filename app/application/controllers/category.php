<?php 

class Category_Controller extends Base_Controller {

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
                                    'link' => 'category@add',
                                    'text' => __('site.add_category')))
                                ->get_item_buttons();
    }


    /**
     * Category index
     * @return redirect Redirecting to category list
     */
    public function get_index()
    {
        return Redirect::to_action('category@list');
    }


    /**
     * Get category list
     * @return view Category list
     */
    public function get_list()
    {
        if( ! Auth::can('view_categories'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('site@status');
        }

        return View::make('layout.index')
                ->nest('header', 'layout.blocks.header', array(
                    'submenu' => $this->submenu,
                ))
                ->nest('main', 'category.list', array(
                    'list'         => $this->fetch_categories(),
                    'status'       => $this->status,
                    'item_buttons' =>  $this->item_buttons
                ));
    }


    /**
     * Add item page
     * @return Response
     */
    public function get_add()
    {
        if( ! Auth::can('add_categories'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('categories@list');
        }

        return View::make('layout.index')
                        ->nest('header', 'layout.blocks.header', array(
                            'submenu' => $this->submenu,
                        ))
                        ->nest('main', 'category.add', array(
                            'status' => $this->status,
                            'item_buttons' =>  $this->item_buttons
                    ));
    }


    /**
     * Add item Form submission
     * @return Response
     */
    public function post_add()
    {
        if( ! Auth::can('add_categories'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('categories@list');
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
                    $id = DB::table('categories')->insert_get_id(array(
                                            'name'          => $items['name'],
                                            'description'   => $items['description']
                    ));
                }
                catch(Exception $e)
                {
                    Vsession::cadd('r',  __('site.st_item_not_saved'))->cflash('status');
                    return Redirect::to_action('category@add');
                }

                Vsession::cadd('g', __('site.st_item_saved'))->cflash('status');
                return Redirect::to_action('category@add');
            }
        }

        return $this->get_add();
    }


    /**
     * Edit category
     * @param int ID
     * @return Response
     */
    public function get_edit($id = null)
    {
        if( ! Auth::can('edit_categories'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('categories@list');
        }

        if($id !== null)
        {
            $id = trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT));
        }
        else
        {
            Redirect::to_action('category@list');
        }

        if(null === $category = $this->fetch_category($id))
        {
            return Redirect::to_action('category@list');
        }

        // Generating buttons
        $this->item_buttons = Navigation::item_buttons()
                                ->reset_item_buttons()
                                ->add_item_button(array(
                                    'icon'  => 'icon-minus-sign icon-white',
                                    'link'  => 'category@delete/'.$id,
                                    'text'  => __('site.delete_category'),
                                    'class' => 'btn-danger delete',
                                ))
                                ->get_item_buttons();

        return View::make('layout.index')
                        ->nest('header', 'layout.blocks.header', array(
                            'submenu' => $this->submenu,
                        ))
                        ->nest('main', 'category.edit', array(
                            'category' => $category,
                            'status' => $this->status,
                            'item_buttons' =>  $this->item_buttons
                    ));
    }

    
    /**
     * Edit category Form submission
     * @param int ID
     * @return Response
     */
    public function post_edit($id = null)
    {
        if( ! Auth::can('edit_categories'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('categories@list');
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
                Redirect::to_action('category@list');
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
                foreach ($input as $key => $value)
                {
                    $input[$key] = ($value !== '') ? trim(filter_var($value, FILTER_SANITIZE_STRING)) : null;
                }

                try
                {
                    $date = new \DateTime;

                    DB::table('categories')
                        ->where_id($id)
                        ->update(array(
                            'name'          => $input['name'],
                            'description'   => $input['description']
                    ));
                }
                catch(Exception $e)
                {
                    Vsession::cadd('r', __('site.st_cat_not_saved'))->cflash('status');
                    return Redirect::to_action('category@edit/' . $id);
                }

                Vsession::cadd('g', __('site.st_cat_edited'))->cflash('status');
                return Redirect::to_action('category@edit/' . $id);
            }
        }

        return $this->get_edit($id);
    }


    public function get_delete($id = null)
    {
        if( ! Auth::can('delete_categories'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('categories@list');
        }

        // ID
        if($id !== null)
        {
            $id = trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT));
        }
        else
        {
            Redirect::to_action('category@list');
        }

        if($delete = DB::table('categories')->delete($id))
        {
            Vsession::cadd('g', __('site.st_cat_deleted'))->cflash('status');
        }
        else
        {
            Vsession::cadd('g', __('site.st_cat_not_deleted'))->cflash('status');
        }

        return Redirect::to_action('category@list');
    }


    /*
    * Private helper functions
    */


    /**
     * Getting category list from DB
     * @return array Category names with ID as key
     */
    private function fetch_categories()
    {
        $catlist = array();

        $categories = DB::table('categories')
                            ->get(array('id','name', 'description'));

        return $categories;
    }


    /**
     * Get category data by ID
     * @param  int $id Contact ID
     * @return Response
     */
    private function fetch_category($id)
    {
        $category = DB::table('categories')->where_id($id)->first();

        return $category;
    }
}


