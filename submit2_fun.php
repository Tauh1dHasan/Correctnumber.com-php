<?php require_once('Connections/saha.php'); ?>
<?php require_once('inc-main.php'); ?>
<?php
    // checking if values are submited
    if(isset($_POST['submit'])) {
        $web_url = mysqli_real_escape_string($saha, $_POST['web_url']);
        $title = mysqli_real_escape_string($saha, $_POST['title']);
        $description = mysqli_real_escape_string($saha, $_POST['description']);
        $contact_person = mysqli_real_escape_string($saha, $_POST['contact_person']);
        $company_name = mysqli_real_escape_string($saha, $_POST['company_name']);
        $address = mysqli_real_escape_string($saha, $_POST['address']);
        $email = mysqli_real_escape_string($saha, $_POST['email']);
        $mobile_number = mysqli_real_escape_string($saha, $_POST['mobile_number']);
        $phone_number = mysqli_real_escape_string($saha, $_POST['phone_number']);
        $land_mark = mysqli_real_escape_string($saha, $_POST['land_mark']);
        $city = mysqli_real_escape_string($saha, $_POST['city']);
        $state = mysqli_real_escape_string($saha, $_POST['state']);
        $country = mysqli_real_escape_string($saha, $_POST['country']);
        $category = mysqli_real_escape_string($saha, $_POST['category']);
        $keywords = mysqli_real_escape_string($saha, $_POST['keywords']);
        $targated_client = mysqli_real_escape_string($saha, $_POST['targeted_client']);
        $short_note = mysqli_real_escape_string($saha, $_POST['short_note']);
        
        $sql = "INSERT INTO submit2(web_url, title, description, contact_person, company_name, address, email, mobile_number, phone_number, land_mark, city, state, country, category, keyword, targeted_client, short_note) 
        VALUES ('$web_url','$title','$description','$contact_person','$company_name','$address','$email','$mobile_number','$phone_number','$land_mark','$city','$state','$country','$category','$keywords','$targated_client','$short_note')";
        $run_sql = mysqli_query($saha, $sql);
        
        if(!$run_sql){
            echo "<script> alert('Something went wrong, Please try again') </script>";
            echo "<script> location = 'submit2.php' </script>";
        }
    }
?>

<html>
    <head>
        <title>Survey Submited</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div calss="col-md-3"></div>
                <div calss="col-md-6">
                    <h2 style="text-align: center">Thank you for the survey, Your information submited</h2>
                </div>
                <div calss="col-md-3"></div>
            </div>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4" style="text-align: center;">
                    <a href="index.php" class="btn btn-danger" style="background: red;">Go Home</a>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
    </body>
</html>