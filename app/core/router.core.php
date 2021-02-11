<?php

// uses the url to find and run the correct methods

class Router {
	private $controllerPath;
	private $controller;
	private $action;
	private $paramsExist;
	public $url;

	public function __construct($url) {
		$this->url = $url;
	}

	public function findRoute() {
		$explode_url = explode('/', $this->url);
		$explode_url = array_slice($explode_url, 3);

		if ($explode_url[0]) {
			$this->controller = $explode_url[0];
			// echo "ROUTE: " . $this->controller;
		} else {
			return false;
		}

		if (isset($explode_url[1]) && !empty($explode_url[1])) {
			$this->action = $explode_url[1];
		} else {
			$this->action = 'default';
		}


		if (isset($explode_url[2]) && !empty($explode_url[2])) {
			$explode_url[2] = str_replace("?", "", $explode_url[2]);
			$get = explode('=', $explode_url[2]);
			$_GET[$get[0]] = $get[1];
		}
	
		$this->paramsExist = (!empty($_POST)) ? true : false;
	}

	public function getcontroller() {
		return $this->controller;
	}

	public function getAction() {
		return $this->action;
	}

	public function getParamsExist() {
		return $this->paramsExist;
	}
}