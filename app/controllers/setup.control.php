<?php

// handles interactions for the setup page
class Setup extends Controller {
	private $paramsExist;
	private $view;

	// accepts $paramsExist whichs confirms parameters do or do not exist
	public function __construct($paramsExist) {
		parent::__construct();

		$this->paramsExist = $paramsExist;
		$classManagament = new classLoader('setup', 'view');
		$classManagament->loadClass();

		$this->view = new view\Setup();
	}

	// loads the base setup page
	public function default() {
		$this->view->default();
	}
}