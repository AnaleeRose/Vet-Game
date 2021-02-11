<?php
namespace View;

// serves up info from controller and model in json format, specifically regarding the Setup pages
class Setup extends \View {
	public $page = [];

	public function __construct() {
		parent::__construct();
	}

	// displays setup page info
	public function default() {
		$this->page['title'] = "Setup";
		$this->page['form'] = [];
		$this->page['body_classes'] = "setup";

		$this->page['form'] = [
			'dName'=>[
				'displayName'=>"Doctor's Name", 
				'type'=>'text', 
				'required'=>true
			],

			'pName'=>[
				'displayName'=>"Patient's Name", 
				'type'=>'text', 
				'required'=>true
			],

			'illness'=>[
				'displayName'=>"Patient's Illness", 
				'type'=>'text', 
				'required'=>false,
				'default' => "a cold"
			],
			'submit'=>[
				'text' => 'Start',
				'link' => BASE_URI . 'app/game/start',
			]
		];
		$this->displayJson($this->page);
	}
}
