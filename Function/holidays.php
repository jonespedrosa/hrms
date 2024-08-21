<?php
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
if (isset($_GET['dates'])) {
    if ($_GET['dates'] == 'getHolidays') {
        echo getHoliday($HRconnect);
        exit;
    }
}

function getHoliday($HRconnect)
{
    $select_yearly_leave = "SELECT base_year, current_year FROM `hrms`.`holiday_yearly_leave`";
    $query_yearly_leave = $HRconnect->query($select_yearly_leave);
    $yearly_leave = $query_yearly_leave->fetch_array();
    $base_year = $yearly_leave['base_year'];
    $current_year = $yearly_leave['current_year'];
    $select_holiday_dates = "SELECT holiday_day FROM `hrms`.`holiday` WHERE holiday_day BETWEEN ? AND ? AND type = 0 ORDER BY holiday_day DESC";
    $stmt = $HRconnect->prepare($select_holiday_dates);
    $stmt->bind_param('ii', $base_year, $current_year);
    $stmt->execute();
    $result_holiday_dates = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $response = array(
        'holiday_dates' => $result_holiday_dates,
    );

    return json_encode($response);
}
?>