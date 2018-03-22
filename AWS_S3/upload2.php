<?php

	require './vendor/autoload.php';
	use Aws\S3\S3Client;
	use Aws\S3\Exception\S3Exception;

	// AWS info
	$bucketName = 'music-network';
	$IAM_KEY = '';
	$IAM_SECRET = '';

	try {
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

	$keyName = 'test_example/' . basename($_FILES["fileToUpload"]['name']);
	$pathInS3 = 'https://s3.us-east-1.amazonaws.com/' . $bucketName . '/' . $keyName;

	$errors = []; // Store all foreseen and unforseen errors here
	$fileExtensions = ['jpeg','jpg','png']; // Get all the file extensions
	$fileName = $_FILES['fileToUpload']['name'];
    $fileSize = $_FILES['fileToUpload']['size'];
    $fileExtension = strtolower(end(explode('.',$fileName)));

	try {
    	if (!in_array($fileExtension,$fileExtensions)) {
    		$errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file";
    		echo "This file extension is not allowed. Please upload a JPEG or PNG file";
    	}

 		if ($fileSize > 2000000) {
 			$errors[] = "This file is more than 2MB. Sorry, it has to be less than or equal to 2MB";
        	echo "This file is more than 2MB. Sorry, it has to be less than or equal to 2MB";
    	}

    	if (empty($errors)) {
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
		} 
	} catch (Exception $e) {
		die('Error:' . $e->getMessage());
	} 

// 	echo <<<_END
//     	<form method='post' action='upload2.php'>$error
//     	<span class='fieldname'>bucket</span><input type='text'
//     	  maxlength='16' name='bucket' value='$bucket'><br>
//     	<span class='fieldname'>file_Path</span><input type='text'
//     	  maxlength='16' name='file_Path' value='$file_Path'>
// _END;
	
?>
