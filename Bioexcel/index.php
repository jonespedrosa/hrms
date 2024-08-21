<form enctype="multipart/form-data" method="post" role="form">
    <div class="form-group">
        <label for="exampleInputFile">File Upload</label>
        <input type="file" name="file" id="file" size="150">
        <p class="help-block">Only Excel/CSV File Import.</p>
    </div>
    <button type="submit" class="btn btn-default" name="Import" value="Import">Upload</button>
</form>

<?php 
if(isset($_POST["Import"]))
{
    //First we need to make a connection with the database
   $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

    echo $filename=$_FILES["file"]["tmp_name"];
    if($_FILES["file"]["size"] > 0)
    {
        $file = fopen($filename, "r");
        //$sql_data = "SELECT * FROM prod_list_1 ";
        while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
        {
            //print_r($emapData);
            //exit();
            $sql = "INSERT into info(id,empno,lastname,firstname,department,datefromto,timein,breakout,breakin,timeout,overin,overout) values ('$emapData[0]','$emapData[1]','$emapData[2]','$emapData[3]','$emapData[4]','$emapData[5]','$emapData[6]','$emapData[7]','$emapData[8]','$emapData[9]','$emapData[10]','$emapData[11]')";
            $querydate=$HRconnect->query($sql);  
             echo 'CSV File has been successfully Imported';
        }
        fclose($file);
       
  
    }
    else
        echo 'Invalid File:Please Upload CSV File';
}
?>