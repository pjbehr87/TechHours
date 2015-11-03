<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/com/class/service.php';

class UserService extends Service {

	public function createAdminAccount ($username, $name, $email, $password) {

		global $user;

		$stmt = $this->mysqli->prepare('CALL spUserCreateAdminAccount(?, ?, ?, ?, ?);');
		$stmt->bind_param('issss', $user['id'], $username, $name, $email, $this->salt . $password);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$result = $stmt->get_result();
		$stmt->close();
		$row = $result->fetch_assoc();

		$_SESSION['user'] = array(
			'id'		=> $row['user_id'],
			'logged_in'	=> true,
			'username'	=> $row['username'],
			'name'		=> $row['name'],
			'email'		=> $row['email'],
			'access'	=> $row['access'],
			'street'	=> $row['street'],
			'city'		=> $row['city'],
			'state'		=> $row['state'],
			'zip'		=> $row['zip']
		);
		return $row['access'];
	}

	public function createUserAccount ($username, $name, $email, $street, $city, $state, $zip, $password) {

		global $user;

		$stmt = $this->mysqli->prepare('CALL spUserCreateUserAccount(?, ?, ?, ?, ?, ?, ?, ?, ?);');
		$stmt->bind_param('issssssis', $user['id'], $username, $name, $email, $street, $city, $state, $zip, $this->salt . $password);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$result = $stmt->get_result();
		$stmt->close();
		$row = $result->fetch_assoc();

		$_SESSION['user'] = array(
			'id'		=> $row['user_id'],
			'logged_in'	=> true,
			'username'	=> $row['username'],
			'name'		=> $row['name'],
			'email'		=> $row['email'],
			'access'	=> $row['access'],
			'street'	=> $row['street'],
			'city'		=> $row['city'],
			'state'		=> $row['state'],
			'zip'		=> $row['zip']
		);
		return $row['access'];
	}

	public function getNewAccount ($key) {

		$stmt = $this->mysqli->prepare('CALL spUserGetNewAccount(?);');
		$stmt->bind_param('s', $key);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$result = $stmt->get_result();
		$stmt->close();
		if ($result->num_rows === 1) {
			$row = $result->fetch_assoc();

			$_SESSION['user'] = array(
				'id'		=> $row['user_id'],
				'logged_in'	=> false,
				'username'	=> $row['username'],
				'name'		=> $row['name'],
				'email'		=> $row['email'],
				'access'	=> $row['access'],
				'street'	=> $row['street'],
				'city'		=> $row['city'],
				'state'		=> $row['state'],
				'zip'		=> $row['zip']
			);

			return true;
		}
		else {
			return false;
		}
	}
	
	public function login ($username, $password) {
		$stmt = $this->mysqli->prepare('CALL spUserGetUserByPassword(?, ?);');
		$stmt->bind_param('ss', $username, $this->salt . $password);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$result = $stmt->get_result();
		$stmt->close();
		if ($result->num_rows === 1) {
			$row = $result->fetch_assoc();

			$_SESSION['user'] = array(
				'id'		=> $row['user_id'],
				'logged_in'	=> true,
				'username'	=> $row['username'],
				'name'		=> $row['name'],
				'email'		=> $row['email'],
				'access'	=> $row['access'],
				'street'	=> $row['street'],
				'city'		=> $row['city'],
				'state'		=> $row['state'],
				'zip'		=> $row['zip'],
				'email_hours_submitted' => intval($row['email_hours_submitted']) === 1
			);
			return $row['access'];
		}
		else {
			return false;
		}

	}

	public function resetPassword ($username) {

		$key = $this->getRandomString();
		$stmt = $this->mysqli->prepare('CALL spUserPasswordReset(?, ?);');
		$stmt->bind_param('ss', $username, $key);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$result = $stmt->get_result();
		$stmt->close();
		if ($result->num_rows !== 0) {
			$row = $result->fetch_assoc();
			$this->emailPasswordReset($row['email'], $row['name'], $key);
			return true;
		}
		else {
			return false;
		}
	}

	public function updateUserInfo ($username, $name, $email, $emailSubmitHours, $street, $city, $state, $zip) {

		global $user;

		$stmt = $this->mysqli->prepare('CALL spUserUpdateUserInfo(?, ?, ?, ?, ?, ?, ?, ?, ?);');
		$stmt->bind_param('isssisssi', $user['id'], $username, $name, $email, $emailSubmitHours, $street, $city, $state, $zip);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$stmt->close();
		$_SESSION['user']['username']	= $username;
		$_SESSION['user']['name']		= $name;
		$_SESSION['user']['email']		= $email;
		$_SESSION['user']['street']		= $street;
		$_SESSION['user']['city']		= $city;
		$_SESSION['user']['state']		= $state;
		$_SESSION['user']['zip']		= $zip;
		
		return true;
	}

	public function updatePassword ($password) {

		global $user;
		$stmt = $this->mysqli->prepare('CALL spUserUpdatePassword(?, ?);');
		$stmt->bind_param('is', $user['id'], $this->$salt . $password);
		$stmt->execute();
		if (strlen($stmt->error)) {
			$this->logError($stmt->error);
		}
		$stmt->close();
		return true;
	}

}