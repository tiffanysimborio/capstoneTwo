<?php

class Auth_Controller extends Base_Controller {


    public function __construct()
    {
        //parent::__construct();
    }

    /**
     * Category index
     * @return redirect Redirecting to category list
     */
    public function get_index()
    {
        return Redirect::to_action('auth@login');
    }

    public function get_login()
    {
        return View::make('layout.login');
    }


    /**
     * User Login
     * @return Response
     */
    public function post_login()
    {
        $this->filter('before','csrf');
        
        if(Input::get('login'))
        {
            $rules = array(
                'username' => 'required|max:30',
                'password' => 'required'
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
                    $input[$field] = trim(filter_var($value, FILTER_SANITIZE_STRING));
                }

                $credentials = array(
                    'username' => $input['username'],
                    'password' => $input['password']
                );

                try
                {
                    if(Auth::attempt($credentials))
                    {
                        return Redirect::to_action('item@list');
                    }
                }
                catch (Exception $e)
                {
                    //echo $e->getMessage();
                }
            }
        }

        return $this->get_login();
    }

    /**
     * User logout
     * @return Response
     */
    public function get_logout()
    {
        Auth::logout();

        return Redirect::to_action('auth@login');
    }

    /*private function get_roles()
    {
        $permissions = array(
            'view_users',
            'view_roles',
            'add_users',
            'edit_users',
            'delete_users',
            'edit_self',
            'view_categories',
            'add_categories',
            'edit_categories',
            'delete_categories',
            'view_contacts',
            'add_contacts',
            'edit_contacts',
            'delete_contacts',
            'view_settings',
            'edit_settings',
            'view_items',
            'view_item_edits',
            'add_checkin',
            'add_checkout',
            'add_items',
            'edit_items',
            'delete_items',
            'upload_item_images',
            'delete_item_images',
            'view_transactions',
            'delete_transactions',
            'edit_transactions',
        );

        $permissions_demo = array(
            'view_users',
            'view_roles',
            'view_categories',
            'add_categories',
            'edit_categories',
            'delete_categories',
            'view_contacts',
            'add_contacts',
            'edit_contacts',
            'delete_contacts',
            'view_settings',
            'view_items',
            'view_item_edits',
            'add_checkin',
            'add_checkout',
            'add_items',
            'edit_items',
            'delete_items',
            'view_transactions',
            'delete_transactions',
            'edit_transactions'
        );

        foreach ($permissions_demo as $value)
        {
            $obj = DB::table('permissions')
                ->where_name($value)
                ->first('id');

                DB::table('permission_role')
                ->insert(array('role_id' => 12, 'permission_id' => $obj->id));
        }


        $permissions_viewer = array(
            'edit_self',
            'view_categories',
            'view_contacts',
            'view_items',
            'view_item_edits',
            'view_transactions',
        );

        foreach ($permissions_viewer as $value)
        {
            $obj = DB::table('permissions')
                ->where_name($value)
                ->first('id');

                DB::table('permission_role')
                ->insert(array('role_id' => 5, 'permission_id' => $obj->id));
        }


        $permissions_seller = array(
            'edit_self',
            'view_categories',
            'view_contacts',
            'view_items',
            'view_item_edits',
            'add_checkout',
            'upload_item_images',
            'delete_item_images',
            'view_transactions',
        );

        foreach ($permissions_seller as $value)
        {
            $obj = DB::table('permissions')
                ->where_name($value)
                ->first('id');

                DB::table('permission_role')
                ->insert(array('role_id' => 4, 'permission_id' => $obj->id));
        }


        $permissions_manager = array(
            'edit_self',
            'view_categories',
            'add_categories',
            'edit_categories',
            'delete_categories',
            'view_contacts',
            'add_contacts',
            'edit_contacts',
            'delete_contacts',
            'view_items',
            'view_item_edits',
            'add_checkin',
            'add_checkout',
            'add_items',
            'edit_items',
            'delete_items',
            'upload_item_images',
            'delete_item_images',
            'view_transactions',
            'delete_transactions',
            'edit_transactions',
        );

        foreach ($permissions_manager as $value)
        {
            $obj = DB::table('permissions')
                ->where_name($value)
                ->first('id');

                DB::table('permission_role')
                ->insert(array('role_id' => 6, 'permission_id' => $obj->id));
        }

        $permissions_admin = array(
            'view_users',
            'view_roles',
            'add_users',
            'edit_users',
            'delete_users',
            'edit_self',
            'view_categories',
            'add_categories',
            'edit_categories',
            'delete_categories',
            'view_contacts',
            'add_contacts',
            'edit_contacts',
            'delete_contacts',
            'view_settings',
            'edit_settings',
            'view_items',
            'view_item_edits',
            'add_checkin',
            'add_checkout',
            'add_items',
            'edit_items',
            'delete_items',
            'upload_item_images',
            'delete_item_images',
            'view_transactions',
            'delete_transactions',
            'edit_transactions',
        );

        foreach ($permissions_admin as $value)
        {
            $obj = DB::table('permissions')
                ->where_name($value)
                ->first('id');

                DB::table('permission_role')
                ->insert(array('role_id' => 2, 'permission_id' => $obj->id));
        }

    }*/


}

?>