<?php // to display a users' friends and followers
  require_once 'header.php';

  if (!$loggedin) 
    die();

  if (isset($_GET['view'])) 
    $view = sanitizeString($_GET['view']);
  else                      
    $view = $user;

  if ($view == $user) {
    $name1 = $name2 = "Your";
    $name3 = "You are";
  } else {
    $name1 = "<a href='members.php?view=$view'>$view</a>'s";
    $name2 = "$view's";
    $name3 = "$view is";
  }

  echo "<div class='main'>";

  // showProfile($view);

  // All the followers are saved into an array called $followers, and all the people being followed are placed in an array called $following
  $followers = array();
  $following = array();

  $result = queryMysql("SELECT * FROM friends WHERE user='$view'");
  $num = $result->num_rows;

  for ($j = 0 ; $j < $num ; ++$j) {
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $followers[$j] = $row['friend'];
  }

  $result = queryMysql("SELECT * FROM friends WHERE friend='$view'");
  $num = $result->num_rows;

  for ($j = 0 ; $j < $num ; ++$j) {
      $row = $result->fetch_array(MYSQLI_ASSOC);
      $following[$j] = $row['user'];
  }

  // The array_intersect function extracts all members common to both arrays and returns a new array containing only those people. This array is then stored in $mutual
  $mutual = array_intersect($followers, $following);

  // Now it’s possible to use the array_diff function for each of the $followers and $following arrays to keep only those people who are not mutual friends
  $followers = array_diff($followers, $mutual);
  $following = array_diff($following, $mutual);
  $friends   = FALSE;

  // display each category of members seperately
  if (sizeof($mutual)) {
    echo "<span class='subhead'>$name2 mutual friends</span><ul>";
    foreach($mutual as $friend)
      echo "<li><a href='members.php?view=$friend'>$friend</a>";
    echo "</ul>";
    $friends = TRUE;
  }

  if (sizeof($followers)) {
    echo "<span class='subhead'>$name2 followers</span><ul>";
    foreach($followers as $friend)
      echo "<li><a href='members.php?view=$friend'>$friend</a>";
    echo "</ul>";
    $friends = TRUE;
  }

  if (sizeof($following)) {
    echo "<span class='subhead'>$name3 following</span><ul>";
    foreach($following as $friend)
      echo "<li><a href='members.php?view=$friend'>$friend</a>";
    echo "</ul>";
    $friends = TRUE;
  }

  if (!$friends) 
    echo "<br>You don't have any friends :(<br>";
    echo "<br>Click <a href='members.php'>here</a> to see a list of all users.<br>";
?>

    </div><br>
  </body>
</html>
