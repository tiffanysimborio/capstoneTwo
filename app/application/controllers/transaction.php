<?php 

class Transaction_Controller extends Base_Controller {

    // Buttons above the main content
    public $item_buttons = null;

    // Buttons above the main content
    public $submenu = null;

    // Item ID
    public $iid = null;

    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();

        $this->submenu = Navigation::submenu('transactions');
    }


    /**
     * Category index
     * @return redirect Redirecting to transaction list
     */
    public function get_index()
    {
        return Redirect::to_action('transaction@list');
    }


    /**
     * Listing item transactions
     * @return Response
     */
    public function get_list($type = null, $iid = null)
    {
        if( ! Auth::can('view_transactions'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('item@list');
        }

        if( $iid !== null)
        {
            if(null !== $this->iid = $this->filter_item($iid))
            {
                $this->item_buttons = $this->delete_button($this->iid);
            }
            else
            {
                return Redirect::to_action('transaction@list/overview');
            }
        }

        Asset::script('jquijs', 'app/assets/js/tooltip-image.js', 'jquery');

        switch ($type) {
            case 'overview':
                return $this->get_list_view('overview');
                break;

            case 'advanced':
                return $this->get_list_view('advanced');
                break;
            
            default:
                return $this->get_list_view('overview');
                break;
        }
    }


    /**
     * Delete transaction
     * 
     * @param  int $id Transaction ID
     * @return Response
     */
    public function get_delete($tid = null)
    {
        if( ! Auth::can('delete_transactions'))
        {
            Vsession::cadd('y',  __('site.not_allowed'))->cflash('status');
            return Redirect::to_action('transaction@list');
        }

        if( ! is_null($this->filter_transaction($tid)) )
        {
            $iid = $this->item_by_transaction($tid);

            DB::table('transactions')->delete($tid);

            $this->recalculate($iid);
        }
        else
        {
            return Redirect::to_action('transaction@list');
        }

        Vsession::cadd('g',  __('site.st_trans_deleted'))->cflash('status');
        return Redirect::to_action('transaction@list');
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
     * Grabbing transaction view in given mode
     * 
     * @return View
     */
    private function get_list_view($type)
    {
        return View::make('layout.index')
                    ->nest('header', 'layout.blocks.header', array(
                        'submenu' => $this->submenu
                    ))
                    ->nest('main', 'transaction.list_' . $type, array(
                        'id'           => $this->iid,
                        'status'       => $this->status,
                        'item_buttons' => $this->item_buttons
                    ));
    }
   

   /**
    * Make delete button for Item if ID set
    * 
    * @param  int $id    Item ID
    * @return string     Button markup
    */
    private function delete_button($id)
    {
        if( ! is_null($id))
        {
            // Generating buttons
            return Navigation::item_buttons()
                ->add_item_button(array(
                    'icon'  => 'icon-minus-sign icon-white',
                    'link'  => 'item@delete/'.$id,
                    'text'  =>  __('site.delete_item'),
                    'class' => 'btn-danger delete',
                ))
                ->get_item_buttons();
        }
    }


    /**
    * Filter validate Item id
    * 
    * @param  int $id    Item ID
    * @return string     Item ID valid
    */
    private function filter_item($id = null)
    {   
        if (is_null($id)) return null;

        $id = trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT));

        if( $this->item_exists($id))
        {
            return $id;
        }

        return null;
    }


    /**
     * Searching item by Id
     * 
     * @return array Item data
     */
    private function item_exists($id = null)
    {
        $item = DB::table('items')->find($id);

        return ($item !== null) ? true : false;
    }


    /**
    * Filter validate Transaction id
    * 
    * @param  int $id    Transaction ID
    * @return string     Transaction ID valid
    */
    private function filter_transaction($id = null)
    {
        if (is_null($id)) return null;

        $id = trim(filter_var($id, FILTER_SANITIZE_NUMBER_INT));

        if( $this->transaction_exists($id))
        {
            return $id;
        }

        return null;
    }


    /**
     * Searching transaction by Id
     * 
     * @return array Item data
     */
    private function transaction_exists($id = null)
    {
        $transaction = DB::table('transactions')->find($id);

        return ($transaction !== null) ? true : false;
    }


    /**
     * Searching transaction by Id
     * 
     * @return array Item data
     */
    private function item_by_transaction($id)
    {
        $transaction = DB::table('transactions')
            ->where_id($id)
            ->take(1)
            ->get('items_id');

        return ( ! empty($transaction)) ? $transaction[0]->items_id : null;
    }


    /**
     * Recalculating total stock
     * 
     * @param  int $id Item ID
     * @return         Respose
     */
    private function recalculate($id = null)
    {
        if( ! $this->item_exists($id))
        {
            return false;
        }

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