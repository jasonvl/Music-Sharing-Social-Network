<?php 
  require_once 'header.php';

  if (!$loggedin) 
    die();

  echo "<div class='main'>";

  $view = sanitizeString($_GET['view']);
    
  echo "<h3>$Your Profile</h3>";
  showProfile($view); // method in functions.php
    die("</div></body></html>");

?>