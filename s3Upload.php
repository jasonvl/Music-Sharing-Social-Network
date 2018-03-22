<?php
	require_once 'header.php';

	// for AWS S3
	require 'AWS_S3/vendor/autoload.php';
	use Aws\S3\S3Client;
	use Aws\S3\Exception\S3Exception;
	// use Aws\Common\Credentials\Credentials;

	// AWS info
	$bucketName = 'music-network';
	$IAM_KEY = '';
	$IAM_SECRET = '';

	try {
			// using credential file - not working :(
			// $s3 = S3Client::factory(
			// 	array(
   //  				'profile' => 'my_profile',
   //  				'version' => 'latest',
   //  				'region'  => 'us-east-1'
   //  			));
		
			// Using hard-coded credentials
			$s3 = new S3Client([
    		'version'     => 'latest',
    		'region'      => 'us-east-1',
    		'credentials' => [
        		'key'    => $IAM_KEY,
        		'secret' => $IAM_SECRET ,
    		],
		]);
	} catch (Exception $e) { //  more info https://docs.aws.amazon.com/aws-sdk-php/v3/api/class-Aws.S3.Exception.S3Exception.html
		die("Error: " . $e->getMessage());
	}

	echo <<<_END
	<form action="s3Upload.php" method="post" enctype="multipart/form-data">
	    Select image to upload:
	    <input type="file" name="fileToUpload" id="fileToUpload">
	    <input type="submit" name="submit" value="Upload File Now">
	</form>
_END;

	$errors = []; // Store all foreseen and unforseen errors here
	$fileExtensions = ['jpeg','JPEG','jpg','JPG','png','PNG']; // Get all the file extensions
	$fileName = $_FILES['fileToUpload']['name'];
    $fileSize = $_FILES['fileToUpload']['size'];
    $fileExtension = strtolower(end(explode('.', $fileName)));

    $userName = $user; // get user's name from header.php to use as name of bucket on S3

	try {
		// only allow certain file extensions
    	if (!in_array($fileExtension, $fileExtensions)) {
    		$errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file.";
    	}
    	// also check filesize
 		if ($fileSize > 10000000) {
 			$errors[] = "This file is more than 10MB. Sorry, it has to be less than or equal to 10MB.";
    	}
    	// only enters the loop if user has submitted (checks he type="submit" in the form script above in echo)
    	if (isset($_POST['submit'])) { 
    		// proceed to uploading if file extension and size pass specifications
    		if (empty($errors)) {
    			$keyName = $userName . '/' . basename($_FILES["fileToUpload"]['name']); // name of bucket will be the same as the user's username 
				// $pathInS3 = 'https://s3.us-east-1.amazonaws.com/' . $bucketName . '/' . $keyName;
       			// upload file to S3
       			try {
        			$file = $_FILES["fileToUpload"]['tmp_name'];
					$s3->putObject(
						array(
							'Bucket'=>$bucketName,
							'Key' =>  $keyName,
							'SourceFile' => $file,
							'StorageClass' => 'REDUCED_REDUNDANCY'
						)
					);
        		} catch (S3Exception $e) {
					die('Error:' . $e->getMessage());
				}
				echo 'Done';
			} else {
				foreach ($errors as $error) {
                	echo $error;
            	}
			} 
		}
	} catch (Exception $e) {
		die('Error:' . $e->getMessage());
	}

	// list all items in bucket
	$objects = $s3->getIterator('ListObjects', array(
    "Bucket" => $bucketName,
    "Prefix" => 'userName/' //must have the trailing forward slash "/"
));

foreach ($objects as $object) {
    echo $object['Key'] . "<br>";
}  

?>
