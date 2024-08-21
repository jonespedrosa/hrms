<?php
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
if (isset($_GET['department'])) {
    if ($_GET['department'] == 'all') {
        echo getAllDepartment($HRconnect);
    } else if ($_GET['department'] == 'employee') {
        echo getEmployee($HRconnect, $_GET['departmentId'], $_GET['type']);
    } else if ($_GET['department'] == 'newEmployee') {
        echo addNewCwwEmp($HRconnect, $_POST['newEmployee']);
    } else if ($_GET['department'] == 'uncompress') {
        echo uncompressEmp($HRconnect, $_POST['empno']);
    }
}

function getAllDepartment($HRconnect)
{
    $queryAllDepartment = "SELECT ui.userid, ui.branch, ui.department FROM db.user us
    LEFT JOIN hrms.user_info ui on us.userid = ui.userid 
    where areatype in('HO','Prod','COMMI','South','North','MFO','KIOSK')   
    GROUP BY us.userid
    ORDER BY ui.department asc";
    $result = mysqli_query($HRconnect, $queryAllDepartment);
    $data = $result->fetch_all(MYSQLI_ASSOC);

    return json_encode($data);
}

function getEmployee($HRconnect, $departmentId, $type)
{
    if ($departmentId == '' || $departmentId == null) {
        return json_encode([]);
    }
    $queryAllDepartment = "SELECT empno, name, branch, department FROM hrms.user_info WHERE is_compressed = ? AND status = ? AND empno NOT IN (9999, 9998)";
    if (strcasecmp($departmentId, 'all') != 0) {
        $queryAllDepartment .= " AND userid = ?";
    }
    $stmt = $HRconnect->prepare($queryAllDepartment);
    $isCompressed = 0;
    if ($type == 1) {
        $isCompressed = 1;
    }
    $active = 'active';
    if (strcasecmp($departmentId, 'all') != 0) {
        $stmt->bind_param('iss', $isCompressed, $active, $departmentId);
    } else {
        $stmt->bind_param('is', $isCompressed, $active);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);

    return json_encode($data);
}

function addNewCwwEmp($HRconnect, $employees)
{
    if ($employees == '' || $employees == null) {
        return json_encode([]);
    }
    $implodedEmployees = implode(',', $employees);
    $queryAllDepartment = "UPDATE hrms.user_info SET is_compressed = ? WHERE empno in ($implodedEmployees) AND is_compressed = ?";
    $stmt = $HRconnect->prepare($queryAllDepartment);
    $isCompressed = 1;
    $notCompressed = 0;
    $stmt->bind_param('ii', $isCompressed, $notCompressed);
    $stmt->execute();
    return json_encode($employees);
}

function uncompressEmp($HRconnect, $employee)
{
    if ($employee == '' || $employee == null) {
        return json_encode([]);
    }
    $queryAllDepartment = "UPDATE hrms.user_info SET is_compressed = ? WHERE empno in ($employee) AND is_compressed = ?";
    $stmt = $HRconnect->prepare($queryAllDepartment);
    $isCompressed = 0;
    $notCompressed = 1;
    $stmt->bind_param('ii', $isCompressed, $notCompressed);
    $stmt->execute();
    return json_encode($employee);
}