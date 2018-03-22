<?php
  // This file contains features that each page of the project must have access to.
  // Dependency: functions.php

  session_start(); // start session to store info stored across different PHP files

  echo "<!DOCTYPE html>\n<html><head>";

  require_once 'functions.php';

  $userstr = ' (Guest)';

  // checks wether session variable "user" is currently assigned a value
  // if so, then the user is logged in 
  if (isset($_SESSION['user'])) {
    $user     = $_SESSION['user'];
    $loggedin = TRUE;
    $userstr  = " ($user)";
  }
  else $loggedin = FALSE;

  if ($loggedin) {
    echo "<br ><ul class='menu'>" .
         "<li><a href='home.php'>Home</a></li>".
         "<li><a href='members.php'>All Members</a></li>".
         "<li><a href='friends.php'>My Friends</a></li>".
         "<li><a href='s3Upload.php'>My Uploads</a></li>".
         "<li><a href='profile.php'>Edit Profile</a></li>".
         "<li><a href='logout.php'>Log out</a></li></ul><br>";
  }
  // not logged in menu - display menu to create an account
  else {
    echo ("<br><ul class='menu'>".
          "<li><a href='index.php'>Home</a></li>".
          "<li><a href='signup.php'>Sign up</a></li>".
          "<li><a href='login.php'>Log in</a></li></ul><br>".
          "<span class='info'>&#8658; You must be logged in to view this page.</span><br><br>"); // &#8658 for arrow symbol
  }
?>
