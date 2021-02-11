<?php

// handles interactions for the Game page
class Game extends Controller {
	private $paramsExist;
	private $view;
	protected $model = false;

	// accepts a bool confirming parameters do or do not exist
	public function __construct($paramsExist) {
		parent::__construct();

		$this->paramsExist = $paramsExist;
		$classManagament = new classLoader('game', 'view');
		$classManagament->loadClass();

		$this->view = new view\Game();
	}	

	//verifies submitted form info, setups up session variables, and passes info onto to run to actually start the game
	public function start() {

		if (!$this->paramsExist) {
			$this->view->failTest($_POST);
			return true;
		} else {

			$this->loadModel();
			$checkForm = $this->model->checkForm($_POST);

			if (is_array($checkForm) && isset($checkForm['error'])) {
				$this->view->failTest($checkForm);
				return true;
			} elseif($checkForm) {
				
				// run game
				$_SESSION['dName'] = $_POST['dName'];
				$_SESSION['pName'] = $_POST['pName'];
				$_SESSION['illness'] = $_POST['illness'];
				$_SESSION['treatments'] = 0;
				$_SESSION['cures'] = 0;
				$_SESSION['totalItemsUsed'] = 0;

				$this->run(true);

			}

		}

	}

	// runs the game, aka selects tool that has not been used before and presents it to user
	public function run($first = false) {
		if ($first) {
			$_SESSION['itemsUsed'] = null;
		}

		if (isset($_GET['success']) && $_GET['success'] == true) {
			if ($_SESSION['treatments'] < 4) {
				$_SESSION['treatments']++;
			} else {
				$_SESSION['cures']++;
			}

			$_SESSION['totalItemsUsed'] = $_SESSION['treatments'] + $_SESSION['cures'];
			$_GET['success'] = false;
		} elseif(isset($_GET['tool'])) {
			$tool = $_GET['tool'];
			if (in_array($tool, $_SESSION['itemsUsed'])) {
				unset($_SESSION['itemsUsed'][$tool]);
			}
		}

		$this->loadModel();
		if ($_SESSION['cures'] >= 2) {
			$this->view->finish();
		} else{ 
			if ($_SESSION['treatments'] < 4) {
				$type = 'tool';
			} elseif($_SESSION['cures'] < 2) {
				$type = 'cure';
			}

			if ($this->model->checkParams()) {
				$toolName = $this->model->selectItem($type);
				if ($toolName) {
					// continue
					$_SESSION['toolsUsed'] = [$toolName];
					$this->view->start($toolName, $type);

				} else {
					throw new Exception('item selection failed');
				}
			} else {
				echo "RUN FAILED";
				exit();
			}
		}
	}


	// gets a tool using the name in _GET
	public function getTool() {
		if (isset($_GET['itemName']) && !empty(trim($_GET['itemName']))) {

			$this->loadModel();

			$toolInfo = $this->model->getItem($_GET['itemName']);

			$this->view->runTool($toolInfo);

		} else {
			throw new Exception('grabbing tool failed');
		} 

	}

	public function quickStart() {
		if (isset($_GET['name'])) {
			switch ($_GET['name']) {
				case 'dallas':
					$_SESSION['dName'] = "Dallas";
					$_SESSION['pName'] = "a puppy";
					break;

				case 'austin':
					$_SESSION['dName'] = "austin";
					$_SESSION['pName'] = "a kitty";
					break;

				case 'ana':
					$_SESSION['dName'] = "ana";
					$_SESSION['pName'] = "Winnie";
					break;
				
				default:
					$_SESSION['dName'] = "Vet";
					$_SESSION['pName'] = "A pet";
					break;
			}
			
			$_SESSION['illness'] = "a cold";
			$_SESSION['treatments'] = 0;
			$_SESSION['cures'] = 0;
			$_SESSION['totalItemsUsed'] = 0;
			$this->run(true);
		} else {
			$this->view->quickStart();
		}
	}
}