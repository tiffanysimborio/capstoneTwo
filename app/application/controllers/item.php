<?php

class Item_Controller extends Base_Controller {

    // Buttons above the main content
    public $item_buttons = null;

    // Buttons above the main content
    public $submenu = null;


    public function __construct()
    {
        parent::__construct();

        $this->submenu = Navigation::submenu('inventory');
        

        // Registering unique category validator
        Validator::register('cat_exists', function($attribute, $value, $parameters)
        {
            if($value == 'null') return true;

            $category = DB::table('categories')->where_id($value)->first();
            
            if($category !== null) return true;
        });

        // Registering unique item code validator
        Validator::register('code_unique', function($attribute, $value, $parameters = array())
        {
            $id = (!empty($parameters)) ? $parameters[0] : null;

            $existing_item = DB::table('items')->where_code($value)->first('code');

            if($existing_item !== null)
            {
                if($id === null)
                {
                    return false;
                }

                $current_item  = DB::table('items')->where_id($id)->first('code');

                if($current_item->code == $value)
                {
                    return true;
                }
            }
            elseif($existing_item == null)
            {
                return true;
            }
        });
    }


    /**
     * Product index
     * @return redirect Redirecting to product list
     */
    public function get_index()
    {
        return Redirect::to_action('item@list');
    }


    /**
     * Listing items
     * @return Response
     */
    public function get_list()
    {
        if( ! Auth::can('view_items'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('site@status');
        }

        Asset::script('jquijs', 'app/assets/js/tooltip-image.js', 'jquery');

        // Generating buttons
        $this->item_buttons = Navigation::item_buttons()
                                ->add_item_button(array(
                                    'icon' => 'icon-arrow-down',
                                    'link' => 'item@checkin',
                                    'text' => __('site.check_in_item')
                                ))
                                ->add_item_button(array(
                                    'icon' => 'icon-arrow-up',
                                    'link' => 'item@checkout',
                                    'text' => __('site.check_out_item')
                                ))
                                ->add_item_button(array(
                                    'icon' => 'icon-plus-sign',
                                    'link' => 'item@add',
                                    'text' => __('site.add_item')
                                ))
                                ->get_item_buttons();

        /* Item list */
        $list = DB::table('items')
                    ->left_join('categories', 'items.categories_id', '=', 'categories.id')
                    ->get(array('items.id','items.name','items.code','categories.name AS category','items.quantity','items.location'));

        return View::make('layout.index')
                    ->nest('header', 'layout.blocks.header', array(
                        'submenu' => $this->submenu,
                    ))
                    ->nest('main', 'item.list', array(
                        'list'   => $list,
                        'status' => $this->status,
                        'item_buttons' =>  $this->item_buttons
                    ));
    }


    /**
     * Add item page
     * @return Response
     */
    public function get_add()
    {
        if( ! Auth::can('add_items'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('item@list');
        }

        return View::make('layout.index')
                    ->nest('header', 'layout.blocks.header', array(
                        'submenu' => $this->submenu,
                    ))
                    ->nest('main', 'item.add', array(
                        'categories' => $this->list_categories(),
                        'item_buttons' =>  $this->item_buttons
                    ));
    }


    /**
     * Add item Form submission
     * @return Response
     */
    public function post_add()
    {
        if( ! Auth::can('add_items'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('item@list');
        }

        if(Input::get('submit'))
        {
            $rules = array(
                'name'          => 'required|max:200',
                'category'      => 'required|cat_exists',
                'code'          => 'required|max:50|code_unique',
                'buying_price'  => 'numeric',
                'selling_price' => 'numeric',
                'location'      => 'max:200'
            );

            if (Auth::can('upload_item_images')) $rules['image'] = 'image|mimes:jpg,png,gif';

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

                $date = new \DateTime;

                $id = DB::table('items')->insert_get_id(array(
                                        'name'          => $input['name'],
                                        'categories_id' => $input['category'],
                                        'code'          => $input['code'],
                                        'buying_price'  => $input['buying_price'],
                                        'selling_price' => $input['selling_price'],
                                        'location'      => $input['location'],
                                        'description'   => $input['description'],
                                        'created_at'    => $date,
                                        'updated_at'    => $date
                ));

                if(Auth::can('upload_item_images'))
                {
                    if(is_numeric($id) && Input::file('image.name') !== '')
                    {
                        $path = Config::get('application.upload_path') . DS . 'images' . DS . 'items' . DS .
                                $id . '.' . File::extension(Input::file('image.name'));

                        // Starting resizer
                        Bundle::start('resizer');

                        $success = Resizer::open(Input::file('image'))
                            ->resize( 500 , 500 , 'crop' )
                            ->save( $path , 90 );
                    }
                }

                Vsession::cadd('g', __('site.st_item_saved'))->cflash('status');

                return Redirect::to_action('item@add');
            }
        }

        return $this->get_add();
    }


    /**
     * Add item page
     * @return Response
     */
    public function get_edit($id = null)
    {
        if( ! Auth::can('view_item_edits'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('item@list');
        }

        if(!$id ||  !$this->item_exists($id, 'items'))
        {
            return Redirect::to_action('item@list');
        }

        $id = trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT));

        $this->recalculate($id);

        // Generating buttons
        $this->item_buttons = Navigation::item_buttons()
                                ->add_item_button(array(
                                    'icon'  => 'icon-minus-sign icon-white',
                                    'link'  => 'item@delete/'.$id,
                                    'text'  => __('site.delete_item'),
                                    'class' => 'btn-danger delete',
                                ))
                                ->get_item_buttons();

        return View::make('layout.index')
                    ->nest('header', 'layout.blocks.header', array(
                        'submenu' => $this->submenu,
                    ))
                    ->nest('main', 'item.edit', array(
                        'categories'    => $this->list_categories(),
                        'item'          => $this->fetch_item('id', $id),
                        'item_buttons'  =>  $this->item_buttons
                    ));
    }

    /**
     * Edit item
     * @return Response
     */
    public function post_edit($id = null)
    {
        if( ! Auth::can('edit_items'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('item@list');
        }

        // Input ID
        if(!$id || !$this->item_exists($id, 'items'))
        {
            return Redirect::to_action('item@list');
        }

        $id = trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT));


        if(Input::get('submit'))
        {
            $rules = array(
                'name'          => 'required|max:200',
                'category'      => 'required|cat_exists',
                'code'          => 'required|max:50|code_unique:'.$id,
                'buying_price'  => 'numeric',
                'selling_price' => 'numeric',
                'location'      => 'max:200'
            );

            if (Auth::can('upload_item_images')) $rules['image'] = 'image|mimes:jpg,png,gif';

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

                $date = new \DateTime;

                DB::table('items')
                    ->where('id', '=', $id)
                    ->update(array(
                    'name'          => $input['name'],
                    'categories_id' => $input['category'],
                    'code'          => $input['code'],
                    'buying_price'  => $input['buying_price'],
                    'selling_price' => $input['selling_price'],
                    'location'      => $input['location'],
                    'description'   => $input['description'],
                    'created_at'    => $date,
                    'updated_at'    => $date
                ));

                if(Auth::can('upload_item_images'))
                {
                    if(Input::file('image') !== null && Input::file('image.name') !== '')
                    {
                        $path = Config::get('application.upload_path') . DS . 'images' . DS . 'items' . DS .
                                $id . '.' . File::extension(Input::file('image.name'));

                        // Starting resizer
                        Bundle::start('resizer');

                        $success = Resizer::open(Input::file('image'))
                            ->resize( 500 , 500 , 'crop' )
                            ->save( $path , 90 );
                    }
                }

                Vsession::cadd('g', __('site.st_item_saved'))->cflash('status');

                return Redirect::to_action('item@edit/' . $id);
            }
        }

        return $this->get_edit($id);
    }


    /**
     * Checkin item
     * @return Response
     */
    public function get_checkin($id = null)
    {
        if( ! Auth::can('add_checkin'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('item@list');
        }

        // Generating buttons
        $this->item_buttons = Navigation::item_buttons()
                                ->add_item_button(array(
                                    'icon' => 'icon-arrow-down',
                                    'link' => 'item@checkin',
                                    'text' =>  __('site.check_in_item')
                                ))
                                ->add_item_button(array(
                                    'icon' => 'icon-arrow-up',
                                    'link' => 'item@checkout',
                                    'text' =>  __('site.check_out_item')
                                ))
                                ->get_item_buttons();

        Asset::style('jquicss', 'app/assets/css/jquery-ui-1.10.2.custom.min.css', 'jquery');
        Asset::script('jquijs', 'app/assets/js/jquery-ui-1.10.2.custom.js', 'jquery');
        Asset::script('datepicker', 'app/assets/js/jquery.ui.datepicker-' . Config::get('application.language') . '.js', 'jquery');


        if($id != null)
        {
            $id = trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT));
            $item = $this->fetch_item('id', $id);
        }
        else
        {
            $item = null;
        }


        return View::make('layout.index')
                    ->nest('header', 'layout.blocks.header', array(
                        'submenu' => $this->submenu,
                    ))
                    ->nest('main', 'item.checkin', array(
                        'item' => $item,
                        'status' => $this->status,
                        'item_buttons' =>  $this->item_buttons
                    ));
    }


    /**
     * Post checkin item
     * @return Response
     */
    public function post_checkin($id = null)
    {
        if( ! Auth::can('add_checkin'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('item@list');
        }

        if(Input::get('submit'))
        {

            // Registering existing code validator
            Validator::register('code_exists', function($attribute, $value, $parameters)
            {
                $code = trim(filter_var($value, FILTER_SANITIZE_STRING));

                $code = DB::table('items')->where_code($code)->first('id');
                
                if($code !== null) return true;
            });

            $rules = array(
                'code'          => 'required|max:50|code_exists',
                'buying_price'  => 'numeric',
                'quantity'      => 'required|numeric',
                'contact'       => 'max:200',
                'date'          => 'max:13'
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

                // If User inserted a source
                if($input['contact'] !== null)
                {
                    // Get Contact basic data
                    $contact = $this->fetch_contact('name', $input['contact'], 'id');

                    // If no Contact in DB, creating one
                    if($contact == null)
                    {
                        $input['contact'] = $this->insert_contact($input['contact']);
                    }
                    else
                    {
                        $input['contact'] = $contact->id;
                    }
                }


                // ID
                if($id != null)
                {
                    $id = trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT));
                    $item = $this->fetch_item('id',$id);
                }
                else
                {
                    $item = $this->fetch_item('code', $input['code']);
                }


                if($item !== null)
                {
                    if(is_numeric($item->quantity))
                    {
                        $input['quantity']     = abs($input['quantity']);
                        $input['buying_price'] = abs($input['buying_price']);

                        $input['new_quantity'] = (int) $item->quantity + (int) $input['quantity'];
                        $input['new_price']    = (int) $item->cost_total + ((int) $input['quantity'] * (int) $input['buying_price']);

                        try
                        {
                            $date = new \DateTime;

                            DB::connection()->pdo->beginTransaction();
                            DB::table('transactions')
                                ->insert(array(
                                    'items_id'              => $item->id,
                                    'transactions_types_id' => 2,
                                    'date'                  => date("Y-m-d H:i:s", strtotime($input['date'])),
                                    'contacts_id'           => $input['contact'],
                                    'users_id'              => Auth::user()->id,
                                    'quantity'              => $input['quantity'],
                                    'price'                 => $input['buying_price'],
                                    'sum'                   => 0 - ($input['quantity'] * $input['buying_price']),
                                    'note'                  => $input['note'],
                                    'created_at'            => $date
                                ));
                            DB::table('items')
                                ->where('id', '=', $item->id)
                                ->update(array(
                                    'cost_total' => $input['new_price'],
                                    'quantity'     => $input['new_quantity']
                                ));
                            DB::connection()->pdo->commit();
                        }
                        catch(PDOException $e)
                        {
                            DB::connection()->pdo->rollBack();
                            Vsession::cadd('r', __('site.st_inserting_error'))->cflash('status');
                        }

                        Vsession::cadd('g',  __('site.st_trans_inserting_ok'))->cflash('status');
                    }
                }
            }
        }
        return $this->get_checkin($id);
    }


    /**
     * Checkout item
     * @return Response
     */
    public function get_checkout($id = null)
    {
        if( ! Auth::can('add_checkout'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('item@list');
        }

        // Generating buttons
        $this->item_buttons = Navigation::item_buttons()
                                ->add_item_button(array(
                                    'icon' => 'icon-arrow-down',
                                    'link' => 'item@checkin',
                                    'text' => __('site.check_in_item')
                                ))
                                ->add_item_button(array(
                                    'icon' => 'icon-arrow-up',
                                    'link' => 'item@checkout',
                                    'text' => __('site.check_out_item')
                                ))
                                ->get_item_buttons();

        Asset::style('jquicss', 'app/assets/css/jquery-ui-1.10.2.custom.min.css', 'jquery');
        Asset::script('jquijs', 'app/assets/js/jquery-ui-1.10.2.custom.js', 'jquery');
        Asset::script('datepicker', 'app/assets/js/jquery.ui.datepicker-' . Config::get('application.language') . '.js', 'jquery');

        if($id != null)
        {
            $id = trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT));
            $item = $this->fetch_item('id', $id);
        }
        else
        {
            $item = null;
        }


        return View::make('layout.index')
                    ->nest('header', 'layout.blocks.header', array(
                        'submenu' => $this->submenu,
                    ))
                    ->nest('main', 'item.checkout', array(
                        'item' => $item,
                        'status' => $this->status,
                        'item_buttons' =>  $this->item_buttons
                    ));
    }


    /**
     * Post checkout item
     * @return Response
     */
    public function post_checkout($id = null)
    {
        if( ! Auth::can('add_checkout'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('item@list');
        }

        if(Input::get('submit'))
        {

            // Registering unique code validator
            Validator::register('code_exists', function($attribute, $value, $parameters)
            {
                $code = trim(filter_var($value, FILTER_SANITIZE_STRING));

                $code = DB::table('items')->where_code($code)->first('id');
                
                if($code !== null) return true;
            });

            // All the rules
            $rules = array(
                'code'          => 'required|max:50|code_exists',
                'selling_price' => 'numeric',
                'quantity'      => 'required|numeric',
                'contact'       => 'max:200',
                'date'          => 'max:13'
            );

            $input = Input::all();
            $validation = Validator::make($input, $rules);

            // Validating
            if($validation->fails())
            {
                Vsession::cadd('r',  $validation->errors->first())->cflash('status');
            }
            elseif (false === $this->check_stock(trim(filter_var($input['code'], FILTER_SANITIZE_STRING)), $input['quantity']))
            {
                Vsession::cadd('r',  __('site.st_inventory_out'))->cflash('status');
            }
            else
            {
                // Sanitizing trough all the Inputs
                foreach ($input as $key => $value)
                {
                    $input[$key] = ($value !== '') ? trim(filter_var($value, FILTER_SANITIZE_STRING)) : null;
                }

                // User inserted a name into the source field
                if($input['contact'] !== null)
                {
                    // Is there a contact in Database?
                    $contact = $this->fetch_contact('name', $input['contact'], 'id');
                    
                    if($contact == null) // Nope
                    {
                        $input['contact'] = $this->insert_contact($input['contact']);
                    }
                    else // Yes
                    {
                        $input['contact'] = $contact->id;
                    }
                }

                // Getting all the Item data
                // based on either the ID or the Code
                if($id != null)
                {
                    $id = trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT));
                    $item = $this->fetch_item('id', $id);
                }
                else
                {
                    $item = $this->fetch_item('code', $input['code']);
                }

                // So we have the Item data
                if($item !== null) 
                {
                    if(is_numeric($item->quantity))
                    {
                        $input['quantity']      = abs($input['quantity']);
                        $input['selling_price'] = abs($input['selling_price']);

                        $input['new_quantity'] = (int) $item->quantity - (int) $input['quantity']; // Total quantity
                        $input['new_price']    = (int) $item->income_total + ((int) $input['quantity'] * (int) $input['selling_price']); // Total price

                        try
                        {
                            $date = new \DateTime;

                            DB::connection()->pdo->beginTransaction();
                            DB::table('transactions')
                                ->insert(array(
                                    'items_id'              => $item->id,
                                    'transactions_types_id' => 1,
                                    'date'                  => date("Y-m-d H:i:s", strtotime($input['date'])),
                                    'contacts_id'           => $input['contact'],
                                    'users_id'              => Auth::user()->id,
                                    'quantity'              => 0 - $input['quantity'],
                                    'price'                 => $input['selling_price'],
                                    'sum'                   => $input['quantity'] * $input['selling_price'],
                                    'note'                  => $input['note'],
                                    'created_at'            => $date
                                ));
                            DB::table('items')
                                ->where('id', '=', $item->id)
                                ->update(array(
                                    'income_total' => $input['new_price'],
                                    'quantity'     => $input['new_quantity']
                                ));
                            DB::connection()->pdo->commit();
                        }
                        catch(PDOException $e)
                        {
                            DB::connection()->pdo->rollBack();
                            Vsession::cadd('r', __('site.st_inserting_error'))->cflash('status');
                        }

                        Vsession::cadd('g',  __('site.st_trans_inserting_ok'))->cflash('status');
                    }
                }
            }
        }
        return $this->get_checkout($id);
    }


    /**
     * Deleting item
     * @param  int $id Item ID
     * @return response
     */
    public function get_delete($id = null)
    {
        if( ! Auth::can('delete_items'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('item@list');
        }

        if($id != null)
        {
            $id = trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT));
        }
        else
        {
            return Redirect::to_action('item@list');
        }

        DB::table('items')->delete($id);

        // Image
        $image = glob('uploads/images/items/' . $id . '.*');
        
        if( ! empty($image))
        {
            if(file_exists($image[0]))
            {
                $this->get_deleteimg($id);
            }
        }

        Vsession::cadd('g',  __('site.st_item_deleted'))->cflash('status');
        return Redirect::to_action('item@list');
    }


    /**
     * Deleting item image
     * @param  int $id Item ID
     * @return response
     */
    public function get_deleteimg($id = null)
    {
        if( ! Auth::can('delete_item_images'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('item@list');
        }

        if($id != null)
        {
            $id = trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT));
        }
        else
        {
            return Redirect::to_action('item@list');
        }

        $image = glob('uploads/images/items/' . $id . '.*');

        if( ! empty($image))
        {
            if(file_exists($image[0]))
            {
                File::delete($image[0]);
            }
        }

        Vsession::cadd('g',  __('site.st_image_deleted'))->cflash('status');
        return Redirect::to_action('item@edit/'.$id);
    }


    /*
    |--------------------------------------------------------------------------
    | Helper functions
    |--------------------------------------------------------------------------
    |
    | These functions are here to help the Controller and are private to it
    |
    */


    /**
     * Fetching category list
     * @return array Category names with ID as key
     */
    private function list_categories()
    {
        $catlist_null = array('null'=> __('site.select_cat'));
        $catlist = array();

        $categories = DB::table('categories')
                            ->get(array('id','name'));

        foreach ($categories as $category)
        {
            $catlist[$category->id] = $category->name;
        }

        $catlist = (array) $catlist_null + (array) $catlist;

        return $catlist;
    }


    /**
     * Fetching item data
     * @return array Item data
     */
    private function fetch_item($column, $value)
    {

        $item = DB::table('items')
                            ->where('items.'.$column, '=', $value)
                            ->left_join('categories', 'categories.id', '=', 'items.categories_id')
                            ->first(array(
                                'items.id',
                                'items.name',
                                'categories.id as category_id',
                                'categories.name as category',
                                'items.code',
                                'items.quantity',
                                'items.buying_price',
                                'items.selling_price',
                                'items.cost_total',
                                'items.income_total',
                                'items.location',
                                'items.description',
                                'items.created_at',
                                'items.updated_at'
                            ));

        return $item;
    }


    /**
     * Fetching item data
     * @return array Item data
     */
    private function fetch_item_basics($column, $value)
    {

        $item = DB::table('items')
                            ->where($column, '=', $value)
                            ->first(array(
                                'id',
                                'name',
                                'quantity',
                            ));

        return $item;
    }

    /**
     * Get contact item data
     * @return array Item data
     */
    private function fetch_contact($column, $value, $data)
    {

        $contact = DB::table('contacts')
                            ->where($column, '=', $value)
                            ->first(array(
                                $data
                            ));

        return $contact;
    }

    /**
     * Insert contact by name
     * @return array Item data
     */
    private function insert_contact($name)
    {
        $date = new \DateTime;

        $contact = DB::table('contacts')
                            ->insert_get_id(array(
                                'name'       => $name,
                                'updated_at' => $date,
                                'created_at' => $date
                            ));

        return $contact;
    }


    /**
     * Fetching item by Id
     * @return array Item data
     */
    private function item_exists($id, $table)
    {
        $item = DB::table($table)->find($id);

        return ($item !== null) ? true : false;
    }


    /**
     * Fetching transactions by item Id
     * @return array Item transactions
     */
    private function fetch_transactions($id)
    {
        $transactions = DB::table('transactions')
                            ->where('transactions.items_id', '=', $id)
                            ->left_join('transaction_types', 'transaction_types.id', '=', 'transactions.transactions_types_id')
                            ->left_join('contacts', 'transactions.contacts_id', '=', 'contacts.id')
                            ->get(array(
                                'transactions.id',
                                'transactions.date',
                                'transaction_types.name as type',
                                'contacts.name as contact',
                                'transactions.quantity',
                                'transactions.price',
                                'transactions.note'
                            ));

        return $transactions;
    }


    /**
     * Checking if enough products are aviable to substract from
     * @param  string $code   Product code
     * @param  int    $number Number to deduct
     * @return bool           False if fewer than 0
     */
    private function check_stock($code, $number)
    {
        $current_stock = DB::table('items')->where_code($code)->first('quantity');

        if(($current_stock->quantity - $number) >= 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Recalculating total stock
     * @param  int $id Item ID
     * @return         Respose
     */
    private function recalculate($id = null)
    {
        try
        {
            $item                = DB::table('items')->where_id($id)->first(array('quantity', 'cost_total', 'income_total'));
            $transactions_stock  = DB::table('transactions')->where_items_id($id)->sum('quantity');
            $transactions_cost   = DB::table('transactions')->where_items_id($id)->where('transactions_types_id', '=', '2')->sum('sum');
            $transactions_income = DB::table('transactions')->where_items_id($id)->where('transactions_types_id', '=', '1')->sum('sum');
        }
        catch (Exception $e)
        {
            return false;
        }

        if($item == null)
        {
            return false;
        }

        $transactions_stock = (is_null($transactions_stock)) ? 0 : $transactions_stock;
        $transactions_cost = (is_null($transactions_cost)) ? 0 : $transactions_cost;
        $transactions_income = (is_null($transactions_income)) ? 0 : $transactions_income;

        if($item->quantity !==  $transactions_stock)
        {
            try
            {
                DB::table('items')->where_id($id)->update(array('quantity' => $transactions_stock));
            }
            catch (Exception $e)
            {
                return false;
            }
        }

        if($item->cost_total !== abs($transactions_cost))
        {
            try
            {
                DB::table('items')->where_id($id)->update(array('cost_total' => abs($transactions_cost)));
            }
            catch (Exception $e)
            {
                return false;
            }
        }

        if($item->income_total !== abs($transactions_income))
        {
            try
            {
                DB::table('items')->where_id($id)->update(array('income_total' => abs($transactions_income)));
            }
            catch (Exception $e)
            {
                return false;
            }
        }

        return true;
    }
}
