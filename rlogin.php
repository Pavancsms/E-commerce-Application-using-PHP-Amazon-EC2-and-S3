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
<form method="POST" action="rlogin.php">
    <fieldset>
        <legend><b>Login:</b></legend>
        <label><b>User Name:</b>
            <input type="text" name="userName"/>
        </label>
        <label>
            <b>Password:</b>
            <input type="password" name="password"/>
        </label>
        <br/>
        <br/>
        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="submit" value="Login" name="login"/>&nbsp;&nbsp;&nbsp;
            <input type="submit" value="Register" name="register"/>
        </label>
    </fieldset>
</form>
<?php
error_reporting(E_ALL);
ini_set('display_errors','On');

//goto registration screen
if(isset($_POST['register'])){
    header("Location:register.php");
    exit;
}
//check the credentials
if(isset($_POST['login'])){
    $user = $_POST['userName'];
    $pass = md5($_POST['password']);
//    try{

        //connect to dynamodb
        $client = DynamoDbClient::factory(array(
            'credentials' => array('aws_access_key_id' => 'AKIAJ6X2GNOE7PNOGYFA',
                'aws_secret_access_key' => '0ZR/Nsd4nAbN9bGl0Oru4KH60ebxTRcgbtQp0P83'),
            'region' => 'us-west-2'  // replace with your desired region

        ));

        //check against data in dynamodb
        $response = $client->getItem(array(
            'TableName' => 'UsersDetails',
            'Key' => array(
                'ID' => array('S' => $user), // Primary Key
                'PWD' => array('S' => $pass)
            )
        ));

        //if both match authenticate the user
        if(count($response)>0){
            $_SESSION["userName"]=$user;
            header("Location:buy.php");
            exit;
        }else{
            echo "Invalid UserName/Password, Try Again";
        }
}
?>
</body>
</html>
