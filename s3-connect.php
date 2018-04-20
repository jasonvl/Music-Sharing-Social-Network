<?php
	require_once 'header.php';
	require_once'aws-credentials.php'; // where credentials are stored

	// For AWS S3
	require 'AWS_S3/vendor/autoload.php';
	use Aws\S3\S3Client;
	use Aws\S3\Exception\S3Exception;

	$userName = $user; // get user's name from header.php to use as name of bucket on S3

	// AWS info
	$bucketName = 'music-network';

	// Info is fetched from aws-credentials.php
	$IAM_KEY = $IAM_KEY; 
	$IAM_SECRET = $IAM_SECRET; 

	try {
		// Using credential file
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
?>
