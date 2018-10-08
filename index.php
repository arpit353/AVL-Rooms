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
    } else{
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
        $sql1 = "INSERT INTO user_details VALUES (null,?,?,?,?,?,?,?)";
         
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
            if(mysqli_stmt_execute($stmt1)){ ?>
                    <script type="text/javascript">
                        alert("Successfully created new account");
                        window.location = "index.php"
                    </script>
            <?php } else{ ?>
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

if(isset($_SESSION['username']))
{
	$username = $_SESSION['username'];
	$sql = "SELECT * FROM user_details WHERE user_email = '".$username."'";

	$result = mysqli_query($link, $sql);
	$row = mysqli_fetch_assoc($result); 

	$first_name = $row['first_name'];
	$last_name = $row['last_name'];
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>AVL Homes</title>
  <meta charset="utf-8"/>
  <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <link type="text/css" rel="stylesheet" href="assets/css/materialize.css"  media="screen,projection"/>
  <link href="assets/css/dcalendar.picker.css" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="assets/css/style.css" media="screen,projection"/>
  <link type="text/css" rel="stylesheet" href="assets/css/header.css" media="screen,projection"/>
  <link type="text/css" rel="stylesheet" href="assets/css/footer.css" media="screen,projection"/>
  <link rel="stylesheet" type="text/css" href="assets/css/slick.css">
  <link rel="stylesheet" type="text/css" href="assets/css/slick-theme.css">
  <script src="https://use.fontawesome.com/4ef4ce7ce4.js"></script>
  <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi"/>
    <!--Import jQuery before materialize.js-->
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="assets/js/materialize.min.js"></script>
  <script src="assets/js/dcalendar.picker.js"></script>
  <script src="assets/js/slick.js" type="text/javascript" charset="utf-8"></script>
</head>
<body>
  <!-- HEADER BEGINS -->
  <div class="avl-header avl-container show-on-med-and-large hide-on-small-only">
	<a href="index.php">
		<img src="assets/images/logo.png">
		<h5>AVL ROOMS</h5>
	</a>
	<ul>
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
		   		<a class="modal-trigger" href="profile.php">
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
      <a href="index.php" class="brand-logo center">AVL ROOMS</a>
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
            <a id="lgn" class="btn modal-button">Login</a>
            <p class="mb-4">New? Create a <span>new account</span>.</p>
            <a id="sgnup" class="btn modal-button">Sign Up</a>
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
  <!-- HEADER ENDS -->
  <div class="avl-container">
	<div class="tagline hide-on-small-only">
		<h4>Welcome to <span>your</span> humble abode</h4>
		<h3>Choose form a large number of accomodations</h3>
		<h3>to <span>feel at home away from home</span>.</h3>
	</div>
    <div class="tagline show-on-small hide-on-med-only hide-on-large-only">
        <h4>Welcome to <span>your</span> humble abode</h4>
        <h3>Choose form a large number of accomodations to <span>feel at home away from home</span>.</h3>
    </div>
	<div class="row">
    	<form action="city.php" method="GET" class="col s12 m10 l9" style="padding:0px">
    		<div class="search-bar"> 
    		  <div class="row">
    			<div class="col s5 m6 l5" style="border-right:1px solid #eee">
    			  <label><h6>LOCATION</h6></label>
    			  <input type="text" name="city" placeholder="Enter Destination" id="city" required>
    			</div>
    			<div class="col s5 m5 l5" style="padding: 0 0.75rem 0 1.45rem;">
    			  <label><h6>DATE</h6></label>
    			  <input id="" type ="date" name="date" placeholder="Check in date" required>
    			</div>
    			<div class="col s2 m3 l2" style="text-align:center">
    			  <button type="submit">
                    <span class="hide-on-small-only">Search</span>
                    <span class="show-on-small hide-on-med-only hide-on-large-only">Go</span>
                  </button> 	      
    		    </div> 
    		  </div>
    		</div>
    	</form>
	</div>
	
	<!--TOP CITIES-->
	<div class="tagline">
		<div class="avl-heading">
			<h4>Top Cities</h4>
			<div></div>
		</div>
	</div>
	<div class="top-cities">
		<img class="responsive-img" src="assets/images/bangalore.jpg">
		<img class="responsive-img" src="assets/images/chennai.jpg">
		<img class="responsive-img" src="assets/images/delhi.jpg">
		<img class="responsive-img" src="assets/images/mumbai.jpg">
		<img class="responsive-img" src="assets/images/PUNE.jpg">
		<img class="responsive-img" src="assets/images/HYDERABAD.jpg">
		<img class="responsive-img" src="assets/images/KOLKATA.jpg">
		<img class="responsive-img" src="assets/images/ahmedabad.jpg">
	</div>
	  <script>
      $('.top-cities').slick({
        infinite:true,
        responsive: [
            {
              breakpoint: 2000,
              settings: {
                slidesToShow: 6,
                slidesToScroll: 1
              }
            },
            {
              breakpoint: 770,
              settings: {
                slidesToShow: 5,
                slidesToScroll: 1
              }
            },
            {
              breakpoint: 600,
              settings: {
                slidesToShow: 4,
                slidesToScroll: 1
              }
            },
            {
              breakpoint: 310,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 1
              }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
      });
      </script>
    <!--About us-->
	<div class="tagline">
		<div class="avl-heading">
			<h4>About Us</h4>
			<div></div>
		</div>
		<p class="flow-text">AVL Rooms is a platform for easy and efficient communication between seller and buyer. We have earned the trust of our buyers thanks to our secure system. 
		We offer all varieties of houses you are looking for just in your price range.</p>
		<br>
	</div>
  </div>
	<footer class="page-footer">
		<div class="avl-container">
			<div class="row">
				<div class="col l6 s12 pd-0 tagicon">
					<h4 class="white-text"><b>AVL ROOMS</b></h4>
					<p class="flow-text white-text">Choose form a large number of accomodations to feel at home away from home.</p>
                    <a><img height="40px" width="40px" src="assets/images/icon/facebook.png"></a>
                    <a><img height="40px" width="40px" src="assets/images/icon/twitter.png"></a>
                    <a><img height="40px" width="40px" src="assets/images/icon/google-plus.png"></a>
				</div>
				<div class="col s6 l2 offset-l1 pd-0">
					<h5 class="white-text">Navigate to</h5>
					<ul>
						<li>
                            <?php if(isset($_SESSION['username'])) { ?>
                                <a href="accomodate.php">Accomodate</a>
                            <?php } ?>
                            <?php  if(!isset($_SESSION['username'])) { ?>
                                <a class="modal-trigger" href="#login">Accomodate</a>
                            <?php } ?>
                        </li>
                        <li>
                            <?php if(isset($_SESSION['username'])) { ?>
                                <a class="modal-trigger" href="profile.php">
                                    <?php echo $first_name." ".$last_name ?>
                                </a>
                            <?php } ?>
                            <?php  if(!isset($_SESSION['username'])) { ?>
                                <a class="modal-trigger" href="#login">Login</a>
                            <?php } ?>
                        </li>
                        <li>
                            <?php if(isset($_SESSION['username'])) { ?>
                                <a href="logout.php">Sign Out</a>
                            <?php } ?>
                            <?php  if(!isset($_SESSION['username'])) { ?>
                                <a class="modal-trigger" href="#signuo">Sign Up</a>
                            <?php } ?>
                        </li>
						<li><a href="#!">Contact Us</a></li>
					</ul>
				</div>
				<div class="col s6 l2 offset-l1 pd-0">
					<h5 class="white-text">Accomodations</h5>
					<ul>
						<li><a href="#!">Bangalore</a></li>
						<li><a href="#!">Delhi</a></li>
						<li><a href="#!">Mumbai</a></li>
						<li><a href="#!">Chennai</a></li>
						<li><a href="#!">Hyderabad</a></li>
						<li><a href="#!">Pune</a></li>
						<li><a href="#!">Ahmedabad</a></li>
						<li><a href="#!">Kolkata</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="footer-copyright">
			<div style="width:100%" class="avl-container">
			© 2017 Copyright Text
			<a class="grey-text text-lighten-4 right" href="#!"><b>< Developers /></b></a>
			</div>
		</div>
	</footer>
  <script>
$(document).ready(function(){
   $(".button-collapse").sideNav({
    closeOnClick: true,
    draggable: true,
   });
   $('#moveInDate').dcalendarpicker();
   $('#login').modal();
   $('#accomodate').modal();
   $('#signup').modal({
      endingTop: '30', // Ending top style attribute
   });
   $('#lgn').click(function(){
        $('#accomodate').modal('close');
        $('#login').modal('open');
   });
   $('#sgnup').click(function(){
        $('#accomodate').modal('close');
        $('#signup').modal('open');
   });
   // $('input.autocomplete').autocomplete({
   //  data: {
   //    "Apple": null,
   //    "Microsoft": null,
   //    "Moo":null,
   //    "Google": null
   //  },
   //  limit: 10, // The max amount of results that can be shown at once. Default: Infinity.
   //  onAutocomplete: function(val) {
   //    // Callback function when value is autcompleted.
   //  },
   //  minLength: 1,
   // });
});
  </script>
</body>
</html>
