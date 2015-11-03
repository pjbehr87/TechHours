<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/com/class/service.php';

class AdminService extends Service {

	public function activateJob ($jobID) {
		$stmt = $this->mysqli->prepare('CALL spAdminActivateJob(?);');
		$stmt->bind_param('i', $jobID);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$stmt->close();
		return true;
	}

	public function activateUser ($userID) {
		$stmt = $this->mysqli->prepare('CALL spAdminActivateUser(?);');
		$stmt->bind_param('i', $userID);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$stmt->close();
		return true;
	}

	public function createJob ($location, $job, $account, $rate, $flat) {

		$stmt = $this->mysqli->prepare('CALL spAdminCreateJob(?, ?, ?, ?, ?);');
		$stmt->bind_param('sssdi', $location, $job, $account, $rate, $flat);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$result = $stmt->get_result();
		$stmt->close();
		$row = $result->fetch_assoc();

		return $row['last_id'];
	}

	public function createLimitedUser ($name, $email, $street, $city, $state, $zip) {

		$stmt = $this->mysqli->prepare('CALL spAdminCreateLimitedUser(?, ?, ?, ?, ?, ?);');
		$stmt->bind_param('sssssi', $name, $email, $street, $city, $state, $zip);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$stmt->close();

		return true;
	}

	public function createUser ($email, $access) {

		$newUserKey = $this->getRandomString();
		$stmt = $this->mysqli->prepare('CALL spAdminCreateUser(?, ?, ?);');
		$stmt->bind_param('ssi', $email, $newUserKey, $access);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$result = $stmt->get_result();
		$stmt->close();
		$row = $result->fetch_assoc();
		$this->emailNewAccount($email, $newUserKey);

		return $row['last_id'];
	}

	public function deactivateJob ($jobID) {
		$stmt = $this->mysqli->prepare('CALL spAdminDeactivateJob(?);');
		$stmt->bind_param('i', $jobID);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$stmt->close();
		return true;
	}

	public function deactivateUser ($userID) {
		$stmt = $this->mysqli->prepare('CALL spAdminDeactivateUser(?);');
		$stmt->bind_param('i', $userID);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$stmt->close();
		return true;
	}

	public function getJob ($jobID) {
		$stmt = $this->mysqli->prepare('CALL spAdminGetJob(?);');
		$stmt->bind_param('i', $jobID);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$stmt->close();
		$job = array(
			'job_id' => $row['job_id'],
			'location' => $row['location'],
			'job' => $row['job'],
			'account' => $row['account'],
			'rate' => $row['rate'],
			'flat' => intval($row['flat']) === 1,
			'active' => intval($row['active']) === 1
		);
		return $job;
	}

	public function getJobs () {
		$stmt = $this->mysqli->prepare('CALL spAdminGetJobs();');
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$result = $stmt->get_result();
		$stmt->close();
		$jobs = array();
		while ($row = $result->fetch_assoc()) {
			$jobs[] = array(
				'job_id' => $row['job_id'],
				'location' => $row['location'],
				'job' => $row['job'],
				'account' => $row['account'],
				'rate' => $row['rate'],
				'flat' => intval($row['flat']) === 1,
				'active' => intval($row['active']) === 1
			);
		}
		return $jobs;
	}

	public function	getUser ($userID) {

		$stmt = $this->mysqli->prepare('CALL spAdminGetUserByID(?);');
		$stmt->bind_param('i', $userID);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$result = $stmt->get_result();
		$stmt->close();
		$row = $result->fetch_assoc();
		$userArray = array(
			'user_id'	=> $row['user_id'],
			'admin'		=> $row['access'] == 1,
			'username'	=> $row['username'],
			'name'		=> $row['name'],
			'email'		=> $row['email'],
			'street'	=> $row['street'],
			'city'		=> $row['city'],
			'state'		=> $row['state'],
			'zip'		=> $row['zip'],
			'active'	=> $row['active'] == 1
		);
		return ($userArray);
	}

	public function	getUsers () {

		$stmt = $this->mysqli->prepare('CALL spAdminGetUsers();');
		$stmt->execute();
		$result = $stmt->get_result();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$stmt->close();
		$userArray = array();
		while ($row = $result->fetch_assoc()) {
			$userArray[] = array(
				'user_id'	=> $row['user_id'],
				'admin'		=> $row['access'] == 1,
				'username'	=> $row['username'],
				'name'		=> $row['name'],
				'email'		=> $row['email'],
				'street'	=> $row['street'],
				'city'		=> $row['city'],
				'state'		=> $row['state'],
				'zip'		=> $row['zip'],
				'active'	=> $row['active'] == 1
			);
		}
		return ($userArray);
	}

	public function updateAdminInfo ($userID, $username, $name) {

		$stmt = $this->mysqli->prepare('CALL spUserUpdateUserInfo(?, ?, ?, NULL, NULL, NULL, NULL, NULL);');
		$stmt->bind_param('iss', $userID, $username, $name);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$stmt->close();
		return true;
	}


	public function updateJob ($jobID, $location, $job, $account, $rate) {

		$stmt = $this->mysqli->prepare('CALL spAdminUpdateJob(?, ?, ?, ?, ?);');
		$stmt->bind_param('isssd', $jobID, $location, $job, $account, $rate);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$stmt->close();

		return true;
	}

	public function updateUserInfo ($userID, $username, $name, $email, $street, $city, $state, $zip) {

		$stmt = $this->mysqli->prepare('CALL spUserUpdateUserInfo(?, ?, ?, ?, ?, ?, ?, ?);');
		$stmt->bind_param('issssssi', $userID, $username, $name, $email, $street, $city, $state, $zip);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$stmt->close();
		return true;
	}

	public function updateUserPassword ($userID, $password) {

		$stmt = $this->mysqli->prepare('CALL spUserUpdatePassword(?, ?);');
		$stmt->bind_param('is', $userID, $password);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$stmt->close();
		return true;
	}

}