<?php 
  require_once 'header.php';

  echo ("<h3>Please enter your details to sign up</h3>");

  $error = $user = $pass = "";

  // destroy old session
  if (isset($_SESSION['user'])) 
    destroySession();

  if (isset($_POST['user'])) {
    // sanitize user input
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);

    // both a username and passoword are needed to create an account
    if ($user == "" || $pass == "")
      $error = "Not all fields were entered<br><br>";
    else { // check if username is available and create an account if it is
      $result = queryMysql("SELECT * FROM members WHERE user='$user'");

      if ($result->num_rows)
        $error = "That username already exists<br><br>";
      else {
        queryMysql("INSERT INTO members VALUES('$user', '$pass')");
        die("<h4>Account created</h4>Please Log in.<br><br>");
      }
    }
  }

  // load signup.php (this file) in the browser
  echo <<<_END
    <form method='post' action='signup.php'>$error
    <span class='fieldname'>Username</span>
    <input type='text' maxlength='16' name='user' value='$user'
      onBlur='checkUser(this)'><span id='info'></span><br>
    <span class='fieldname'>Password</span>
    <input type='text' maxlength='16' name='pass'
      value='$pass'><br>
_END;
?>
    <!-- HTML to display 'Sign up' button -->
    <span class='fieldname'>&nbsp;</span>
    <input type='submit' value='Sign up'>
    </form></div><br>
  </body>
</html>
