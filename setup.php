<!DOCTYPE html>
<html>
  <head>
    <title>Setting up database</title>
  </head>
  <body>

    <h3>Setting up...</h3>

<?php
  // to set up MySQL tables users will use 
  // createTable() in functions.php is used to create the tables

  require_once 'functions.php';

  // to add more columns to these tables, MySQL DROP TABLE command must be issued before recreating a table
  
  // username user (indexed), password pass
  createTable('members',
              'user VARCHAR(16),
              pass VARCHAR(16),
              INDEX(user(6))');

  // username user (indexed), friend’s username friend
  createTable('friends',
              'user VARCHAR(16),
              friend VARCHAR(16),
              INDEX(user(6)),
              INDEX(friend(6))');

  // username user (indexed), friend’s username friend
  createTable('profiles',
              'user VARCHAR(16),
              text VARCHAR(4096),
              INDEX(user(6))');
?>

    <br>...done.
  </body>
</html>
