<?php 

class Ajax_Controller extends Base_Controller {

    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();

        if ( ! Request::ajax())
        {
            die();
        }
    }

    public function get_contacts($term = null)
    {
        $names = null;

        $term = Input::get('term');
        $term = trim(filter_var($term, FILTER_SANITIZE_STRING));

        $like = '%' . $term . '%';
        
        $contacts = DB::table('contacts')
        ->where('name', 'LIKE', $like)
        ->take(10)
        ->get('name');

        foreach ($contacts as $contact) {
            $names[] = $contact->name;    
        }

        echo json_encode($names);
    }


    public function get_checkin_fields($code=null)
    {
        $data = array();

        $code = Input::get('code');
        $code = trim(filter_var($code, FILTER_SANITIZE_STRING));

        $items = DB::table('items')
        ->where('code', '=', $code)
        ->first();

        if($items == null)
        {
            exit;
        }

        foreach ($items as $key => $value) {
            $data[$key] = $value;    
        }

        echo json_encode($data);
    }

    /**
     * Fetch transaction basics for transactions list
     * 
     * @param  int $id   Item id (optional)
     * @return JSON
     */
    public function get_transaction_overview($id = null)
    {
        $table = 'transactions';

        $iid = 'items_id';

        $columns = array(
            'transactions.id',
            'transactions.date',
            'transaction_types.type',
            'items.name as item',
            'transactions.quantity',
            'transactions.price',
            'transactions.items_id as iid',
        );

        $joins = array(
            array(
                'table' => 'transaction_types',
                'tcolumn' => 'transactions.transactions_types_id',
                'fcolumn' => 'transaction_types.id'),
            array(
                'table' => 'items',
                'tcolumn' => 'transactions.items_id',
                'fcolumn' => 'items.id'
        ));

        $input = Input::all();

        $transactions = $this->fetch_transactions($table, $id, $iid, $columns, $joins, $input);

        if(!is_null($transactions)) echo $transactions;
    }


    /**
     * Fetch transaction advanced list for wide transaction layout
     * 
     * @param  int $id   Item id (optional)
     * @return JSON
     */
    public function get_transaction_advanced($id = null)
    {
        $table = 'transactions';

        $iid = 'items_id';

        $columns = array(
            'transactions.id',
            'items.code as code',
            'transactions.date',
            'transaction_types.type',
            'contacts.name as contact',
            'items.name as item',
            'transactions.note',
            'users.name as user',
            'transactions.quantity',
            'transactions.price',
            'transactions.items_id as iid',
        );

        $joins = array(
            array(
                'table' => 'transaction_types',
                'tcolumn' => 'transactions.transactions_types_id',
                'fcolumn' => 'transaction_types.id'),
            array(
                'table' => 'contacts',
                'tcolumn' => 'transactions.contacts_id',
                'fcolumn' => 'contacts.id'),
            array(
                'table' => 'items',
                'tcolumn' => 'transactions.items_id',
                'fcolumn' => 'items.id'),
            array(
                'table' => 'users',
                'tcolumn' => 'transactions.users_id',
                'fcolumn' => 'users.id')
        );

        $input = Input::all();

        $transactions = $this->fetch_transactions($table, $id, $iid, $columns, $joins, $input);

        if(!is_null($transactions)) echo $transactions;
    }


    /**
     * Fetch items list
     * @return JSON
     */
    public function get_item_basic()
    {
        $table = 'items';

        $columns = array(
            'items.code',
            'items.name',
            'categories.name as category',
            'items.location',
            'items.quantity',
            'items.id',
        );

        $joins = array(
            array(
                'table' => 'categories',
                'tcolumn' => 'items.categories_id',
                'fcolumn' => 'categories.id'
        ));

        $input = Input::all();

        $items = $this->fetch_items($table, $columns, $joins, $input);

        if(!is_null($items)) echo $items;
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
     * Fetch transactions for Datatables
     * @param  string $table    Table to be fetched from
     * @param  int $id          Item id (optional)
     * @param  string $iid      Item id column name in target table
     * @param  array $columns   Columns to be fetched
     * @param  array  $joins    2D array wih 3 join parametes each array
     * @param  array $input     All the environmental inputs
     * @return string           JSON data of query
     */
    private function fetch_transactions($table=null, $id=null, $iid=null, $columns=null, $joins=array(), $input)
    {
        if(empty($input)) return false;

        $limit = null;
        $offset = null;
        $order = array();
        $where = array();

        $id = ( !is_null($id)) ? trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT)) : null;

        foreach ($input as $key => $value)
        {
            $input[$key] = trim(filter_var($value, FILTER_SANITIZE_STRING));
        }

        // Column name without the as aliases
        // needed in this form for Filters and Column search
        $columns_no_as = array();

        foreach ($columns as $value)
        {
            $columns_no_as[] = preg_replace('/\sas\s[a-z]+/', '', $value);
        }
        
        // Limit and offset
        if (!is_null( $input['iDisplayStart']) && $input['iDisplayLength'] != '-1' )
        {
            $offset = (int) $input['iDisplayStart'];
            $limit  = (int) $input['iDisplayLength'];
        }

        // Order
        if ( isset( $input['iSortCol_0'] ) ) // which column sorts
        {
            for ( $i=0 ; $i<$input['iSortingCols'] ; $i++ ) // how many sort
            {
                if ( $input[ 'bSortable_'.intval($input['iSortCol_'.$i]) ] == "true" )
                {
                    $order[] = array(
                        'col' => $columns_no_as[$input['iSortCol_'.$i]],
                        'dir' => $input['sSortDir_'.$i]==='asc' ? 'asc' : 'desc'
                    );
                }
            }
        }

        // Id
        if( !is_null($id)) 
        {
            $where[] = array(
                'col'  => $iid,
                'comp' => '=',
                'val'  => $id
            );
        }

        //Filters
        if ( isset($input['sSearch']) && $input['sSearch'] != "" )
        {
            for ( $i=0 ; $i<count($columns) ; $i++ )
            {
                $where[] = array(
                    'col'  => $columns_no_as[$i],
                    'comp' => 'LIKE',
                    'val'  => '%'.$input['sSearch'].'%'
                );
            }
        }

        // Column search
        for ( $i=0 ; $i<count($columns) ; $i++ )
        {
            if ( isset($input['bSearchable_'.$i]) && $input['bSearchable_'.$i] == "true" && $input['sSearch_'.$i] != '' )
            {
                $where[] = array(
                    'col'  => $columns_no_as[$i],
                    'comp' => 'LIKE',
                    'val'  => '%'.$input['sSearch_'.$i].'%'
                );
            }
        }


        
        try
        {
            // Putting together the query
            $db = DB::table($table);

            $total  = $db->count();

            foreach ($where as $value)
            {
                $db->or_where($value['col'], $value['comp'], $value['val']);
            }

            foreach ($order as $value)
            {
                $db->order_by($value['col'], $value['dir']);
            }

            foreach ($joins as $join)
            {
                $db->left_join($join['table'],$join['tcolumn'],'=',$join['fcolumn']);
            }
            
            $filtered_total  = $db->count();

            $db->take($limit);
            $db->skip($offset);

            $results = $db->get($columns);
        }
        catch (Exception $e)
        {
            return null;
        }


        $output = array(
            "sEcho" => $input['sEcho'],
            "iTotalRecords" => $total,
            "iTotalDisplayRecords" => $filtered_total,
            "aaData" => array()
        );

        $result = array();

        for ($i=0; $i < count($results); $i++)
        { 
            $id = null;

            foreach ($results[$i] as $key => $value)
            {
                if ($key == 'iid')
                {
                    continue;
                }
                elseif ($key == 'type')
                {
                    $value = Lang::line('site.'.$value)->get();
                }
                elseif ($key == 'item')
                {
                    $image = array();
                    $image = glob('uploads/images/items/' . $results[$i]->iid . '.*');
                    $image = (!empty($image) && file_exists($image[0])) ? ' &nbsp;&nbsp;<a class="screenshot" href="' . URL::base() . '/' . $image[0] . '"><i class="icon-picture opacity50"></i></a>' : '';

                    $value = '<a href="' . action('item@edit') . '/' . $results[$i]->iid . '">'. $value . '</a>' . $image;
                }
                                
                $result[$i][] = $value;
            }

            $result[$i][] = '<a href="' . action('transaction/delete/' . $results[$i]->id) . '" class="delete">' . Lang::line('site.delete')->get() . '</a>';
        }
    
        $output['aaData'] = $result;
        
        return json_encode( $output );
    }


    /**
     * Fetch items for Datatables
     * @param  string $table    Table to be fetched from
     * @param  int $id          Item id (optional)
     * @param  string $iid      Item id column name in target table
     * @param  array $columns   Columns to be fetched
     * @param  array  $joins    2D array wih 3 join parametes each array
     * @param  array $input     All the environmental inputs
     * @return string           JSON data of query
     */
    private function fetch_items($table=null, $columns=null, $joins=array(), $input)
    {
        if(empty($input)) return false;

        $limit = null;
        $offset = null;
        $order = array();
        $where = array();

        foreach ($input as $key => $value)
        {
            $input[$key] = trim(filter_var($value, FILTER_SANITIZE_STRING));
        }

        // Column name without the as aliases
        // needed in this form for Filters and Column search
        $columns_no_as = array();

        foreach ($columns as $value)
        {
            $columns_no_as[] = preg_replace('/\sas\s[a-z]+/', '', $value);
        }

        // Limit and offset
        if (!is_null( $input['iDisplayStart']) && $input['iDisplayLength'] != '-1' )
        {
            $offset = (int) $input['iDisplayStart'];
            $limit  = (int) $input['iDisplayLength'];
        }

        // Order
        if ( isset( $input['iSortCol_0'] ) ) // which column sorts
        {
            for ( $i=0 ; $i<$input['iSortingCols'] ; $i++ ) // how many sort
            {
                if ( $input[ 'bSortable_'.intval($input['iSortCol_'.$i]) ] == "true" )
                {
                    $order[] = array(
                        'col' => $columns_no_as[$input['iSortCol_'.$i]],
                        'dir' => $input['sSortDir_'.$i]==='asc' ? 'asc' : 'desc'
                    );
                }
            }
        }
        
        //Filters
        if ( isset($input['sSearch']) && $input['sSearch'] != "" )
        {
            for ( $i=0 ; $i<count($columns) ; $i++ )
            {
                $where[] = array(
                    'col'  => $columns_no_as[$i],
                    'comp' => 'LIKE',
                    'val'  => '%'.$input['sSearch'].'%'
                );
            }
        }

        // Column search
        for ( $i=0 ; $i<count($columns) ; $i++ )
        {
            if ( isset($input['bSearchable_'.$i]) && $input['bSearchable_'.$i] == "true" && $input['sSearch_'.$i] != '' )
            {
                $where[] = array(
                    'col'  => $columns_no_as[$i],
                    'comp' => 'LIKE',
                    'val'  => '%'.$input['sSearch_'.$i].'%'
                );
            }
        }

        

            // Putting together the query
            $db = DB::table($table);

            $total  = $db->count();

            foreach ($where as $value)
            {
                $db->or_where($value['col'], $value['comp'], $value['val']);
            }

            foreach ($order as $value)
            {
                $db->order_by($value['col'], $value['dir']);
            }

            foreach ($joins as $join)
            {
                $db->left_join($join['table'],$join['tcolumn'],'=',$join['fcolumn']);
            }
            
            $filtered_total  = $db->count();

            $db->take($limit);
            $db->skip($offset);

            $results = $db->get($columns);
        try
        {    
        }
        catch (Exception $e)
        {
            return null;
        }


        $output = array(
            "sEcho" => $input['sEcho'],
            "iTotalRecords" => $total,
            "iTotalDisplayRecords" => $filtered_total,
            "aaData" => array()
        );

        $result = array();

        for ($i=0; $i < count($results); $i++)
        {
            $id = null;

            foreach ($results[$i] as $key => $value)
            {
                if ($key == 'id')
                {
                    $id = $value;
                }
                elseif($key == 'name')
                {
                    $image = array();
                    $image = glob('uploads/images/items/' . $results[$i]->id . '.*');
                    $image = (!empty($image) && file_exists($image[0])) ? ' &nbsp;&nbsp;<a class="screenshot" href="' . URL::base() . '/' . $image[0] . '"><i class="icon-picture opacity50"></i></a>' : '';

                    $result[$i][] = '<a href="edit/' . $results[$i]->id . '">'. $value . '</a>' . $image;
                }
                else
                {
                    $result[$i][] = $value;
                }
            }
        }

        $output['aaData'] = $result;
        
        return json_encode( $output );
    }

    
}
