<?php
// Bucket Name
$bucket="Ace";
if (!class_exists('S3'))require_once('S3.php');

//AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', 'xxxxxxxxxxxxxxxxxx');
if (!defined('awsSecretKey')) define('awsSecretKey', 'xxxxxxxxxxxxxx');

$s3 = new S3(awsAccessKey, awsSecretKey);
$s3->putBucket($bucket, S3::ACL_PUBLIC_READ);
?>
