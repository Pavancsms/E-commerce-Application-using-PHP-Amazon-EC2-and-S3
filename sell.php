<?php
/**
 * User: Pavan
 */
session_start();
require 'aws/aws-autoloader.php';
use Aws\DynamoDb\DynamoDbClient;
use Aws\S3\S3Client;

?>
<html>
<head><title>Sell Items</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<form method="POST" enctype="multipart/form-data" action="sell.php">
    <fieldset>
        <legend><b>Sell Items Here:</b></legend>
        <label>Enter item description here
           <TEXTAREA NAME="description"
                     ROWS="4" COLS="50">
           </TEXTAREA>
        </label>
        <br/>
		<label>Enter Category
		  <input type="text" name="category"/>
        </label>
		<br/>
        <input type="hidden" name="MAX_FILE_SIZE" value="3000000"/>
        Image:<input name="image" type="file" height="50" width="50" /><br/>
        <input type="submit" value="Post" name="post"/>
        <input type="submit" value="Back" name="back"/>
    </fieldset>
</form>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

if (isset($_POST['back'])){
 header("Location:buy.php");
 exit;
}

if (isset($_POST['post'])) {

    $file = $_FILES['image']['name'];
    $file1 = $_FILES['image']['tmp_name'];

    //Connect to s3
    $s3client = S3Client::factory(array(
        'credentials' => array('aws_access_key_id' => 'AKIAJ6X2GNOE7PNOGYFA',
            'aws_secret_access_key' => '0ZR/Nsd4nAbN9bGl0Oru4KH60ebxTRcgbtQp0P83'),
        'region' => 'us-east-1'  // replace with your desired region
    ));

    //Uploading image to s3
    $result = $s3client->putObject(array(
        'Bucket' => "Ace",
        'Key' => $_FILES['image']['name'],
        'Body' => fopen($file1, "rb"),
        'ACL' => 'public-read'
    ));

    //S3 URL for the image
    $url = "https://s3.amazonaws.com/Ace/" . $file;

    //Connect to dynamodb http://Ace.s3.amazonaws.com/
    $client = DynamoDbClient::factory(array(
        'credentials' => array('aws_access_key_id' => 'AKIAIMOX7X2RSCVTH2SQ',
            'aws_secret_access_key' => '8hdkBo5wy3p5pkZ4qsgCWmgptOFOVaDzmMhtKGRR'),
        'region' => 'us-west-2'  // replace with your desired region
    ));

    $description = $_POST['description'];
    $user = $_SESSION["userName"];
    $category = $_POST['category'];
    $id = uniqid();
    //Store the data in Dynamodb
    $response = $client->putItem(array(
        'TableName' => 'items',
        'Item' => array(
            'itemID' => array('S' => $id),
            'userID' => array('S' => $user),
            'description' => array('S' => $description),
			'category'  => array('S' => $category),
			'image' => array('S' => $url)
        )
    ));
}
?>
</body>
</html>
