<?php

// base versions of all but core classes

class Controller {
	public function __construct() {
	}

	// loads a model
	protected function loadModel($name = 'game') {
		if (!$this->model) {
			$classManagament = new classLoader($name, 'model');
			$classManagament->loadClass();

			$this->model = new Model\Game();
		}
	}

}

class View {
	public function __construct() {
	}
	
	// display info in json format
	public function displayJson($data) {
		echo json_encode($data);
	}
}

class Model {

	protected $dbc;

	// automatically connects to the db
	public function __construct() {
		$this->connect();
	}
	
	// connects to db
	protected  function connect() {
		DEFINE('DB_HOST', 'localhost');

		DEFINE('DB_NAME', 'analeerose_vet');
		DEFINE('DB_USER', 'analeerose_vet');
		DEFINE('DB_PASSWORD', '3.14NCream');
		$this->dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	}

}
