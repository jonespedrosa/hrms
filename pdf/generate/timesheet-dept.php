<i class="fas fa-store fa-sm mr-2"></i>

<select name="department" id="department-selector" class="form-control border-0 bg-transparent">
    <?php 
        $counter = 0;
        while($counter < count($row_dept)){
            $is_selected = "";
            if($_SESSION["useridd"] == $row_dept[$counter]["userid"]){
                $is_selected = "selected";
            } 
            echo '<option value="'.$row_dept[$counter]["userid"].'" '.$is_selected.'>'.strtoupper($row_dept[$counter]["concat_dept"]).'</option>';
            // echo '<option value=""></option>';
            $counter++;
        }
    ?>
</select>