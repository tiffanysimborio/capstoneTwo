<?php

class Base_Controller extends Controller {

	public $restful = true;


	public function __construct()
	{
		parent::__construct();

		// Load setting from Database
		$settings = $this->load_settings('settings');

		// Set setting values as Config values
		if($settings) $this->set_settings($settings);

        // Password protecting everything
		$this->filter('before', 'auth');
	}

	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function __call($method, $parameters)
	{
		return Response::error('404');
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
     * Load setting from Database
     * @param  string $table Table name
     * @return array         Array of objects containing the settings
     */
	private function load_settings($table)
	{
		$query = DB::table($table)->get();

		return $query;
	}

	/**
	 * Set database values as config values
	 * @param array $settings Array of objects containing the settings
	 */
	private function set_settings($settings = array())
	{
		foreach ($settings as $setting)
		{
			Config::set($setting->name, $setting->value);
		}
	}

}