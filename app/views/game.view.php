<?php
namespace View;

// serves up info from controller and model in json format, specifically regarding the game pages
class Game extends \View {
	public $page = [];

	public function __construct() {
		parent::__construct();
	}

	// display a list of invalid parameters for starting new custom game
	public function failTest($missingParams) {
		$this->page['title'] = "Setup";
		$this->page['body_classes'] = "home";
		$this->page['missingParams'] = $missingParams;

		$this->page['error'] = "missingParams";
		$this->displayJson($this->page);
	}

	// displays start page info
	public function start($toolName, $toolType = 'treatement') {
		$this->page['title'] = "Oh No!";
		$this->page['tool'] = $toolName;

		$this->page['body'] = [
			'p1' => ['p', ucwords($_SESSION['pName']) . ' is sick, they have ' . $_SESSION['illness'] . "."],
			'p2' => ['p', "Dr. " . ucwords($_SESSION['dName']) . ", use the " . str_replace("_"," ",$toolName) ." to help them! Use the picture below to find the right " . $toolType . "."],
			'toolImg' => ['img', './assets/imgs/tools/' . $toolName . '.jpg', "Picture of a " . $toolName],
		];

		$this->page['links'] = [
			'next' => [
				'text' => "Use " . ucwords($toolName),
				'link' => BASE_URI . "app/game/getTool/?itemName=" . $toolName
			]
		];
		$this->page['treatment_counter'] = $_SESSION['totalItemsUsed'] . " of 6 treatments completed";
		$this->displayJson($this->page);
	}

	// displays finish page info
	public function finish() {
		$this->page['title'] = "Good Job!";
		$this->page['body'] = [
			'p1' => ['p', ucwords($_SESSION['pName']) . " is all better! You did amazing, Dr. " . ucwords($_SESSION['dName']) . '!'],
			// 'p2' => ['p', "Click the button below to restart the game!"],
			'Home' => ['a', 'Home', BASE_URL]
		];

		$this->page['celebrate'] = true;
		$this->displayJson($this->page);
	}

		
	// displays new tool page info
	public function runTool($toolInfo) {
		$this->page['title'] = ucwords($toolInfo["name"]);
		$this->page['body'] = [
			'how-to' => ['h2', "How to Use"],
			'p1' => ['p', $toolInfo["use"]],
			'p2' => ['p', "Click the button below when you're ready to start!"],
		];

		$this->page['tool'] = [
			'name' => $toolInfo["name"],
			'use' => $toolInfo["use"],
			'timer' => $toolInfo["timer"],
			'successLink' => BASE_URI . "app/game/success/?toolName=" . $toolInfo["name"]
		];

		$this->page['links'] = ['timeGame' => $toolInfo["timer"], 'jsLink'] ;

		$this->displayJson($this->page);
	}

	// displays new quickStart info
	public function quickStart() {
	// displays new tool page info
		$this->page['title'] = "QuickStart";

		$this->page['links'] = [
			'dallas' => [
				'text' => "Dallas",
				'link' => BASE_URI . "app/game/quickStart/?name=dallas"
			],

			'austin' => [
				'text' => "Austin",
				'link' => BASE_URI . "app/game/quickStart/?name=austin"
			],

			'generic' => [
				'text' => "Generic",
				'link' => BASE_URI . "app/game/quickStart/?name=generic"
			],

		];

		$this->displayJson($this->page);
	}
}