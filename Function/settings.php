<?php
    $HRconnect = mysqli_connect("localhost", "root", "", "hrms"); 
    if(isset($_GET['settings'])){
        if($_GET['settings'] == "approvals"){
            echo getBranch($_GET['department'], $_GET['selected_dept'],$HRconnect);
        }
    }

    function getBranch($department, $selectedDept,$HRconnect){  
        // OPTIONS BRANCHES
        $element = '';
        $selectBranch = "SELECT DISTINCT branch FROM hrms.user_info WHERE department = '$selectedDept' AND branch IS NOT NULL AND department IS NOT NULL ORDER BY branch ASC";
        $queryBranch = $HRconnect->query($selectBranch);
        while($rowBranch = $queryBranch->fetch_array()) {
            $element .='<div class="form-check form-switch">
                            <input class="form-check-input branches" type="checkbox" name="branch[]" value="'.$rowBranch['branch'].'">
                            <label class="form-check-label">'.$rowBranch['branch'].'</label>
                            <div class="d-none" id="switch-'.$rowBranch['branch'].'">
                                // dynamic
                            </div>
                        </div>'; 
        }
    
        return $element; 
    }   

?>

<!--         // container for consolidated approval
        $approvalArray = array(); 
        $counter = 0;
        while($counter < count($department)){
            $branchesArray = array();
            $selectBranches = "SELECT DISTINCT branch FROM hrms.user_info WHERE department = '$department[$counter]' AND branch = '$'";
            $queryBranches = $HRconnect->query($selectBranches);
            while($rowBranches = $queryBranches->fetch_array()) {
                array_push($branchesArray, $rowBranches['branch']);
            }
            $departmentArray = array(
                "name" => $department[$counter],
                "branches" => $branchesArray
            );
            array_push($approvalArray, $departmentArray);
            $counter++;
        }
            $updateAccess = "UPDATE `hrms`.`approval_access` SET `approvals_access` = '".json_encode($approvalArray)."';";
        $queryUpdateAccess = $HRconnect->query($updateAccess);
 -->