<?php
session_start();

if(!isset($_SESSION['logged']) || $_SESSION['logged']!=True) {
	header('Location: index.php');
	exit();
}

require 'db_form.php';

$city_values = array();
$gender_values = array();
$hobby_values = array();

$tables = array("cities","city_name",$city_values,"genders","gender_name",$gender_values,"hobbies","hobby_name",$hobby_values);

for($val = 0;$val < sizeof($tables) ; $val=$val+3){
  $sql = "SELECT * FROM ".$tables[$val]." ORDER BY ".$tables[$val+1]." ASC";
	$result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
		$result = mysqli_fetch_all($result, MYSQLI_ASSOC);
		foreach($result as $result_one) {
			$tables[$val+2][$result_one['id']] = $result_one[$tables[$val+1]];
		}
	}
}

$is_blank = 0;
if(!empty($_GET))
  foreach($_GET as $get){
    if(!empty($get))
      $is_blank = 1;
      else{
        $is_blank = 0;
        break;
      }
  }

$name_put = ''; $surname_put = ''; $bday_put = ''; $city_put = ''; $gender_put = ''; $hobbies_put = array();

if ($is_blank) {
    $name_put = $_GET['fname'];
    $surname_put = $_GET['lname'];
    $bday_put = $_GET['bday'];
    $city_put = $_GET['city'];
    if(!empty($_GET['gender']))
    $gender_put = $_GET['gender'];
    if(!empty($_GET['hobby']))
    $hobbies_put = array_combine(range(1, count($_GET['hobby'])), array_values($_GET['hobby']));
    print_r("Name: ".$name_put.'<br> ');
    echo "Surame: ".$surname_put.'<br> '."Birth date: ".$bday_put.'<br> '."City: ".$city_put.'<br> '."Gender: ".$gender_put.'<br>'."Hobbies: ".implode(' ',$hobbies_put);
}
else
echo "Please complete the form!";

?>

<!DOCTYPE html>
<html>
<body>

<h2>General form</h2>

<form method="get">
  <label for="fname">First name:</label><br>
  <input type="text" id="fname" name="fname" value="<?php echo $name_put; ?>"><br>

  <label for="lname">Last name:</label><br>
  <input type="text" id="lname" name="lname" value="<?php echo $surname_put; ?>"><br><br>

  <label for="bday">When were you born?</label><br>
  <input type="date" id="bday" name="bday" value="<?php echo $bday_put; ?>">

  <div><br> Which city were you born?</div>
  <input list="city" name="city" value="<?php echo $city_put; echo '">';
  echo '<datalist id="city">';
  foreach($tables[2] as $city)
    echo '<option value="'.$city.'">';
  echo '</datalist><br>' ?>

  <div><br> What is your gender?</div>
  <?php
  foreach($tables[5] as $gender){
    $checked = $gender_put==$gender ? 'checked' : '';
    echo '<input type="radio" id="'.$gender.'" name="gender" value="'.$gender.'" '.$checked.' >';
    echo '<label for="'.$gender.'">'.$gender.'</label><br>';
  }
  echo '<br>';
  ?>

  <div> What is your hobby?</div>
  <?php
  $count = 0;
  foreach($tables[8] as $hobby){
    $checked = array_search($hobby,$hobbies_put) ? 'checked' : '';
    $count++;
    echo '<input type="checkbox" id="hobby'.$count.'" name="hobby[]" value="'.$hobby.'" '.$checked.' >';
    echo '<label for="hobby'.$count.'"> '.$hobby.'</label><br>';
  }
  echo '<br>';
  ?>
  <input type="submit" value="Submit">
</form>

<a href="logout.php">Logout</a>

</body>
</html>