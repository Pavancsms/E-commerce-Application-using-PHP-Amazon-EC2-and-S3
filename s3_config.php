<?php
// Bucket Name
$bucket="Ace";
if (!class_exists('S3'))require_once('S3.php');

//AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAJ6X2GNOE7PNOGYFA');
if (!defined('awsSecretKey')) define('awsSecretKey', '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');

$s3 = new S3(awsAccessKey, awsSecretKey);
$s3->putBucket($bucket, S3::ACL_PUBLIC_READ);
?>