<?php

function cors()
{

    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
}

cors();

header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

require_once realpath(__DIR__ . '/../vendor/autoload.php');

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$frontend_uri = $_ENV['FRONTEND_URI'];

header("Access-Control-Allow-Origin: $frontend_uri");

header("Access-Control-Allow-Methods: PUT, GET, POST");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

// Your PHP code to handle requests will go here

$_POST["login"] = true;

$data = json_decode(file_get_contents('php://input'), true);


if (isset($data['password'])) {
    // echo json_encode(['status' => 'success', 'message' => 'Message received: ' . $data['password']]);
}
if (isset($data['email'])) {
    // echo json_encode(['status' => 'success', 'message' => 'Message received: ' . $data['email']]);
} else {
    // echo json_encode(['status' => 'error', 'message' => 'No message received']);
}



// include the configs / constants for the database connection
require_once("../config/db.php");
/**
 * Class login
 * handles the user's login and logout process
 */
require_once("../classes/Login.php");


$login = new Login($data['email'], $data['password']);



$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
$txt = ($login->errors);
$txt2 = ($login->messages);
fwrite($myfile, implode($txt));
fwrite($myfile, implode($txt2));
fclose($myfile);

if (implode($txt) != "") echo json_encode(['status' => 'error', 'message' => implode($txt)]);

// $isLoggedIn= $login->isUserLoggedIn(); 

// if ($isLoggedIn == true)
//     echo json_encode(['status' => 'success', 'message' => '1']);
// else
//     echo json_encode(['status' => 'error', 'message' => '0']); 
