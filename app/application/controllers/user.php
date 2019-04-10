<?php 

class User_Controller extends Base_Controller {

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
                                    'link' => 'user@add',
                                    'text' => __('site.add_user')))
                                ->get_item_buttons();
    }


    /**
     * User index
     * @return redirect Redirecting to user list
     */
    public function get_index()
    {
        return Redirect::to_action('user@list');
    }


    /**
     * Get user list
     * @return view User list
     */
    public function get_list()
    {
        if( ! Auth::can('view_users'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('site@status');
        }

        return View::make('layout.index')
                ->nest('header', 'layout.blocks.header', array(
                    'submenu' => $this->submenu,
                ))
                ->nest('main', 'user.list', array(
                    'list' => $this->fetch_users(),
                    'item_buttons' =>  $this->item_buttons
                ));
    }


    /**
     * Get roles list
     * @return view Roles list
     */
    public function get_roles()
    {
        if( ! Auth::can('view_roles'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('site@status');
        }

        $roles = $this->fetch_roles();

        foreach ($roles as $role)
        {
            //$types[$role->id] = array('name'=>$role->name, 'permissions'=>array());
            $permissions[$role->id]['name'] = $role->name;
            $permissions[$role->id]['level'] = $role->level;
            $permissions[$role->id]['permissions'][] = $role->permissions;
        }

        return View::make('layout.index')
                ->nest('header', 'layout.blocks.header', array(
                    'submenu' => $this->submenu,
                ))
                ->nest('main', 'user.roles', array(
                    'permissions' => $permissions,
                    'item_buttons' =>  $this->item_buttons
                ));
    }


    /**
     * Add user page
     * @return Response
     */
    public function get_add()
    {   
        if( ! Auth::can('add_users'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('site@status');
        }

        return View::make('layout.index')
                    ->nest('header', 'layout.blocks.header', array(
                        'submenu' => $this->submenu,
                    ))
                    ->nest('main', 'user.add', array(
                        'roles' => $this->list_roles(),
                        'item_buttons' =>  $this->item_buttons
                    ));
    }


    /**
     * Add User Form submission
     * @return Response
     */
    public function post_add()
    {
        if( ! Auth::can('add_users'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('site@status');
        }

        if(Input::get('submit'))
        {
            // Registering unique role validator
            Validator::register('role_exists', function($attribute, $value, $parameters)
            {
                $category = DB::table('roles')->where_id($value)->first();
                
                if($category !== null) return true;
            });

            // So these are the rules
            $rules = array(
                'username'  => 'required|max:30|unique:users',
                'name'      => 'required|max:200',
                'password1' => 'required|max:16|same:password2',
                'password2' => 'required|max:16|same:password1',
                'email'     => 'required|email|unique:users',
                'role'      => 'required|numeric|role_exists'
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

                // Create a new User
                $user = new \Verify\Models\User;
                $user->username = $input['username'];
                $user->name     = $input['name'];
                $user->email    = $input['email'];
                $user->password = $input['password1'];
                $user->verified = 1;
                $user->save();

                // Assign the Role to the User
                $user->roles()->sync($input['role']);
                
                Vsession::cadd('g', __('site.st_user_saved'))->cflash('status');

                return Redirect::to_action('user@add');
            }
        }

        // Reusing view
        return $this->get_add();
    }


    /**
     * Edit user
     * @return Response
     */
    public function get_edit($id = null, $user = null)
    {
        if( ! Auth::can('edit_users'))
        {
            if( ! Auth::can('edit_self') || Auth::user()->id !== (int) $id)
            {
                Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
                return Redirect::to_action('site@status');
            }
        }

        // Input ID
        if($id == null || !$this->user_exists($id, 'users'))
        {
            return Redirect::to_action('user@list');
        }

        $id = trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT));

        if(is_null($user))
        {
            $user = $this->fetch_user($id);
            $user = $user[0];
        }

        // Generating buttons
        $this->item_buttons = Navigation::item_buttons()
                                ->add_item_button(array(
                                    'icon'  => 'icon-minus-sign icon-white',
                                    'link'  => 'user@delete/'.$id,
                                    'text'  => __('site.delete_user'),
                                    'class' => 'btn-danger delete',
                                ))
                                ->get_item_buttons();

        return View::make('layout.index')
                    ->nest('header', 'layout.blocks.header', array(
                        'submenu' => $this->submenu,
                    ))
                    ->nest('main', 'user.edit', array(
                        'user' => $user,
                        'roles' => $this->list_roles(),
                        'item_buttons' =>  $this->item_buttons
                    ));
    }


    /**
     * Edit user submit
     * @return Response
     */
    public function post_edit($id = null)
    {
        if( ! Auth::can('edit_users'))
        {
            if( ! Auth::can('edit_self') || Auth::user()->id !== (int) $id)
            {
                Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
                return Redirect::to_action('site@status');
            }
        }

        // Input ID
        if($id == null || !$this->user_exists($id, 'users'))
        {
            return Redirect::to_action('user@list');
        }


        if(Input::get('submit'))
        {   
            // User data
            $user = $this->fetch_user($id);
            $user = $user[0];

            // Registering unique role validator
            Validator::register('role_exists', function($attribute, $value, $parameters)
            {
                $category = DB::table('roles')->find($value);
                
                if($category !== null) return true;
            });

            // Registering unique validator to see if user Admin
            Validator::register('role_is_admin', function($attribute, $value, $parameters)
            {
                if( ! is_null($value))
                {
                    if( ! Auth::is('Admin') )
                    {
                        return false;
                    }
                }

                return true;
            });

            //If input email is own
            $mailunique = (Input::get('email') != $user->email) ? '|unique:users' : '' ;

            // So these are the rules
            $rules = array(
                'name'      => 'required|max:200',
                'password1' => 'max:16|same:password2',
                'password2' => 'max:16|same:password1',
                'email'     => 'required|email' . $mailunique,
                'role'      => 'role_is_admin|numeric|role_exists'
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

                $user        = new \Verify\Models\User;
                $user        = Auth::retrieve($id);
                $user->name  = $input['name'];
                $user->email = $input['email'];
                ( ! is_null($input['password1'])) ? $user->password = $input['password1'] : '';
                $user->save();

                if( ! is_null($input['role'])) $user->roles()->sync($input['role']);
                
                Vsession::cadd('g', __('site.st_user_up'))->cflash('status');

                return Redirect::to_action('user@edit/' . $id);
            }
        }

        // Reusing view
        return $this->get_edit($id, $user);
    }


    /**
     * Delete user
     * @param  int $id User ID
     * @return Response
     */
    public function get_delete($id = null)
    {
        if( ! Auth::can('delete_users'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('user@list');
        }

        if($id != null)
        {
            $id = trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT));
        }
        else
        {
            return Redirect::to_action('user@list');
        }

        if($id == Auth::user()->id)
        {
            Vsession::cadd('r',  __('site.st_user_urself'))->cflash('status');
            return Redirect::to_action('user@list');
        }

        if(! $this->user_exists($id, 'users'))
        {
            return Redirect::to_action('user@list');
        }

        DB::table('role_user')
            ->where('user_id', '=', $id)
            ->delete();

        DB::table('users')
            ->where('id', '=', $id)
            ->delete();

        Vsession::cadd('g',  __('site.st_user_deleted'))->cflash('status');
        return Redirect::to_action('user@list');
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
     * Getting user list from DB
     * @return array Category names with ID as key
     */
    private function fetch_users()
    {
        $users = DB::table('role_user')
                            ->join('roles', 'roles.id', '=', 'role_user.role_id')
                            ->join('users', 'users.id', '=', 'role_user.user_id')
                            ->group_by('users.username')
                            ->get(array(
                                'users.id',
                                'users.username',
                                'users.name',
                                'users.email',
                                'roles.name AS rolename',
                            ));

        return $users;
    }


    /**
     * Getting user list from DB
     * @return array Category names with ID as key
     */
    private function fetch_roles()
    {
        $roles = DB::table('permission_role')
                            ->order_by('level', 'desc')
                            ->join('roles', 'permission_role.role_id', '=', 'roles.id')
                            ->left_join('permissions', 'permission_role.permission_id', '=', 'permissions.id')
                            ->get(array(
                                'roles.id',
                                'roles.name',
                                'roles.level',
                                'permissions.description',
                                'permissions.name as permissions',
                            ));
        return $roles;
    }


    private function list_roles()
    {
        $list = array();

        $roles = $this->fetch_roles();

        foreach ($roles as $role)
        {
            $list[$role->id] = $role->name;
        }

        return $list;
    }


    /**
     * Fetching user by Id
     * @return bool
     */
    private function user_exists($id, $table)
    {
        $user = DB::table($table)->find($id);

        return ($user !== null || ! empty($user)) ? true : false;
    }


    /**
     * Fetching user by Id
     * @return bool
     */
    private function fetch_user($id)
    {
        $user = DB::table('role_user')
            ->where('role_user.user_id', '=', $id)
            ->left_join('users', 'role_user.user_id', '=', 'users.id')
            ->left_join('roles', 'role_user.role_id', '=', 'roles.id')
            ->take(1)
            ->get(array(
                'roles.id as role',
                'users.username',
                'users.name',
                'users.email'));

        return ($user !== null) ? $user : null;
    }
}


