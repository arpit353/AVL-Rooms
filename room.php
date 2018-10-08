<?php 
require_once 'config.php';

session_start(); 

// Define variables and initialize with empty values
$username = $password = "default";
$username_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_username']) && isset($_POST['login_password'])){
 
    // Check if username is empty
    if(empty(trim($_POST["login_username"]))){
        $username_err = 'Please enter username.';
    } else{
        $username = trim($_POST["login_username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST['login_password']))){
        $password_err = 'Please enter your password.';
    } else {
        $password = trim($_POST['login_password']);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            /* Password is correct, so start a new session and
                            save the username to the session */
                            session_start();
                            $_SESSION['username'] = $username;      
                            header("location: index.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = 'The password you entered was not valid.';
                            ?>
                            <script type="text/javascript">
                              alert('The password you entered was not valid.');
                            </script>

                            <?php
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = 'No account found with that username.';

                    ?>
                            <script type="text/javascript">
                              alert('No account found with that username.');
                            </script>
                            
                    <?php

                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";

                ?>
                        <script type="text/javascript">
                            alert('No account found with that username.');
                        </script>
                            
                <?php


            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}



if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_password']) && isset($_POST['register_username'])){
 
    // Validate username
    if(empty(trim($_POST["register_username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["register_username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["register_username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Validate password
    if(empty(trim($_POST['register_password']))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST['register_password'])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST['register_password']);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["register_password"]))){
        $confirm_password_err = 'Please confirm password.';     
    } else{
        $confirm_password = trim($_POST['register_password']);
        if($password != $confirm_password){
            $confirm_password_err = 'Password did not match.';
        }
    }


    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
      $first_name = $_POST['first_name'];
      $last_name= $_POST['last_name'];
      $city = $_POST['city'];
      $state = $_POST['state'];
      $mobile = $_POST['mobile_number'];

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $sql1 = "INSERT INTO user_details VALUES (?,?,?,?,?,?,?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                
            } else{
                ?>
                <script type="text/javascript">
                  alert("Something went wrong. Please try again later.");
                </script>
                <?php
            }
        }

        if($stmt1 = mysqli_prepare($link, $sql1)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt1,"sssssss",$first_name,$last_name,$param_username, $param_password,$city,$state,$mobile);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt1)){

              ?>
                <script type="text/javascript">
                  alert("Successfully created new account");
                </script>

              <?php 

                header("location: index.php");
            } else{

                ?>
                <script type="text/javascript">
                  alert("Something went wrong. Please try again later.");
                </script>
                <?php

            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
        mysqli_stmt_close($stmt1);
    }
    
    // Close connection
    mysqli_close($link);
}

$link = mysqli_connect("localhost", "root", "", "users");
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$id=2;
if(isset($_GET['id']))
{
	$id = $_GET['id'];
}

$image1 = "'"."view.php?id=".$_GET["id"]."&no=1"."'";
$image2 = "'"."view.php?id=".$_GET["id"]."&no=2"."'";
$image3 = "'"."view.php?id=".$_GET["id"]."&no=3"."'";
$image4 = "'"."view.php?id=".$_GET["id"]."&no=4"."'";

$sql = "SELECT * FROM houses WHERE id=".$id;

if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) > 0){

    	 while($row = mysqli_fetch_array($result)){

    	 	$house_id = $row['id'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $mobile = $row['mobile'];
        $profession = $row['profession'];
        $house_type = $row['house_type'];
        $bolcony = $row['bolcony'];
        $store_room = $row['storeroom'];
        $twowheeler = $row['twowheeler'];
        $fourwheeler = $row['fourwheeler'];
        $discription = $row['discription'];
        $bachelor = $row['bachelor'];
        $family = $row['family'];
        $married = $row['married'];
        $boys = $row['boys'];
        $girls = $row['girls'];
        $smoking = $row['smoking'];
        $alcohol = $row['alcohol'];
        $nonveg = $row['nonveg'];
        $date = $row['date'];
        $contact = $row['contact'];
        $pincode = $row['pincode'];
        $city = $row['city'];
        $state = $row['state'];
        $rent = $row['rent'];
        $colony = $row['colony'];
        $furnished=$row['furnished'];
    	 }

    	}

    }

if(isset($_SESSION['username']))
{
  $username = $_SESSION['username'];
  $sql = "SELECT * FROM user_details WHERE user_email = '".$username."'";

  $result = mysqli_query($link, $sql);
  $row = mysqli_fetch_assoc($result); 

  $first_name_login = $row['first_name'];
  $last_name_login = $row['last_name'];

}

?>

<!DOCTYPE html>
<html>
<head>
  <title>AVL Homes</title>
  <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="assets/css/materialize.css"  media="screen,projection"/>
  <link href="assets/css/dcalendar.picker.css" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="assets/css/house.css" media="screen,projection"/>
  <link type="text/css" rel="stylesheet" href="assets/css/header.css" media="screen,projection"/>
  <link type="text/css" rel="stylesheet" href="assets/css/house.css" media="screen,projection"/>
  <link rel="stylesheet" type="text/css" href="assets/css/slick.css">
  <link rel="stylesheet" type="text/css" href="assets/css/slick-theme.css">
  <script src="https://use.fontawesome.com/4ef4ce7ce4.js"></script>
  <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi"/>
</head>
<body>
  <div class="avl-header header-container show-on-med-and-large hide-on-small-only">
  <a href="index.php">
    <img src="assets/images/logo.png">
    <h5>AVL ROOMS</h5>
  </a>
  <ul>
    <li><a href="index.php">HOME</a></li>
    <li><?php if(isset($_SESSION['username'])) { ?>
          <a href="accomodate.php">ACCOMODATE</a>
      <?php } ?>
      <?php  if(!isset($_SESSION['username'])) { ?>
          <a class="modal-trigger" href="#accomodate">ACCOMODATE</a>
      <?php } ?>
    </li>
    <li><?php if(!isset($_SESSION['username'])) { ?>
          <a class="modal-trigger" href="#login">LOGIN</a>
      <?php } ?>
      <?php  if(isset($_SESSION['username'])) { ?>
          <a class="modal-trigger" href="#">
          <?php echo strtoupper($first_name)." ".strtoupper($last_name) ?>
          </a>
      <?php } ?>
    </li>
    <li><?php  if(!isset($_SESSION['username'])) { ?>
          <a class="modal-trigger" href="#signup">SIGN UP</a>
        <?php } ?>
        <?php  if(isset($_SESSION['username'])) { ?>
            <a href="logout.php"> LOGOUT</a>
        <?php } ?>
    </li>
  </ul>
  </div>
  <!--MOBILE HEADER & SIDE-NAV-->
  <nav class="theme-color-bg show-on-small hide-on-med-only hide-on-large-only">
    <div class="nav-wrapper">
      <a href="index.php" class="white-text brand-logo center">AVL ROOMS</a>
      <a href="#" data-activates="mobile-demo" class="button-collapse"><i id="nav_icon" class="material-icons">menu</i></a>
      <ul class="side-nav" id="mobile-demo">
        <li class="first_li theme-color-bg">
            <?php if(isset($_SESSION['username'])) { ?>
                <h5 id="welcome">Welcome</h5>
                <h4 id="user_name">
                    <b>
                        <?php echo $first_name." ".$last_name ?>
                    </b>
                </h4>
            <?php } else { ?>
                <h5 id="welcome"></h5>
                <h4 id="user_name">
                    <b>
                        Welcome
                    </b>
                </h4>
            <?php } ?>
        </li>
        <li><a href="index.php">HOME</a></li>
        <li><?php if(isset($_SESSION['username'])) { ?>
                <a href="accomodate.php">ACCOMODATE</a>
          <?php } ?>
          <?php  if(!isset($_SESSION['username'])) { ?>
                <a class="modal-trigger" href="#accomodate">ACCOMODATE</a>
          <?php } ?>
        </li>
        <li>
              <?php if(!isset($_SESSION['username'])) { ?>
                    <a class="modal-trigger" href="#login">LOGIN</a>
              <?php } ?>
              <?php  if(isset($_SESSION['username'])) { ?>
                    <a class="modal-trigger" href="profile.php">
                        ACCOUNT
                    </a>
              <?php } ?>
        </li>
        <li><?php  if(!isset($_SESSION['username'])) { ?>
                <a class="modal-trigger" href="#signup">SIGN UP</a>
            <?php } ?>
            <?php  if(isset($_SESSION['username'])) { ?>
                <a href="logout.php"> LOGOUT</a>
            <?php } ?>
        </li>
      </ul>
    </div>
  </nav>
  <div id="accomodate" class="modal tagline">
   <div class="modal-content m-container">
       <div class="avl-heading">
           <h4>Accomodate</h4>
           <div></div>
       </div>
       <div class="row">
            <p class="mb-4">Upload free advertisement and find a suitable tenant.<br>
            Please, <span>Login</span> to continue.</p>
            <a id="lgn" class="btn modal-button white-text">Login</a>
            <p class="mb-4">New? Create a <span>new account</span>.</p>
            <a id="sgnup" class="btn modal-button white-text">Sign Up</a>
       </div>
    </div>
  </div>
  <div id="login" class="modal tagline lgn">
   <form method="POST" action="index.php">
    <div class="modal-content m-container">
       <div class="avl-heading">
       <h4>Login</h4>
       <div></div>
       </div>
       <div class="row">
        <div class="input-field col s12">
          <input id="Email_id" type="email" name="login_username" class="validate" required>
          <label for="Email_id">Email ID</label>
        </div>
        <div class="input-field col s12">
          <input id="password" type="password" name="login_password" minlength="8" required>
          <label for="password">Password</label>
        </div>
      </div>
      <button class="btn left modal-button" type="submit" >Login</button>
    </div>
   </form>
  </div>
  <div id="signup" class="modal tagline">
    <form method="POST" action="index.php">
      <div class="modal-content m-container">
          <div class="avl-heading">
            <h4>Sign Up</h4>
            <div></div>
          </div>
          <div class="row">
            <div class="input-field col s12 m6 l6">
              <input id="first_name" type="text" name="first_name" class="validate" required>
              <label for="first_name">First Name</label>
            </div>
            <div class="input-field col s12 m6 l6">
              <input id="last_name" type="text" name="last_name" class="validate" required>
              <label for="last_name">Last Name</label>
            </div>
            <div class="input-field col s12 m12 l12">
              <input id="email" type="email" name="register_username" class="validate" required>
              <label for="email">E-mail</label>
            </div>
            <div class="input-field col s12 m6 l6">
              <input id="password" type="password" data-error="Minimum 8 charracters required." minlength="8" name="register_password" required>
              <label for="password">Password</label>
            </div>
            <div class="input-field col s12 m6 l6">
              <input id="mobno" type="tel" name="mobile_number" data-error="Enter a valid 10 digit number." data-length="10" class="validate" required>
              <label for="mobno">Mobile Number</label>
            </div>
            <div class="input-field col s12 m6 l6">
              <input id="city" type="text" name="city" class="validate" required>
              <label for="city">City</label>
            </div>
            <div class="input-field col s12 m6 l6">
              <input id="state" type="text" name="state" class="validate" required>
              <label for="state">State</label>
            </div>
          </div>
          <button class="btn left modal-button" type="submit">Sign Up</button>
      </div>
    </form>
  </div>

  <div class="house-container fixed-top">
	<br>
    <div class="row mt">
	  <div class="col l7 m7 s12">
		<div class="img-slider">
		  <div class="avl-h-heading m-b">
			 <p>House Pictures</p>
			 <div></div>
		  </div>
		  <div class="house-img">
			<div style="background-image:url(<?php echo $image1  ?>)"></div>
			<div style="background-image:url(<?php echo $image2 ?>)"></div>
			<div style="background-image:url(<?php echo $image3 ?>)"></div>
			<div style="background-image:url(<?php echo $image4 ?>)"></div>
		  </div>
		  <div class="house-img-nav">
			<div class="house-img-nav-div" style="background-image:url(<?php echo $image1 ?>)"></div>
			<div class="house-img-nav-div" style="background-image:url(<?php echo $image2 ?>)"></div>
			<div class="house-img-nav-div" style="background-image:url(<?php echo $image3 ?>)"></div>
			<div class="house-img-nav-div" style="background-image:url(<?php echo $image4 ?>)"></div>
		  </div>
		</div>
	  </div>
    <div class="col l5 m5 s12">
		<div class="row">
		  <div class="col s7 m6 l7 pd-0">
			  <div class="avl-h-heading">
				 <p>House Details</p>
				 <div></div>
			  </div>
			  <ul class="disc">
				<li class="owner-name"><?php echo $first_name." ".$last_name ?></li>
				<li><i class="material-icons">work</i> &nbsp; <?php echo $profession ?></li>
				<li><i class="material-icons">location_on</i>  &nbsp; <?php echo $city." ".$state ?></li>
			  </ul>
		  </div>
		  <div class="col s5 m6 l5 pd-0">
			  <div class="avl-h-heading">
				 <p>House Rent</p>
				 <div></div>
			  </div>
			  <ul class="disc">
				<li class="owner-name">₹<?php echo $rent?></li>
				<li>per month</li>
			  </ul>
		  </div>
		</div>
		<div class="row">
		  <div class="col s12 m12 l12 pd-0">
			  <div class="avl-h-heading">
				 <p>House Description</p>
				 <div></div>
			  </div>
			  <p class="desc"><?php echo $discription ?></p>
		  </div>
		</div>
        <table class="striped">
			<thead>
			   <div class="avl-h-heading">
				  <p>House Specifications</p>
				  <div></div>
			   </div>
			</thead>
			<br>
			<tbody>
			  <tr>
				<td>House Type</td>
				<td> <?php echo $house_type ?></td>
			  </tr>
			  <tr>
				<td>Store Room</td>
				<td>
            <?php if ($store_room == 'true'){
                echo "Yes";
            } else {
                echo "Not Available";
            }?>
        </td>
			  </tr>
			  <tr>
				<td>Balcony</td>
				<td>
            <?php if ($bolcony == 'true'){
              echo "Yes";
            } else {
              echo "Not Available";
            }?>
        </td>
			  </tr>
			  <tr>
				<td>Parking Type</td>
				<td>
        <?php
          if($twowheeler == 'true')
          {
            echo "Two Wheeler ";
          }
          if($fourwheeler == 'true')
          {
            echo " Four Wheeler<br />";
          }
          if($twowheeler == 'false' && $fourwheeler== 'false' )
          {
            echo "Not Available";
          }
        ?>   
        </td>
        
			  </tr>
			</tbody>
        </table>
		<div class="row other-details">
			<div class="col l6 m6 s12 pd-0">
			  <div class="avl-h-heading">
					<p>Owner's Tenant Preferences</p>
					<div></div>
			  </div>
			  <ul>
        <?php
        if($married == "true")
        {
          echo "<li>Married</li>";
        }
        ?>    
        <?php
        if($girls == "true")
        {
          echo "<li>Girls</li>";
        }
        ?>
        <?php
        if($boys == "true")
        {
          echo "<li>Boys</li>";
        }
        ?>
        <?php
        if($family == "true")
        {
          echo "<li>Family</li>";
        }
        ?>
        <?php
        if($bachelor == "true")
        {
          echo "<li>Bachelor</li>";
        }
        ?>
        </li>

			  </ul>
			</div>
			<div class="col l6 m6 s12 pd-0">
			  <div class="avl-h-heading">
				<p>Ower's Restrictions</p>
				<div></div>
			  </div>
			  <ul>
        <?php
        if($smoking == "true")
        {
          echo "<li>Smoking</li>";
        }
        ?>
        <?php
        if($nonveg == "true")
        {
          echo "<li>Non Veg</li>";
        }
        ?>
        <?php
        if($alcohol == "true")
        {
          echo "<li>Alcohol</li>";
        }
        ?>

			  </ul>
			</div>
		</div>
	   </div>
    </div>
	<div class="row" align="center">
  <?php

  $redirect_url = "successful.php?id=$house_id"; 
  $url = "http://avekare.net16.net/email.php?email=$username&name=$first_name_login&id=$house_id"; 

   ?>
		<a href="<?php echo $url ?>" class="btn waves-effect waves-light theme-color-bg" name="action">Visit House
			<i class="material-icons right">send</i>
		</a>
	</div>
    </div>
  <!--Import jQuery before materialize.js-->
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="assets/js/materialize.min.js"></script>
  <script src="assets/js/dcalendar.picker.js"></script>
  <script src="assets/js/slick.js" type="text/javascript" charset="utf-8"></script>
  <script>
  $(document).ready(function(){
     var w = $(".house-img").outerWidth();
     var h = w * 0.5578;
     $('.house-img div').css("height",h);
     $('.house-img').slick({
     slidesToShow: 1,
     slidesToScroll: 1,
     fade: true,
     asNavFor: '.house-img-nav'
   });

   var navw = $(".house-img-nav div").outerWidth();
   var navh = h * 0.25;
   $('.house-img-nav div').css("height",navh);
   $('.house-img-nav').slick({
  	slidesToShow: 4,
  	slidesToScroll: 1,
  	asNavFor: '.house-img',
  	dots: true,
  	centerMode: true,
  	arrows:false,
  	focusOnSelect: true
   });
   
   $(".button-collapse").sideNav({
      closeOnClick: true,
      draggable: true,
   });
   $('#accomodate').modal();
   $('#login').modal();
   $('#signup').modal({
      endingTop: '0',
   });
  });
  </script>
</body>
</html>
