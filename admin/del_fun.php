<?php 
    require_once('../Connections/saha.php'); 
    require_once('../inc-main.php'); 

// making delete function
    if(empty($_GET['id'])) {
        echo "<script> alert('Please select a list item first..!!!') </script>";
        echo "<script> location = 'submit2_info.php' </script>";
    }else{
        $id = mysqli_real_escape_string($saha, $_GET['id']);
        
        $del_row = mysqli_query($saha, "DELETE FROM submit2 WHERE id = '$id' ");
        
        if($del_row) {
            echo "<script> location = 'submit2_info.php' </script>";
        }else{
            echo "<script> alert('Sorry Please try again..!!!') </script>";
            echo "<script> location = 'submit2_info.php' </script>";
        }
    }
?>