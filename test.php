<?php 

if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$mobile = $_POST['mobile'];
	$mobile_watsapp = $_POST['mobile_watsapp'];
	$email = $_POST['email'];
	$profession = $_POST['profession'];
	$city = $_POST['city'];
	$country = $_POST['country'];
	$house_type = $_POST['house_type'];

	$bolcony = 'false';
	if(isset($_POST['bolcony']))
	{
		$bolcony = $_POST['bolcony'];
	}

	$storeroom = 'false';
	if(isset($_POST['storeroom']))
  {
     $storeroom = $_POST['storeroom'];
  }

  $twowheeler = 'false';
  if(isset($_POST['twowheeler']))
  {
    $twowheeler = $_POST['twowheeler'];
  }

  $fourwheeler = 'false';
  if(isset($_POST['$fourwheeler']))
  {
     $fourwheeler = $_POST['fourwheeler'];
  }

  $discription = $_POST['discription'];


  $bachelor = 'false';
  if(isset($_POST['bachelor']))
  {
    $bachelor = $_POST['bachelor'];
  }

  $married = 'married';
  if(isset($_POST['married']))
  {
    $married = $_POST['married'];
  }

  $family = 'false';
  if(isset($_POST['family']))
  {
    $family = $_POST['family'];
  }

  $unmarried = 'false';
  if(isset($_POST['unmarried']))
  {
    $unmarried = $_POST['unmarried'];
  }

  $boys = 'false';
  if(isset($_POST['boys']))
  {
    $boys = $_POST['boys'];
  }

  $girls = 'false';
  if(isset($_POST['girls']))
  {
    $girls = $_POST['girls'];
  }

  $smoking = 'false';
  if(isset($_POST['smoking']))
  {
    $smoking = $_POST['smoking'];
  }
  $alcohol = 'false';
  if(isset($_POST['alcohol']))
  {
    $alcohol = $_POST['alcohol'];
  }
  $nonveg = 'false';
  if(isset($_POST['nonveg']))
  {
    $nonveg = $_POST['nonveg'];
  }

  $imgContent1 = null;
  $imgContent2 = null;
  $imgContent3 = null;
  $imgContent4 = null;  

  $check1 = getimagesize($_FILES["image1"]["tmp_name"]);
  if($check1 !== false){
        $image1 = $_FILES['image1']['tmp_name'];
        $imgContent1 = addslashes(file_get_contents($image1));
  }

  $check2 = getimagesize($_FILES["image2"]["tmp_name"]);
  if($check2 !== false){
        $image2 = $_FILES['image2']['tmp_name'];
        $imgContent2 = addslashes(file_get_contents($image2));
  }

  $check3 = getimagesize($_FILES["image3"]["tmp_name"]);
  if($check3 !== false){
        $image3 = $_FILES['image3']['tmp_name'];
        $imgContent3 = addslashes(file_get_contents($image3));
  }

  $check4 = getimagesize($_FILES["image4"]["tmp_name"]);
  if($check4 !== false){
        $image4 = $_FILES['image4']['tmp_name'];
        $imgContent4 = addslashes(file_get_contents($image4));
  }


  $other_restrictions = $_POST['other_restrictions'];

  $flat = $_POST['flat'];
  $colony = $_POST['colony'];
  $landmark = $_POST['landmark'];
  $city = $_POST['city'];
  $state = $_POST['state'];
  $pincode = $_POST['pincode'];
  $contact = $_POST['contact'];
  $rent = $_POST['rent'];
  $date = $_POST['date'];

  $link = mysqli_connect("localhost", "vedant1727", "Mp7389207016$", "avlrooms");

  if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
  }

  $sql = "INSERT INTO houses VALUES ('$first_name', '$last_name', '$mobile','$mobile_watsapp','$email','$profession','$city','$country','$house_type','$bolcony','$storeroom','$twowheeler','$fourwheeler','$discription','$bachelor','$family','$married','$boys','$girls','$smoking','$alcohol','$nonveg','$other_restrictions','$flat','$colony','$landmark','$city','$state','$pincode','$contact','$rent','$date','$imgContent1','$imgContent2','$imgContent3','$imgContent4')";

  if(mysqli_query($link, $sql)){
    echo "Records inserted successfully.";
  } else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
  }

  mysqli_close($link);

}


?>

<!DOCTYPE html>
<html>
<head>
  <title>AVL Homes</title>
  <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="assets/css/normalize.css"  media="screen,projection"/>  
  <link type="text/css" rel="stylesheet" href="assets/css/set2.css"  media="screen,projection"/>  
  <link type="text/css" rel="stylesheet" href="assets/css/materialize.css"  media="screen,projection"/>  
  <link type="text/css" rel="stylesheet" href="assets/css/header.css"  media="screen,projection"/>
  <link type="text/css" rel="stylesheet" href="assets/css/accomodate.css"  media="screen,projection"/>
  <link rel="stylesheet" href="assets/css/validin.css" type="text/css" media="all" />
  <script src="https://use.fontawesome.com/4ef4ce7ce4.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
  <div class="avl-header avl-container">
	<a href="index.html">
		<img src="assets/images/logo.png">
		<h5>AVL ROOMS</h5>
	</a>
	<ul>
		<a href=""><li>ACCOMODATE</li></a>
		<a href=""><li>LOGIN</li></a>
		<a href=""><li>SIGN UP</li></a>
	</ul>
  </div>
<!--HEADER-->
  <div class="avl-container">
  	<div class="tagline">
  	  <h4>Looking for a <span>Tenant</span></h4>
  	  <h3>Join our large family of happy landlords.</h3>
  	</div>
  </div>


    <form action="upload.php" method="POST" >
        Select image to upload:
        <input type="file" name="image1"/>
        <input type="submit" name="submit" value="UPLOAD"/>
    </form>


</body>

  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script type="text/javascript" src="assets/js/materialize.min.js"></script>
  <script src="assets/js/validin.js"></script>  
<script>
$('form').validin();

function toggle(source) {
  checkboxes = document.getElementsByClassName('pref');
  for(var i=0, n=checkboxes.length; i<n; i++) {
	checkboxes[i].checked = source.checked;
  }
}
function change(source) {
  checkboxes = document.getElementsByClassName('prefnot');

  if(source.checked==true){ 
  for(var i=0, n=checkboxes.length; i<n; i++) {
    checkboxes[i].checked = false;
  } }
} 

function deselect(source){
  x = document.getElementById("no-w");
  if(source.checked==true){
    x.checked = false;
  }
}


var flag = 0;
function popup(source){
  var w = document.getElementById("pg1");
  var x = document.getElementById("pg2");
  var y = document.getElementById("pg3");
  var z = document.getElementById("pg4");
  var btn = document.getElementById("bt");
  if(flag == 0){
  x.style.display = "block";
  source.href = "#pg2";
  flag++;
  }
  else if(flag == 1){
    y.style.display = "block";
    y.style.height = "100%";
    flag++;
    source.href = "#pg3";
  }
  else if(flag == 2){
    z.style.display = "block";
    z.style.height = "100%";
    flag++;
    source.style.display = "none";
    btn.style.display = "block";
    btn.style.height = "100%";
    source.href = "#pg4";
  }
}

  $(document).ready(function(){  
  $("a").on('click', function(event) {

    if (this.hash !== "") {
      event.preventDefault();

      var hash = this.hash;

      $('html, body').animate({
        scrollTop: $(hash).offset().top
      }, 1000, function(){
        window.location.hash = hash;
      });
    } 
  });

  });

</script> 

</body>
</html>
