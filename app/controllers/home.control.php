<?php


// handles interactions for the home page
class Home extends Controller {

	public function __construct() {
		parent::__construct();
	}

	// loads the home page
	public function default() {
		$classManagament = new classLoader('home', 'view');
		$classManagament->loadClass();

		$view = new view\Home();
		$view->default();
	}
}