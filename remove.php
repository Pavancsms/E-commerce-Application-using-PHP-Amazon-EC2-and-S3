<?php
/**
 * User: Pavan
 */
session_start();
require 'aws/aws-autoloader.php';
use Aws\DynamoDb\DynamoDbClient;

?>

<html>
<head><title> Online Shopping </title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<form method="POST" action="buy.php">
    <fieldset>
        <legend><b>Items For Sale:</b></legend>
        <input type="submit" value="Back" name="back"/>
        <input type="submit" value="Logout" name="logout"/>
        </label>
    </fieldset>
</form>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 'Off');

$client = DynamoDbClient::factory(array(
    'credentials' => array('aws_access_key_id' => 'xxxxxxxxxxxxx',
        'aws_secret_access_key' => 'xxxxxxxxxxxxxxxxx'),
    'region' => 'us-west-2'  // replace with your desired region

));

if (isset($_GET['delete'])) {
    $path = $_GET['delete'];
    #echo $path;
    $response = $client->deleteItem(array(
        'TableName' => 'items',
        'Key' => array(
            'itemID' => array(
                'S' => $path
            )
        )
    ));
    if(count($response>0)){
    echo "Succesfully deleted";
    }  
    else{
    echo "Error!!";
    }
    #header("location: buy.php");
}


if (isset($_POST['logout'])) {
    unset($_SESSION["userName"]);
    session_destroy();
    header("Location:rlogin.php");
    exit;
}

//scan and retrieve all the values from dynamodb
$uid = $_SESSION["userName"];
$response = $client->scan(array(
    'TableName' => 'items',
    'ExpressionAttributeValues' => array(
        ':val1' => array('S' => $uid)),
    'FilterExpression' => 'userID = :val1',
));

//print the results
echo '<table>';
echo '<tr><th>User</th><th>Description</th><th>Product</th></tr>';
foreach ($response['Items'] as $key => $value) {
    echo '<tr>';
    echo '<td>' . $value['userID']['S'] . '</td>';
    echo '<td>' . $value['description']['S'] . '</td>';
	echo '<td>' . $value['category']['S'] . '</td>';
    echo '<td><img src=' . $value['image']['S'] . '></td>';
    echo '<td><a href=remove.php?delete=' . $value['itemID']['S'] . '>Delete</a></td>';
    echo '<tr/>';
}
echo '</table>';

if (isset($_POST['back'])) {
    header("Location:buy.php");
    exit;
}
?>
</body>
</html>
