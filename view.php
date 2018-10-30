<?php
if(!empty($_GET['id'])){
    //DB details
    $dbHost     = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName     = 'users';
    
    //Create connection and select DB
    $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
    
    //Check connection
    if($db->connect_error){
       die("Connection failed: " . $db->connect_error);
    }
    
    //Get image data from database
    $result = $db->query("SELECT * FROM houses WHERE id = {$_GET['id']}");
    
    if($result->num_rows > 0){
        $imgData = $result->fetch_assoc();
        
        //Render image
        header("Content-type: image/jpg");

        if($_GET['no']==1)
        {
            echo $imgData['image'];
        }
        elseif($_GET['no']==2)
        {
            echo $imgData['image2'];
        }
        elseif($_GET['no']==3)
        {
            echo $imgData['image3'];
        }
        elseif($_GET['no']==4)
        {
            echo $imgData['image4'];
        }

        
    }else{
        echo 'No image found.. PLease try again later';
    }
}
?>    
