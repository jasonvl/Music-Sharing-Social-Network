<?php 
  // Allows user to find other members in social network with the option to add/delete them as a friend 
  require_once 'header.php';

  if (!$loggedin) 
    die();

  echo "<div class='main'>";

  // To display a user's profile.
  if (isset($_GET['view'])) {
    $view = sanitizeString($_GET['view']);          
    $name = "$view's";
    
    echo "<h3>$name Profile</h3>";
    showProfile($view); // method in functions.php
    die("</div></body></html>");
  }

  // To add or delete a member from user's friends table.
  if (isset($_GET['add'])) {
    $add = sanitizeString($_GET['add']);

    $result = queryMysql("SELECT * FROM friends WHERE user='$add' AND friend='$user'");
    if (!$result->num_rows)
      queryMysql("INSERT INTO friends VALUES ('$add', '$user')");
  } elseif (isset($_GET['remove'])) { // delete friend
    $remove = sanitizeString($_GET['remove']);
    queryMysql("DELETE FROM friends WHERE user='$remove' AND friend='$user'");
  }

  // To list all other members.
  $result = queryMysql("SELECT user FROM members ORDER BY user");
  $num = $result->num_rows;

  echo "<h3>Other Members</h3><ul>";

  // Iterates through each and every member, fetching their details and then looking them up in the friends table to see if they are either being followed by or a follower of the user. If someone is both a follower and a followee, she is classed as a mutual friend.
  for ($j = 0 ; $j < $num ; ++$j) {
    $row = $result->fetch_array(MYSQLI_ASSOC);
    if ($row['user'] == $user) 
      continue;
    
    echo "<li><a href='members.php?view=" .
      $row['user'] . "'>" . $row['user'] . "</a>";
    $follow = "follow";

    // The variable $t1 is nonzero when the user is following another member, and $t2 is nonzero when another member is following the user. Depending on these values, text is displayed after each username, showing the relationship (if any) to the current user.
    $result1 = queryMysql("SELECT * FROM friends WHERE user='" . $row['user'] . "' AND friend='$user'");
    $t1      = $result1->num_rows;
    $result1 = queryMysql("SELECT * FROM friends WHERE user='$user' AND friend='" . $row['user'] . "'");
    $t2      = $result1->num_rows;

    if (($t1 + $t2) > 1) 
      echo " &harr; is a mutual friend";
    elseif ($t1)         
      echo " &larr; you are following";
    elseif ($t2) { 
      echo " &rarr; is following you";
      $follow = "recip"; 
    }
    
    // Depending on whether the user is following another member, a link is provided to either add or drop that member as a friend.
    if (!$t1) 
      echo " [<a href='members.php?add="   .$row['user'] . "'>$follow</a>]";
    else      
      echo " [<a href='members.php?remove=".$row['user'] . "'>drop</a>]";
  }
?>

    </ul></div>
  </body>
</html>
