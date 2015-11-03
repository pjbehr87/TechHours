<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/com/class/service.php';

class HourService extends Service {

	public function addHours ($hoursArray, $userID) {

		$hoursInsert = array();
		foreach ($hoursArray as $hours) {
			$hoursInsert[] = '(' . 
				$userID . ', \'' .
				$hours['in1'] . '\', \'' .
				$hours['out1'] . '\', \'' .
				$hours['in2'] . '\', \'' .
				$hours['out2'] . '\', \'' .
				$hours['date'] . '\', ' .
				$hours['job'] . ', \'' .
				$hours['comment'] . '\'' .
			')';
		}
		$sql = 'INSERT INTO hours (user_id, in1, out1, in2, out2, date, job_id, comment) VALUES ';
		$sql .= implode(',', $hoursInsert);
		$stmt = $this->mysqli->prepare($sql);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$stmt->close();

		return true;
	}

	private function buildHoursArray ($hoursArray) {
		$usersHoursArray = array();
		for ($i = 0; $i < count($hoursArray);) {
			$currentUser = $hoursArray[$i]['user_id'];

			$userHours = array(
				'user_id' => $hoursArray[$i]['user_id'],
				'name' => $hoursArray[$i]['name'],
				'street' => $hoursArray[$i]['street'],
				'city' => $hoursArray[$i]['city'],
				'state' => $hoursArray[$i]['state'],
				'zip' => $hoursArray[$i]['zip'],
				'totalPaid' => 0,
				'hours' => array()
			);
			do {
				$hours = array(
					'hour_id' => $hoursArray[$i]['hour_id'],
					'job_id' => $hoursArray[$i]['job_id'],
					'in1' => $hoursArray[$i]['in1'],
					'out1' => $hoursArray[$i]['out1'],
					'in2' => $hoursArray[$i]['in2'],
					'out2' => $hoursArray[$i]['out2'],
					'date' => $hoursArray[$i]['date'],
					'job' => $hoursArray[$i]['job'],
					'location' => $hoursArray[$i]['location'],
					'comment' => $hoursArray[$i]['comment'],
					'account' => $hoursArray[$i]['account'],
					'paid' => $hoursArray[$i]['paid']
				);
				$userHours['totalPaid'] += $hoursArray[$i]['paid'];
				$userHours['hours'][] = $hours;
			} while (++$i < count($hoursArray) && $hoursArray[$i]['user_id'] === $currentUser);
			$usersHoursArray[] = $userHours;
		}

		return $usersHoursArray;
	}

	public function deleteHours ($hoursID) {

		global $user;

		$stmt = $this->mysqli->prepare('CALL spHourDeleteHours(?);');
		$stmt->bind_param('i', $hoursID);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$result = $stmt->get_result();
		$stmt->close();

		return $result->fetch_assoc();
	}
	
	public function getAllUnsubmittedHours () {

		global $user;

		$stmt = $this->mysqli->prepare('CALL spHourGetAllUnsubmittedHours();');
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$result = $stmt->get_result();
		$stmt->close();
		$hoursArray = array();
		while ($row = $result->fetch_assoc()) {
			$hoursArray[] = $row;
		}

		return $this->buildHoursArray($hoursArray);
	}
	
	public function getAllUnsubmittedHoursOrderAcct () {

		global $user;

		$stmt = $this->mysqli->prepare('CALL spHourGetAllUnsubmittedHoursOrderAcct();');
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$result = $stmt->get_result();
		$stmt->close();
		$hoursArray = array();
		while ($row = $result->fetch_assoc()) {
			$hoursArray[] = $row;
		}

		return $hoursArray;
	}

	public function getAllSubmittedHours () {

		$stmt = $this->mysqli->prepare('CALL spHourGetAllSubmittedHours();');
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$result = $stmt->get_result();
		$stmt->close();
		$hoursArray = array();
		while ($row = $result->fetch_assoc()) {
			$hoursArray[] = $row;
		}

		return $this->buildHoursArray($hoursArray);
	}

	// TODO DELETE FUNCTION
	public function getAllSubmittedHoursOrderAcct () {

		global $user;

		$stmt = $this->mysqli->prepare('CALL spHourGetAllSubmittedHoursOrderAcct();');
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$result = $stmt->get_result();
		$stmt->close();
		$hoursArray = array();
		while ($row = $result->fetch_assoc()) {
			$hoursArray[] = $row;
		}

		return $hoursArray;
	}

	public function getJobs () {

		$stmt = $this->mysqli->prepare('CALL spHourGetJobs();');
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$result = $stmt->get_result();
		$stmt->close();
		$hoursArray = array();
		while ($row = $result->fetch_assoc()) {
			$hoursArray[] = $row;
		}
		return $hoursArray;
	}

	public function getNoHoursUsers () {

		$stmt = $this->mysqli->prepare('CALL spHoursGetNoHoursUsers();');
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$result = $stmt->get_result();
		$stmt->close();
		$usersArray = array();
		while ($row = $result->fetch_assoc()) {
			array_push($usersArray, $row);
		}
		return $usersArray;
	}
	
	public function getUnsubmittedHours () {

		global $user;

		$stmt = $this->mysqli->prepare('CALL spHourGetUnsubmittedHours(?);');
		$stmt->bind_param('s', $user['id']);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$result = $stmt->get_result();
		$stmt->close();
		$hoursArray = array();
		while ($row = $result->fetch_assoc()) {
			$hoursArray[] = $row;
		}

		return $hoursArray;
	}
	
	public function getSubmittedHours () {

		global $user;

		$stmt = $this->mysqli->prepare('CALL spHourGetSubmittedHours(?);');
		$stmt->bind_param('s', $user['id']);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$result = $stmt->get_result();
		$stmt->close();
		$hoursArray = array();
		while ($row = $result->fetch_assoc()) {
			$hoursArray[] = $row;
		}

		return $hoursArray;
	}
	
	public function modifyHours ($hourID, $in1, $out1, $in2, $out2, $date, $job, $comment) {

		global $user;

		$stmt = $this->mysqli->prepare('CALL spHourModifyHours(?,?,?,?,?,?,?,?,?);');
		$stmt->bind_param('isssssisi', $hourID, $in1, $out1, $in2, $out2, $date, $job, $comment, $user['id']);
		$stmt->execute();
		if (strlen($stmt->error)) {
			throw new Exception($stmt->error);
		}
		$result = $stmt->get_result();
		$hours = $result->fetch_assoc();
		$result->free_result();
		$stmt->next_result();
		$result = $stmt->get_result();
		$total = $result->fetch_assoc();
		$stmt->close();
		$hours['totalPaid'] = $total['totalPaid'];
		$hours['in1'] = $this->timeFormat($hours['in1']);
		$hours['out1'] = $this->timeFormat($hours['out1']);
		$hours['in2'] = $this->timeFormat($hours['in2']);
		$hours['out2'] = $this->timeFormat($hours['out2']);
		$hours['date'] = $this->dateFormat($hours['date']);
		return $hours;
	}
	
	public function submitHours ($hourIDList) {

		global $user;

		$sql =	'UPDATE hours SET submit_user = ' . $user['id'] . ', submit_date = NOW() WHERE hour_id IN (' . $hourIDList . '); ';
		$result = $this->mysqli->query($sql);
		$sql =	'SET @user_id = (SELECT DISTINCT user_id FROM hours WHERE hour_id IN (' . $hourIDList . ')); ';
		$result = $this->mysqli->query($sql);
		$sql =	'SELECT DISTINCT U.email, U.name, SUM(H.paid) totalPaid ' . 
				'FROM hours_complete H ' .
				'INNER JOIN users U ON U.user_id = H.user_id AND email_hours_submitted = 1 ' .
				'WHERE H.user_id = @user_id ' .
				'AND submit_date = CAST(NOW() AS DATE) ' .
				'AND NOT EXISTS (' .
					'SELECT 1 FROM hours WHERE user_id = @user_id AND submit_date IS NULL' .
				') ' .
				'GROUP BY U.email, U.name;';
		$result = $this->mysqli->query($sql);
		if ($this->mysqli->error) {
			throw new Exception(mysqli_error());
		}
		if ($result->num_rows) {
			$row = $result->fetch_assoc();
			$this->emailHoursSubmitted($row['email'], $row['name'], $row['totalPaid']);	
		}
		return true;
	}

}