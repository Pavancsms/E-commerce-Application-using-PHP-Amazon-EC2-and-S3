<?php
/**
 * User: Pavan
 */
session_start();
require 'aws/aws-autoloader.php';
use Aws\DynamoDb\DynamoDbClient;

?>
<html>
<head><title>Online Shopping</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<form method="POST" action="buy.php">
    <fieldset>
        <legend><b>Online Shopping:</b></legend>
        <input type="submit" value="Remove" name="removeItems"/>
        <input type="submit" value="Sell" name="sellItems"/>
        <input type="submit" value="Logout" name="logout"/>
        </label>
    </fieldset>
</form>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

if (isset($_POST['logout'])) {
    unset($_SESSION["userName"]);
    session_destroy();
    header("Location:rlogin.php");
    exit;
}

//connect to dynamodb
$client = DynamoDbClient::factory(array(
    'credentials' => array('aws_access_key_id' => 'xxxxxxxxxxxxxxxxxxx',
        'aws_secret_access_key' => 'xxxxxxxxxxxxxxxxxxxxx'),
    'region' => 'us-west-2'  // replace with your desired region

));

//fetch items from the table
$response = $client->scan(array(
    'TableName' => 'items'
));

//print the results in the form of a table
echo '<table>';
echo '<tr><th>User</th><th>Description</th><th>Category</th><th>Product</th></tr>';
foreach ($response['Items'] as $key => $value) {
    echo '<tr>';
    echo '<td>' . $value['userID']['S'] . '</td>';
    echo '<td>' . $value['description']['S'] . '</td>';
    echo '<td>' . $value['category']['S'] . '</td>';
    echo '<td><img src=' . $value['image']['S'] . '></img></td>';
    //echo '<td><img src="https://Ace.s3.amazonaws.com/Casio.jpg"></img></td>';
    echo '<tr/>';
}
echo '</table>';

if (isset($_POST['sellItems'])) {
    header("Location:sell.php");
    exit;
}
if (isset($_POST['removeItems'])) {
    header("Location:remove.php");
    exit;
}
?>
</body>
</html>
