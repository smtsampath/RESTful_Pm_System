<?php

require_once '../includes/DbHandler.php';
require_once '../includes/PassHash.php';
require '../libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

// User id from db - Global Variable
$user_id = NULL;

/**
 * Adding Middle Layer to authenticate every request
 * Checking if the request has valid api key in the 'Authorization' header
 */
function authenticate(\Slim\Route $route) {
    // Getting request headers
    $headers = apache_request_headers();
    $response = array();
    $app = \Slim\Slim::getInstance();

    // Verifying Authorization Header
    if (isset($headers['Authorization'])) {
        $db = new DbHandler();

        // get the api key
        $api_key = $headers['Authorization'];
        // validating api key
        if (!$db->isValidApiKey($api_key)) {
            // api key is not present in users table
            $response["error"] = true;
            $response['error_id'] = 401;
            $response['error_title'] = 'Invalid Api key';
            $response['error_message'] = 'Access Denied. Invalid Api key';
            // echo json response
            echoRespnse(401, $response);
            $app->stop();
        } else {
            global $user_id;
            // get user primary key id
            $user_id = $db->getUserId($api_key);
        }
    } else {
        // api key is missing in header
        $response["error"] = true;
        $response['error_id'] = 400;
        $response['error_title'] = 'Missing Api Key';
        $response['error_message'] = 'Api key is misssing';
        // echo json response
        echoRespnse(400, $response);
        $app->stop();
    }
}

/* ------------- METHODS WITHOUT AUTHENTICATION ------------------ */

/**
 * User Registration
 * url - /register.php
 * method - POST
 * @params - first_name, last_name, email, password
 */

$app->post('/register.php', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('first_name', 'last_name', 'email', 'password'));

            $response = array();

            // reading post params
            $first_name = $app->request->post('first_name');
            $last_name = $app->request->post('last_name');
            $email = $app->request->post('email');
            $password = $app->request->post('password');

            // validating email address
            validateEmail($email);

            $db = new DbHandler();
            $res = $db->createUser($first_name, $last_name, $email, $password);

            if ($res == USER_CREATED_SUCCESSFULLY) {
                $response["error"] = false;                 
                $response['error_id'] = 200;
                $response['error_title'] = 'User Registered';
                $response['error_message'] = 'You are successfully registered';

                // echo json response
                echoRespnse(200, $response);              
                
            } else if ($res == USER_CREATE_FAILED) {
                $response["error"] = true;
                $response['error_id'] = 400;
                $response['error_title'] = 'Registration Error';
                $response['error_message'] = 'Oops! An error occurred while registereing';
                
                // echo json response
                echoRespnse(400, $response);
                
            } else if ($res == USER_ALREADY_EXISTED) {
                $response["error"] = true;
                $response['error_id'] = 101;
                $response['error_title'] = 'Email Already Existed';
                $response['error_message'] = 'Sorry, this email already existed';
                
                // echo json response
                echoRespnse(101, $response);
            } 
    
        });


/**
 * User Login
 * url - /login.php
 * method - POST
 * @params - email, password
 */

$app->post('/login.php', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('email', 'password'));

            // reading post params
            $email = $app->request()->post('email');
            $password = $app->request()->post('password');
            $response = array();

            $db = new DbHandler();
            // check for correct email and password
            if ($db->checkLogin($email, $password)) {
                // get the user by email
                $user = $db->getUserByEmail($email);

                if ($user != NULL) {
                    $response["error"] = false;
                    $response['first_name'] = $user['first_name'];
                    $response['last_name'] = $user['last_name'];
                    $response['email'] = $user['email'];
                    $response['apiKey'] = $user['api_key'];
                    $response['created_at'] = $user['created_at'];
                } else {
                    $response['error'] = true;                    
                    $response['error_id'] = 400;
                    $response['error_title'] = 'Unknown Error';
                    $response['error_message'] = 'An error occurred. Please try again';
                
                    // echo json response
                    echoRespnse(400, $response);
                }
                 // echo json response
                echoRespnse(200, $response);
                
            } else {
                // user credentials are wrong
                $response['error'] = true;
                $response['error_id'] = 401;
                $response['error_title'] = 'Login Failure';
                $response['error_message'] = 'Email or Password was Invalid!';
                
                // echo json response
                echoRespnse(401, $response);
            }

            
        });


/* ---------------- METHODS WITH AUTHENTICATION ------------------ */

/**
 * Listing all users excluding requested user
 * method GET
 * url /list_all_users.php          
 */

$app->get('/list_all_users.php', 'authenticate', function() {
            global $user_id;
            $response = array();
            $db = new DbHandler();

            // fetching all users
            $result = $db->getAllUsers($user_id);
            
            if ($result) { 
                $response["error"] = false;
                $response["users"] = array();
                // looping through result and preparing user array
                while ($users = $result->fetch_assoc()) {
                    $tmp = array();
                    $tmp["user_id"] = $users["user_id"];
                    $tmp["email"] = $users["email"];
                    $tmp["first_name"] = $users["first_name"];
                    $tmp["last_name"] = $users["last_name"];
                    array_push($response["users"], $tmp);
                }
                
                // echo json response
                echoRespnse(200, $response);
                
            } else {
                // if query failed,
                $response['error'] = true;
                $response['error_id'] = 401;
                $response['error_title'] = 'Query Error';
                $response['error_message'] = 'An error occurred while fetching the Users';
                
                // echo json response
                echoRespnse(401, $response);
            }
        });

/**
 * send a message one user to another
 * method POST
 * url /send_message.php         
 */

$app->post('/send_message.php', 'authenticate', function() use ($app) { 
            // check for required params
            verifyRequiredParams(array('receiver_user_id', 'message'));
            $response = array();  
            $db = new DbHandler();     
                
            // reading post params
            $sender_user_id = $app->request->post('sender_user_id');
            $receiver_user_id = $app->request->post('receiver_user_id');
            $message = $app->request->post('message');
    
            // fetch user_id from api_key.
            $headers = apache_request_headers();
            $api_key = $headers['Authorization'];    
            $sender_user_id = $db->getUserId($api_key);
    
            // get current UNIX_TIMESTAMP
            $epoch = time();      
            
            // check if user ids a equal
            if ($sender_user_id != $receiver_user_id) {
                // send a message
                $res = $db->sendMessage($sender_user_id, $receiver_user_id, $message);

                if ($res == 'success') {
                    $response["error"] = false;
                    $response['error_id'] = 200;
                    $response['error_title'] = 'Message Sent';
                    $response['error_message'] = 'Message was sent successfully';

                    // echo json response
                    echoRespnse(200, $response);

                } else if ($res == 'failed') {
                    $response["error"] = true;
                    $response['error_id'] = 400;
                    $response['error_title'] = 'Message Sending Failed';
                    $response['error_message'] = 'Oops! An error occurred while sending message';

                    // echo json response
                    echoRespnse(400, $response);

                } 
            } else {
                $response["error"] = true;
                $response['error_id'] = 400;
                $response['error_title'] = 'Message Sending Failed';
                $response['error_message'] = 'Oops! An error occurred while sending message';

                // echo json response
                echoRespnse(400, $response);
            }
        });

/**
 * view all messages from  between two uers
 * method GET
 * url /view_messages.php/user=id          
 */

$app->get('/view_messages.php/user=:id', 'authenticate', function($user_id_b)  use ($app) {
            global $user_id;
            $response = array();
            $db = new DbHandler();
            
            $user_id_a = $user_id;    
            // Fetching all messages
            $result = $db->getAllMessages($user_id_a, $user_id_b);         
            
            if ($user_id_a != $user_id_b) {
                $response["error"] = false;
                $response["messages"] = array();
                
                // looping through result and preparing messages array
                while ($messages = $result->fetch_assoc()) {                
                    $tmp = array();
                    $tmp["message_id"] = $messages["message_id"];
                    $tmp["sender_user_id"] = $messages["sender_user_id"];
                    $tmp["receiver_user_id"] = $messages["receiver_user_id"];
                    $tmp["message"] = $messages["message"];
                    $tmp["epoch"] = $messages["epoch"];

                    array_push($response["messages"], $tmp);
                }
                // echo json response
                echoRespnse(200, $response);
                
            } else {
                // if query failed,
                $response['error'] = true;
                $response['error_id'] = 401;
                $response['error_title'] = 'Invalid User';
                $response['error_message'] = 'An error occurred while fetching the Messages';
                
                // echo json response
                echoRespnse(401, $response);
            }
        });

/**
 * Verifying required params posted or not
 */

function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response['error_id'] = 400;
        $response['error_title'] = 'Required field(s)';
        $response["error_message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        
        // echo json response
        echoRespnse(400, $response);
        $app->stop();
    }
}

/**
 * Validating email address
 */
function validateEmail($email) {
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["error"] = true;
        $response['error_id'] = 400;
        $response['error_title'] = 'Invalid Email';
        $response["error_message"] = 'Email address is not valid';
        
        // echo json response
        echoRespnse(400, $response);
        $app->stop();
    }
}

/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 */

function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');
    echo json_encode($response);
}

$app->run();

?>