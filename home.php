<?php 
  require_once 'header.php';
  require_once 's3-connect.php';

    if (!$loggedin) 
      die();
  
  echo "<div class='main'>";

    $view = sanitizeString($_GET['view']);
    
    $userName = $user; // get user's name from header.php to use as name of 

    echo "<h3>$userName's Profile</h3>"; // get username from header.php
    showProfile($view); // method in functions.php

    // list all items in bucket
    $count = 0;
  echo "<h3>My Items in S3 Bucket</h3>";
  $objects = $s3->getIterator('ListObjects', array(
      "Bucket" => $bucketName,
  )); 

  // Display name of all items in S3 bucket in a table format with option for user to delete files.
  foreach ($objects as $object) {
    echo $object['Key'] . "\n";

    // delete not working! I'm still working on it
    echo '
      <tr>
          <td>
            <method="post" form action="#" > <!-- action of # indicates that form stays on the same page, simply suffixing the url with a # -->
            <input type="hidden" name="keyToDelete" value="<?php echo $object['Key']; ?>"
            <input type="submit" name="delete">
                  <?php echo $object['Key']; ?>
                  <?php
                    if (isset($_POST['delete'])) {
                    $s3->deleteObject([ 
                    'Bucket' => $bucket, 
                    'Key' => $object['keyToDelete']
                    ]);
                  ?>
            </form>
            </td>
        </tr>
        ';

    $count++;
  }  

  echo "-> $count items total";

    die("</div></body></html>");
?>
