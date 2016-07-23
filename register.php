<?php
/**
 * User: Pavan
 */
session_start();
require 'aws/aws-autoloader.php';
use Aws\DynamoDb\DynamoDbClient;

?>
<html>
<head><title>Register</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<form method="POST" action="register.php">
    <fieldset>
        <legend><b>Register:</b></legend>
        <label><b>User Name:</b>
            <input type="text" name="userName"/>
        </label>
        <br/>
        <label><b>Full Name:</b>
            <input type="text" name="fullName"/>
        </label>
        <br/>
        <label><b>Email:</b>
            <input type="text" name="email"/>
        </label>
        <br/>
        <label><b>Password:</b>
            <input type="password" name="password"/>
        </label>
        <br/>
        <br/>
        <input type="submit" value="Register" name="register"/>
    </fieldset>
</form>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
if (isset($_POST['register'])) {
    $user = $_POST['userName'];
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $pass = md5($_POST['password']);

    //connect to dynamodb
    $client = DynamoDbClient::factory(array(
        'credentials' => array('aws_access_key_id' => 'xxxxxxxxxxxxxxxxxx',
            'aws_secret_access_key' => 'xxxxxxxxxxxxxxxx'),
        'region' => 'us-west-2'  // replace with your desired region

    ));

$response = $client->query(array(
    'TableName' => 'UsersDetails',
    'KeyConditions' => array(
        'ID' => array(
            'ComparisonOperator' => 'EQ',
            'AttributeValueList' => array(
                array('S' => $user)
            )
        )
     )
   )
);
print_r ($response['Items']);
$count = count(array_filter($response['Items']));

   if($count > 0) {
        echo "Error! UserName already taken please select a different username";
    } 
   else {
        //save data in dynamodb
        $response = $client->putItem(array(
            'TableName' => 'UsersDetails',
            'Item' => array(
                'ID' => array('S' => $user),
                'PWD' => array('S' => $pass),
                'fullName' => array('S' => $fullName),
                'email' => array('S' => $email)
            )
        ));

        //go back to login
        header("Location:rlogin.php");
        exit;
    }
}
?>
</body>
</html>
