<?php
session_start();
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
if (isset($_GET['course'])) {
	if ($_GET['course'] == 'getCourses') {
		echo getCourses($HRconnect, $_GET['departmentId']);
	} else if ($_GET['course'] == 'getDepartmentByAreatype') {
		echo getDepartmentByArea($HRconnect, $_GET['areaType']);
	} else if ($_GET['course'] == 'getDepartments') {
		echo getDepartments($HRconnect);
	} else if ($_GET['course'] == 'addCourse') {
		echo addCourse($HRconnect, $_POST);
	} else if ($_GET['course'] == 'updateCourse') {
		echo updateCourse($HRconnect, $_POST);
	} else if ($_GET['course'] == 'getCourseById') {
		echo getCourseById($HRconnect, $_GET['id']);
	}
}

function getCourses($HRconnect, $departmentIds)
{
	$queryAllCourse = "SELECT 
        lnd_training_courses.*,
        (SELECT COUNT(*) FROM lnd_course_topics WHERE lnd_course_topics.course_id = lnd_training_courses.id) AS topic_count,
        GROUP_CONCAT(lnd_enrolled_dept.userid) AS enrolled_departments
        FROM `hrms`.`lnd_training_courses` 
        LEFT JOIN  lnd_enrolled_dept ON lnd_training_courses.id = lnd_enrolled_dept.course_id";

	if (!empty($departmentIds)) {
		$departmentIdsString = implode(',', $departmentIds);
		$queryAllCourse .= " WHERE lnd_enrolled_dept.userid IN ($departmentIdsString)";
	}
	$queryAllCourse .= " GROUP BY lnd_training_courses.id;";
	$result = mysqli_query($HRconnect, $queryAllCourse);
	$data = $result->fetch_all(MYSQLI_ASSOC);

	return json_encode($data);
}

function getDepartmentByArea($HRconnect, $areaType)
{
	$queryDepartment = "SELECT ui.userid, ui.branch, ui.department FROM db.user us
	LEFT JOIN hrms.user_info ui on us.userid = ui.userid 
	WHERE areatype = '$areaType'  
	GROUP BY us.userid
	ORDER BY ui.branch asc";
	$result = mysqli_query($HRconnect, $queryDepartment);
	$data = $result->fetch_all(MYSQLI_ASSOC);

	return json_encode($data);
}

function getDepartments($HRconnect)
{
	$queryDepartment = "SELECT ui.userid, ui.branch, ui.department FROM db.user us
    LEFT JOIN hrms.user_info ui on us.userid = ui.userid  
	WHERE us.areatype != ''
    GROUP BY us.userid 
    ORDER BY us.areatype desc";
	$result = mysqli_query($HRconnect, $queryDepartment);
	$data = $result->fetch_all(MYSQLI_ASSOC);


	return json_encode($data);
}

function addCourse($HRconnect, $request)
{
	try {
		$formdata = urldecode($request['formData']);
		parse_str($formdata, $dataArray);
		$courseName = $dataArray['courseName'];
		$courseDescription = $dataArray['courseDescription'];
		$courseTopics = $dataArray['topicTitle'];
		$courseTopicDescription = $dataArray['topicDescription'];
		$enrolledDepartments = $request['enrolledDepartments'];
		$createdById = $_SESSION['empno'];

		mysqli_begin_transaction($HRconnect);

		$queryCourse = mysqli_prepare($HRconnect, "INSERT INTO `hrms`.`lnd_training_courses` (name, description, created_by_id) VALUES (?, ?, ?)");
		mysqli_stmt_bind_param($queryCourse, "sss", $courseName, $courseDescription, $createdById);
		mysqli_stmt_execute($queryCourse);
		$lastInsertedCourseId = mysqli_insert_id($HRconnect);
		mysqli_stmt_close($queryCourse);


		for ($ctr = 0; $ctr < count($courseTopics); $ctr++) {
			$queryTopics = mysqli_prepare($HRconnect, "INSERT INTO `hrms`.`lnd_course_topics` (name, description, course_id, created_by_id) VALUES (?, ?, ?, ?)");
			mysqli_stmt_bind_param($queryTopics, "ssss", $courseTopics[$ctr], $courseTopicDescription[$ctr], $lastInsertedCourseId, $createdById);
			mysqli_stmt_execute($queryTopics);
			mysqli_stmt_close($queryTopics);
		}

		for ($ctr = 0; $ctr < count($enrolledDepartments); $ctr++) {
			$queryEnrolledDepartments = mysqli_prepare($HRconnect, "INSERT INTO `hrms`.`lnd_enrolled_dept` (userid, course_id, created_by_id) VALUES (?, ?, ?)");
			mysqli_stmt_bind_param($queryEnrolledDepartments, "iss", $enrolledDepartments[$ctr], $lastInsertedCourseId, $createdById);
			mysqli_stmt_execute($queryEnrolledDepartments);
			mysqli_stmt_close($queryEnrolledDepartments);
		}

		mysqli_commit($HRconnect);
		return json_encode($request);
	} catch (Exception $e) {
		mysqli_rollback($HRconnect);
		throw new Exception($e->getMessage());
	}
}

function updateCourse($HRconnect, $request)
{
	$formdata = urldecode($request['formData']);
	parse_str($formdata, $dataArray);
	$courseName = $dataArray['courseName'];
	$courseDescription = $dataArray['courseDescription'];
	$courseIdArr = $dataArray['topicId'];
	$courseTopics = $dataArray['topicTitle'];
	$courseTopicDescription = $dataArray['topicDescription'];
	$enrolledDepartments = $request['enrolledDepartments'];
	$updatedById = $_SESSION['empno'];
	$courseId = $request['id'];
	$removedEnrolledDepartment = $request['removedEnrolledDepartments'];
	$removedTopics = $request['removedTopics'];
	try {
		mysqli_begin_transaction($HRconnect);

		$queryUpdateCourse = mysqli_prepare($HRconnect, "UPDATE `hrms`.`lnd_training_courses` SET name = ?, description = ?, updated_by_id = ? WHERE id = ?");
		mysqli_stmt_bind_param($queryUpdateCourse, "sssi", $courseName, $courseDescription, $updatedById, $courseId);
		mysqli_stmt_execute($queryUpdateCourse);
		mysqli_stmt_close($queryUpdateCourse);

		for ($ctr = 0; $ctr < count($courseIdArr); $ctr++) { 
			if ($courseIdArr[$ctr] != "" || $courseIdArr[$ctr] != null) { 
				$queryUpdateTopics = mysqli_prepare($HRconnect, "UPDATE `hrms`.`lnd_course_topics` SET name = ?, description = ?, updated_by_id = ? WHERE id = ?");
				mysqli_stmt_bind_param($queryUpdateTopics, "ssii", $courseTopics[$ctr], $courseTopicDescription[$ctr], $updatedById, $courseIdArr[$ctr]);
				mysqli_stmt_execute($queryUpdateTopics);
				mysqli_stmt_close($queryUpdateTopics);
			} else {
				$queryTopics = mysqli_prepare($HRconnect, "INSERT INTO `hrms`.`lnd_course_topics` (name, description, course_id, created_by_id) VALUES (?, ?, ?, ?)");
				mysqli_stmt_bind_param($queryTopics, "ssss", $courseTopics[$ctr], $courseTopicDescription[$ctr], $courseId, $updatedById);
				mysqli_stmt_execute($queryTopics);
				mysqli_stmt_close($queryTopics);
			}
		}

		if (!empty($removedTopics)) {
			$placeholders = implode(',', $removedTopics);
			$queryDeleteTopics = mysqli_prepare($HRconnect, "DELETE FROM `hrms`.`lnd_course_topics` WHERE id IN ($placeholders) AND course_id = ?");
			mysqli_stmt_bind_param($queryDeleteTopics, "i", $courseId);
			mysqli_stmt_execute($queryDeleteTopics);
			mysqli_stmt_close($queryDeleteTopics);
		}

		if (!empty($removedEnrolledDepartment)) {
			$placeholders = implode(',', $removedEnrolledDepartment);
			$queryEnrolledDepartment = mysqli_prepare($HRconnect, "DELETE FROM `hrms`.`lnd_enrolled_dept` WHERE userid IN ($placeholders) AND course_id = ?");
			mysqli_stmt_bind_param($queryEnrolledDepartment, "i", $courseId);
			mysqli_stmt_execute($queryEnrolledDepartment);
			mysqli_stmt_close($queryEnrolledDepartment);
			var_dump($placeholders);
		}
		for ($ctr = 0; $ctr < count($enrolledDepartments); $ctr++) {
			$queryEnrolledDepartments = mysqli_prepare($HRconnect, "INSERT INTO `hrms`.`lnd_enrolled_dept` (userid, course_id, created_by_id) VALUES (?, ?, ?)");
			mysqli_stmt_bind_param($queryEnrolledDepartments, "iss", $enrolledDepartments[$ctr], $courseId, $updatedById);
			mysqli_stmt_execute($queryEnrolledDepartments);
			mysqli_stmt_close($queryEnrolledDepartments);
		}
		mysqli_commit($HRconnect);
		return 'success';
	} catch (Exception $e) {
		mysqli_rollback($HRconnect);
		throw new Exception($e->getMessage());
	}
}

function getCourseById($HRconnect, $id)
{
	try {
		$queryCourse = "SELECT name, description, id FROM `hrms`.`lnd_training_courses` WHERE id = $id";
		$result = mysqli_query($HRconnect, $queryCourse);
		$dataCourse = $result->fetch_all(MYSQLI_ASSOC);

		$queryTopics = "SELECT name, description, id FROM `hrms`.`lnd_course_topics` WHERE course_id = $id";
		$result = mysqli_query($HRconnect, $queryTopics);
		$dataTopic = $result->fetch_all(MYSQLI_ASSOC);

		$queryDepartments = "SELECT led.userid, ui.branch FROM `hrms`.`lnd_enrolled_dept` led 
		LEFT JOIN `hrms`.`user_info` ui on led.userid = ui.userid
		WHERE led.course_id = $id
		GROUP BY ui.userid";
		$result = mysqli_query($HRconnect, $queryDepartments);
		$dataDepartments = $result->fetch_all(MYSQLI_ASSOC);

		$data = array(
			'is_exist' => count($dataCourse) > 0,
			'course' => $dataCourse,
			'topics' => $dataTopic,
			'departments' => $dataDepartments
		);

		return json_encode($data);
	} catch (Exception $e) {
		throw new Exception($e->getMessage());
	}
}