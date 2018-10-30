<?php
// Include config file
require_once 'config.php';

session_start();

// Define variables and initialize with empty values
$username = $password = "default";
$username_err = $password_err = "";

if(isset($_SESSION['username']))
{
	$username = $_SESSION['username'];
	$user_data_sql = "SELECT * FROM user_details WHERE user_email = '".$username."'";
	$user_id_sql = "SELECT * FROM users WHERE username = '".$username."'";

	$result = mysqli_query($link, $user_data_sql);
	$row = mysqli_fetch_assoc($result);

	$first_name = $row['first_name'];
	$last_name = $row['last_name'];
	$email= $row['user_email'];
	$mobile_number= $row['mobile'];
	$city= $row['city'];
	$state= $row['state'];
	$id = $row['id'];

	$result = mysqli_query($link, $user_id_sql);
	$row = mysqli_fetch_assoc($result);

	$user_id = $row['id'];

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		
		$first_name = $_POST['first_name'];
	    $last_name= $_POST['last_name'];
	    $email = $_POST['register_username'];
	    $city = $_POST['city'];
	    $state = $_POST['state'];
	    $mobile = $_POST['mobile_number'];


		$update_details_sql = "UPDATE user_details SET first_name='".$first_name."', last_name='".$last_name."', username='".$email."', mobile='".$mobile_number."', city='".$city."', state='".$state."' WHERE id= $id";
		$abc = "SELECT * FROM users";
		if($stmt1 = mysqli_prepare($link, $update_details_sql)){
	        // Bind variables to the prepared statement as parameters
	        // mysqli_stmt_bind_param($stmt1,"ssssss",$new_fname); 
	        // Attempt to execute the prepared statement
	        if(mysqli_stmt_execute($stmt1)){
	        	// header("location: profile.php");
	        	?>

	        	<?php 
	        } else {

	            ?>
	            <script type="text/javascript">
	            	alert("Something went wrong. Please try again later.");
	            </script>
	            <?php
	        }
	    }
	}
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>AVL Homes</title>
  <meta charset="utf-8"/>
  <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="assets/css/materialize.css"  media="screen,projection"/>
  <link href="assets/css/dcalendar.picker.css" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="assets/css/profile.css" media="screen,projection"/>
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
		   		<a class="modal-trigger" href="#login">ACCOMODATE</a>
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
  <div class="avl-container">
	<div class="tagline">
		<h4>Welcome here<span><?php echo ($first_name)." ".($last_name) ?></span></h4>
		<p>You can change your account details here.</p>
	</div>
  </div>
	<div class="avl-container">
		<div class="row">
		    <form method="POST" action="profile.php" class="col s12 m10 l9 edit-form">
		      <div style="margin-bottom: 10px" class="row">
				<div class="input-field col s12 m6 l6">
				  <input value='<?php echo $first_name?>' disabled="true" id="first_name" type="text" class="validate" name="first_name">
				  <label for="first_name"><b>First Name</b></label>
				</div>
				<div class="input-field col s12 m6 l6">
				  <input value="<?php echo $last_name?>" disabled="true" id="last_name" type="text" class="validate" name="last_name">
				  <label for="last_name"><b>Last Name</b></label>
				</div>
			  </div>
			  <div style="margin-bottom: 10px" class="row">
				<div class="input-field col s12 m6 l6">
				  <input value="<?php echo $email?>" disabled="true" id="email" type="text" class="validate" name="register_username">
				  <label for="email"><b>E-mail</b></label>
				</div>
				<div class="input-field col s12 m6 l6">
				  <input value="<?php echo $mobile_number?>" disabled="true" id="mobno" type="text" class="validate" name="mobile_number">
				  <label for="mobno"><b>Mobile Number</b></label>
				</div>
			  </div>
			  <div style="margin-bottom: 0px" class="row">
				<div class="input-field col s12 m6 l6">
				  <input value="<?php echo $city?>" disabled="true" id="city" type="text" class="validate" name="city">
				  <label for="city"><b>City</b></label>
				</div>
				<div class="input-field col s12 m6 l6">
				  <input value="<?php echo $state?>" disabled="true" id="state" type="text" class="validate" name="state">
				  <label for="state"><b>State</b></label>
				</div>
			  </div>
			  <center style="margin-top: 15px">
				  <a class="btn" id="enable-edit"><i class="material-icons right">edit</i><b>Edit</b></a>
				  <a class="btn" id="change-pass"><i class="material-icons right">edit</i><b>Change Password</b></a>
				  <button class="btn" id="save-change" type="submit" name="action">Save Changes
				    <i class="material-icons right">send</i>
				  </button>
				  <a class="btn" id="cancel-edit"><i class="material-icons right">close</i>Cancel</a>
			  </center>
		    </form>
	  	</div>
	</div>
	<footer class="page-footer">
	<div class="avl-container">
	<div class="row">
		<div class="col l6 s12 pd-0">
			<h4 class="white-text">AVL ROOMS</h4>
			<p class="flow-text white-text">Choose form a large number of accomodations to feel at home away from home.</p>
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
	                    <a class="modal-trigger" href="#">
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
	                    <a class="modal-trigger" href="#signup">Sign Up</a>
	                <?php } ?>
	            </li>
				<li><a href="#!">Contact</a></li>
			</ul>
		</div>
		<div class="col s6 l3 pd-0">
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
	<div class="avl-container">
	© 2017 Copyright Text
	<a class="grey-text text-lighten-4 right" href="#!">More Links</a>
	</div>
	</div>
	</footer>
  <script>
  $(document).ready(function(){
   $(".button-collapse").sideNav({
    closeOnClick: true,
    draggable: true,
   });
   $('#enable-edit').click(function(){
   	 $(".edit-form input[type='text']").prop('disabled', false);
   	 $('#enable-edit').css('display', 'none');
   	 $('#save-change').css('display', 'inline-block');
   	 $('#cancel-edit').css('display', 'inline-block');
   	 $('input[type=text]:disabled,input[type=password]:disabled').css('border-bottom','1px solid #eee')
   })
   $('#cancel-edit').click(function(){
   	 $(".edit-form input[type='text']").prop('disabled', true);
   	 $('#enable-edit').css('display', 'inline-block');
   	 $('#save-change').css('display', 'none');
   	 $('#cancel-edit').css('display', 'none')
   	 $('input[type=text]:disabled,input[type=password]:disabled').css('border-bottom','1px dotted #e0')
   })
  });
  </script>
</body>
</html>
