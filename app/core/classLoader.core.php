<?php

// loads all classes
class classLoader {

	private $controllerName;
	private $ext;

	public function __construct($controllerName, $ext) {
		$this->controllerName = $controllerName;
		$this->ext = $ext;
	}

	function loadClass() {
		switch ($this->ext) {
			case 'control':
				$folder = "controllers";
				break;

			case 'view':
				$folder = "views";
				break;
				
			case 'model':
				$folder = "models";
				break;
			
			default:
				$folder = "core";
				break;
		}
		$path = ROOT . "/" . $folder  . "/";
		$ext = '.' .$this->ext . '.php';
		$fullPath = $path . $this->controllerName .  $ext;

		if (!file_exists($fullPath)) {
			echo "NO FILE: " . $fullPath;
			return false;
		} else {
			include_once $fullPath;
		}

	}
}
