<?php
namespace view;

// serves up info from controller and model in json format, specifically regarding the Home pages
class Home extends \View {
	public $page = [];

	public function __construct() {
		parent::__construct();
	}

	// display home page info
	public function default() {
		$this->page['title'] = "Dr. Vet!";

		$this->page['body_classes'] = "home";

		$this->page['links'] = [
			'container' => 'homeLinks',
			'play'=>[
				'text' => 'Play',
				'link' => BASE_URI . 'app/setup',
			],
			'qstart'=>[
				'text' => 'QuickStart',
				'link' => BASE_URI . 'app/game/quickStart',
			]
		];
		$this->displayJson($this->page);
	}
}
