<input type="text" class="d-none" id="userlevel" value="<?php echo $_SESSION["userlevel"] ?>">
<?php
for ($counter = 0; $counter < count($employee_schedule); $counter++) {
    // HOOKS
    $res_datefromto = date("m-d-Y", strtotime($employee_schedule[$counter]["datefromto"]));
    $res_schedfrom = date("H:i", strtotime($employee_schedule[$counter]["schedfrom"]));
    $res_schedto = date("H:i", strtotime($employee_schedule[$counter]["schedto"]));
    $res_break = $employee_schedule[$counter]["break"];

    // FOR BREAKS
    $M_timeout_result;
    $breakout_result;
    $breakin_result;
    $A_timein_result;
    if (stripos($employee_schedule[$counter]["M_timeout"], "No Break") !== false) {
        $M_timeout_result = "No Break";
        $breakout_result = "No Break";
    } else {
        $M_timeout_result = date("H:i", strtotime($employee_schedule[$counter]["M_timeout"]));
        $breakout_result = date("H:i", strtotime($employee_schedule[$counter]["M_timeout"]));
    }
    if (stripos($employee_schedule[$counter]["A_timein"], "No Break") !== false) {
        $A_timein_result = "No Break";
        $breakin_result = "No Break";
    } else {
        $A_timein_result = date("H:i", strtotime($employee_schedule[$counter]["A_timein"]));
        $breakin_result = date("H:i", strtotime($employee_schedule[$counter]["A_timein"]));
    }

    $res_scheduled_timein = ($employee_schedule[$counter]["M_timein"] == "") ? "" : date("H:i", strtotime($employee_schedule[$counter]["M_timein"]));
    $res_timein = ($employee_schedule[$counter]["timein"] == "") ? " " : date("H:i", strtotime($employee_schedule[$counter]["timein"]));
    $res_scheduled_breakout = ($employee_schedule[$counter]["M_timeout"] == "") ? "" : $M_timeout_result;
    $res_breakout = ($employee_schedule[$counter]["breakout"] == "") ? "" : $breakout_result;
    $res_scheduled_breakin = ($employee_schedule[$counter]["A_timein"] == "") ? "" : $A_timein_result;
    $res_breakin = ($employee_schedule[$counter]["breakin"] == "") ? "" : $breakin_result;
    $res_scheduled_timeout = ($employee_schedule[$counter]["A_timeout"] == "") ? " " : date("H:i", strtotime($employee_schedule[$counter]["A_timeout"]));
    $res_timeout = ($employee_schedule[$counter]["timeout"] == "") ? "" : date("H:i", strtotime($employee_schedule[$counter]["timeout"]));
    $res_remarks = $employee_schedule[$counter]["remarks"];

    //Work Hours 
    $res_work_hours = $employee_schedule[$counter]["work_hours"];

    // To minutes
    $res_schedfrom_hour = date("H", strtotime($employee_schedule[$counter]["schedfrom"]));
    $res_schedfrom_min = date("i", strtotime($employee_schedule[$counter]["schedfrom"]));
    $res_schedto_hour = date("H", strtotime($employee_schedule[$counter]["schedto"]));
    $res_schedto_min = date("i", strtotime($employee_schedule[$counter]["schedto"]));
    ?>

    <?php
    if ($_SESSION["userlevel"] == "master") {
        ?>
        <tr>
            <td colspan="2">
                <center class="date-from"><?php echo $res_datefromto; ?></center>
                <center><input type="text" name="cutoff-date[]" class="form-control text-center d-none"
                        value="<?php echo $res_datefromto; ?>"></center>
                <center><input type="text" class="d-none work-hours" value="<?php echo $res_work_hours; ?>"></center>
                <input class="form-control d-none scheduled-out" value="<?php echo $employee_schedule[$counter]["schedto"]; ?>">
            </td>
            <td>
                <center>
                    <input type="text" name="schedfrom[]" pattern="(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]"
                        title="Please enter a valid military time (HH:mm)" class="form-control text-center schedfrom"
                        value="<?php echo trim($res_schedfrom); ?>">
                    -
                    <input type="text" name="schedto[]" pattern="(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]"
                        title="Please enter a valid military time (HH:mm)" class="form-control text-center schedto"
                        value="<?php echo trim($res_schedto); ?>">
                </center>
            </td>
            <td>
                <center>
                    <input type="text" name="break[]" class="form-control text-center breaks"
                        value="<?php echo trim($res_break); ?>">
                </center>
            </td>
            <td class="m-time-in">
                <center>
                    <?php echo $res_scheduled_timein; ?>
                </center>
            </td>
            <td class="m-time-out">
                <center>
                    <?php echo $res_scheduled_breakout; ?>
                </center>
            </td>
            <td class="a-time-in">
                <center>
                    <?php echo $res_scheduled_breakin; ?>
                </center>
            </td>
            <td class="a-time-out">
                <center>
                    <?php echo $res_scheduled_timeout; ?>
                </center>
            </td>
            <td class="text-uppercase" colspan="2">
                <center>
                    <input type="text" name="remarks[]" class="form-control text-center d-none"
                        value="<?php echo (array_key_exists($res_remarks, $arr_remarks)) ? $arr_remarks[$res_remarks] : $res_remarks; ?>">
                    <?php echo (array_key_exists($res_remarks, $arr_remarks)) ? $arr_remarks[$res_remarks] : $res_remarks; ?>
                </center>
            </td>
            <td class="text-success">
                <center>
                    <button type="submit" class="btn btn-outline-success btn-user btn-block btn1" name="SubmitButton"
                        onclick="return confirm('Are you sure you want to Save This Record?');">Save</button>
                    <!-- <b>POSTED</b> -->
                </center>
            </td>
        </tr>
        <?php
    } else {
        ?>
        <tr>
            <td colspan="2">
                <center class="date-from"><?php echo $res_datefromto; ?></center>
                <center><input type="text" name="cutoff-date[]" class="form-control text-center d-none"
                        value="<?php echo $res_datefromto; ?>"></center>
                <center><input type="text" class="d-none work-hours" value="<?php echo $res_work_hours; ?>"></center>
            </td>
            <td>
                <center>
                    <div class="col-xs-12 d-flex justify-content-between align-items-center">
                        <select class="custom-select schedfrom_hour" name="hourfrom[]">
                            <option class="bg-warning text-center"><?php echo $res_schedfrom_hour; ?></option>
                            <?php
                            // OUTPUTS 23 time format
                            $time = "";
                            for ($counter_time = 0; $counter_time < 24; $counter_time++) {
                                $time = $counter_time;
                                if (strlen($time) < 2) {
                                    echo '<option class="text-center" value="0' . $time . '">0' . $time . '</option>';
                                } else {
                                    echo '<option class="text-center" value="' . $time . '">' . $time . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <b class="p-1">:</b>
                        <select class="custom-select schedfrom_min" name="minfrom[]">
                            <option class="bg-warning text-center"><?php echo $res_schedfrom_min; ?></option>
                            <?php
                            // OUTPUTS 23 time format
                            $time = "";
                            for ($counter_time = 0; $counter_time < 60; $counter_time++) {
                                $time = $counter_time;
                                if (strlen($time) < 2) {
                                    echo '<option class="text-center" value="0' . $time . '">0' . $time . '</option>';
                                } else {
                                    echo '<option class="text-center" value="' . $time . '">' . $time . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <p class="text-lowercase d-flex justify-content-center m-0">to</p>
                    <div class="col-xs-12 d-flex justify-content-between align-items-center">
                        <select class="custom-select schedto_hour" name="hourto[]">
                            <option class="bg-warning text-center"><?php echo $res_schedto_hour; ?></option>
                            <?php
                            // OUTPUTS 23 time format
                            $time = "";
                            for ($counter_time = 0; $counter_time < 24; $counter_time++) {
                                $time = $counter_time;
                                if (strlen($time) < 2) {
                                    echo '<option class="text-center" value="0' . $time . '">0' . $time . '</option>';
                                } else {
                                    echo '<option class="text-center" value="' . $time . '">' . $time . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <b class="p-1">:</b>
                        <select class="custom-select schedto_min" name="minto[]">
                            <option class="bg-warning text-center"><?php echo $res_schedto_min; ?></option>
                            <?php
                            // OUTPUTS 23 time format
                            $time = "";
                            for ($counter_time = 0; $counter_time < 60; $counter_time++) {
                                $time = $counter_time;
                                if (strlen($time) < 2) {
                                    echo '<option class="text-center" value="0' . $time . '">0' . $time . '</option>';
                                } else {
                                    echo '<option class="text-center" value="' . $time . '">' . $time . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </center>
            </td>
            <td>
                <center>
                    <input type="text" name="break[]" class="form-control text-center breaks" value="<?php echo $res_break; ?>">
                </center>
            </td>
            <td class="m-time-in">
                <center>
                    <?php echo $res_scheduled_timein; ?>
                </center>
            </td>
            <td class="m-time-out">
                <center>
                    <?php echo $res_scheduled_breakout; ?>
                </center>
            </td>
            <td class="a-time-in">
                <center>
                    <?php echo $res_scheduled_breakin; ?>
                </center>
            </td>
            <td class="a-time-out">
                <center>
                    <?php echo $res_scheduled_timeout; ?>
                </center>
            </td>
            <td class="text-uppercase" colspan="2">
                <center>
                    <!--                 
                <select class="m-0 px-2" name="remarks[]">
                    <option value=""></option>
                    <option value="WDO" <?php //echo($res_remarks == "WDO")? "selected":""; ?>>WDO</option>
                    <option value="AB" <?php //echo($res_remarks == "AB")? "selected":""; ?>>ABSENT</option>
                    <option value="LWOP" <?php //echo($res_remarks == "LWOP")? "selected":""; ?>>LEAVE w/o PAY</option>
                    <?php
                    // if(!in_array($res_remarks, ["LWOP", "WDO", "AB"])){
                    //     echo '<option value="'.$res_remarks.'">'.$res_remarks.'</option>';
                    // }
                    ?>
                </select> -->
                    <input type="text" name="remarks[]" class="form-control text-center d-none"
                        value="<?php echo (array_key_exists($res_remarks, $arr_remarks)) ? $arr_remarks[$res_remarks] : $res_remarks; ?>">
                    <?php echo (array_key_exists($res_remarks, $arr_remarks)) ? $arr_remarks[$res_remarks] : $res_remarks; ?>
                </center>
            </td>
            <td class="text-success">
                <center>
                    <button type="submit" class="btn btn-outline-success btn-user btn-block btn1" name="SubmitButton"
                        onclick="return confirm('Are you sure you want to Save This Record?');">Save</button>
                    <!-- <b>POSTED</b> -->
                </center>
            </td>
        </tr>
        <?php
    }
?>
<?php
}
?>