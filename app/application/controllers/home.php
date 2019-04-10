<?php

class Home_Controller extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_index()
	{
		return Redirect::to_action('item@list');
	}

}