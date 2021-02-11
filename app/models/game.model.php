<?php

namespace Model;

// handles db interaction + anything a little too heavy or private for the controller
class Game extends \Model {

	private $requiredParams = ['dName', 'pName', 'illness'];

	// bare minimum checking of the setup form, takes the form info in an array
	public function checkForm($params) {
		// get a copy of all required parameters
		$requiredParams = $this->requiredParams;

		// foreach parameter: If it exists and is not empty, remove it from our copy of the required parameters
		foreach($requiredParams as $requiredKey => $required) {
			if (array_key_exists($required, $params) && !empty(trim($params[$required]))) {
				$params[$required] = str_replace('/[^0-9a-zA-Z_\ ]/', '', $params[$required]);
				unset($requiredParams[$requiredKey]);
			} else {
				continue;
			}
		}

		// if any required parameters were invalid, return a list of input names
		if (count($requiredParams) > 0) {
			$requiredParams['error'] = "Missing parameters";
			return $requiredParams;
		} else {
			return true;
		}
	}

	// checks if all the parameters for the game already exist in session variables
	public function checkParams() {
		$requiredParams = $this->requiredParams;
		foreach($requiredParams as $requiredKey => $required) {
			if (array_key_exists($required, $_SESSION) && !empty(trim($_SESSION[$required]))) {
				unset($requiredParams[$requiredKey]);
			} else {
				continue;
			}
	
		}

		if (count($requiredParams) > 0) {
			return false;
		} else {
			return true;
		}

	}

	// selects a new tool from the db, takes a list of tools to avoid selecting
	public function selectItem($type, $avoid = false) {
 		// query db to get a list of  tools
		$toolList = [];
		$q = "SELECT item_name FROM `items` WHERE item_type = \"" . $type . "\"";
		$r = mysqli_query($this->dbc, $q);
		if ($r) {
			while ($row = $r->fetch_assoc()) {
				$toolList []= $row['item_name'];
			}
		} else {
			return false;
			echo "Failed to query db, selectTool";
			exit();
		}

		if (!isset($_SESSION['itemsUsed']) || empty($_SESSION['itemsUsed'])) {
			$_SESSION['itemsUsed'] = [];
		} else {
			$avoid = $_SESSION['itemsUsed'];
		}

		// remove previously used tools
		if ($avoid && is_array($avoid)) {
			foreach ($avoid as $toolKey => $tool) {
				if (in_array($tool, $toolList)) {
					$key = array_search($tool, $toolList);
					unset($toolList[$key]);
				}
			}
		}

		// select tool at random
		$newTool = array_rand(array_flip($toolList), 1);

		// prevent that tool from popping up again
		if (!isset($_SESSION['itemsUsed'])) {
			$_SESSION['itemsUsed'] = [];
		} 

		if (!in_array($newTool, $_SESSION['itemsUsed'])) $_SESSION['itemsUsed'][] = $newTool;


		// return tool info
		return $newTool;

	}

	// gets the info for a specific item, takes the name of the tool
	public function getItem($name) {
		//query db for tool info
		$itemInfo = [];
		$q = "SELECT * FROM `items` WHERE item_name = '" . $name . "'";
		$r = mysqli_query($this->dbc, $q);
		if ($r) {
			while ($row = $r->fetch_assoc()) {
				$itemInfo['name']= $row['item_name'];
				$itemInfo['use']= $row['item_use'];
				$itemInfo['timer']= $row['item_timer'];
			}
		} else {
			echo $q;
			echo "Failed to query db, getTool";
			exit();
		}

		//return tool info
		return $itemInfo;

	}


}