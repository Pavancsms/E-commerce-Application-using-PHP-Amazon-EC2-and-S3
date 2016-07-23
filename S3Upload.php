<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>S3 tutorial</title>
        <link href="style.css" rel="stylesheet" type="text/css">
    </head>

<body>
<?php
include('image_check1.php'); // getExtension Method
$msg='';
if($_SERVER['REQUEST_METHOD'] == "POST")
{
$name = $_FILES['file']['name'];
$size = $_FILES['file']['size'];
$tmp = $_FILES['file']['tmp_name'];
$ext = getExtension($name);

if(strlen($name) > 0)
{
// File format validation
if(in_array($ext,$valid_formats))
{
// File size validation
#if($size<(1024*1024))
#{
include('s3_config.php');
//Rename image name. 
$actual_image_name = time().".".$ext;

if($s3->putObjectFile($tmp, $bucket , $actual_image_name, S3::ACL_PUBLIC_READ) )
{
$msg = "S3 Upload Successful."; 
$s3file='http://'.$bucket.'.s3.amazonaws.com/'.$actual_image_name;
echo "<img src='$s3file'/> \n";
echo 'S3 File URL: '.$s3file;
}
#else
#$msg = "S3 Upload Fail.";

#}
else
$msg = "Image size Max 1 MB";

}
else
$msg = "Invalid file, please upload image file.";

}
else
$msg = "Please select image file.";

}
?>


<form action="" method='post' enctype="multipart/form-data">
Upload image file here
<input type='file' name='file'/> <input type='submit' value='Upload Image'/>
<?php echo $msg; ?>
</form>
</html>