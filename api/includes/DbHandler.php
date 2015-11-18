<?php

/**
 * Class to handle all db operations
 * DbHandler class will have CRUD methods for database tables
 */

class DbHandler {

    private $conn;

    function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }

    /* ------------- `users` table method ------------------ */

    /**
     * Creating new user
     * @param String $first_name User first name
     * @param String $last_name User last name                                      
     * @param String $email User login email id
     * @param String $password User login password
     */
    
    public function createUser($first_name, $last_name, $email, $password) {
        require_once 'PassHash.php';
        $response = array();

        // First check if user already existed in db
        if (!$this->isUserExists($email)) {
            // Generating password hash
            $password_hash = PassHash::hash($password);

            // Generating API key
            $api_key = $this->generateApiKey();

            $stmt = $this->conn->prepare("INSERT INTO users(first_name, last_name, email, password_hash, api_key, status) values(?, ?, ?, ?, ?, 1)");
            $stmt->bind_param("sssss", $first_name, $last_name, $email, $password_hash, $api_key);
            $result = $stmt->execute();
            $stmt->close();

            if ($result) {
                // User successfully inserted
                return USER_CREATED_SUCCESSFULLY;
            } else {
                // Failed to create user
                return USER_CREATE_FAILED;
            }
        } else {
            // User with same email already existed in the db
            return USER_ALREADY_EXISTED;
        }

        return $response;
    }

    /**
     * Checking user login
     * @param String $email User login email id
     * @param String $password User login password
     * @return boolean User login status success/fail
     */
    
    public function checkLogin($email, $password) {
        // fetching user by email
        $stmt = $this->conn->prepare("SELECT password_hash FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($password_hash);
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Found user with the email
            // Now verify the password
            $stmt->fetch();
            $stmt->close();

            if (PassHash::check_password($password_hash, $password)) {
                // User password is correct
                return TRUE;
            } else {
                // user password is incorrect
                return FALSE;
            }
        } else {
            $stmt->close();
            // user not existed with the email
            return FALSE;
        }
    }

    /**
     * Checking for duplicate user by email address
     * @param String $email email to check in db
     * @return boolean
     */
    
    private function isUserExists($email) {
        $stmt = $this->conn->prepare("SELECT user_id from users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    /**
     * Fetching user by email
     * @param String $email User email id
     */
    
    public function getUserByEmail($email) {
        $stmt = $this->conn->prepare("SELECT first_name, last_name, email, api_key, created_at FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            $stmt->bind_result($first_name, $last_name, $email, $api_key, $created_at);
            $stmt->fetch();
            $user = array();
            $user["first_name"] = $first_name;
            $user["last_name"] = $last_name;
            $user["email"] = $email;
            $user["api_key"] = $api_key;
            $user["created_at"] = $created_at;
            $stmt->close();
            return $user;
        } else {
            return NULL;
        }
    }

    /**
     * Fetching user api key
     * @param String $user_id User user_id
     */
    
    public function getApiKeyById($user_id) {
        $stmt = $this->conn->prepare("SELECT api_key FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $stmt->bind_result($api_key);
            $stmt->close();
            return $api_key;
        } else {
            return NULL;
        }
    }

    /**
     * Fetching user id by api key
     * @param String $api_key User api key
     */
    
    public function getUserId($api_key) {
        $stmt = $this->conn->prepare("SELECT user_id FROM users WHERE api_key = ?");
        $stmt->bind_param("s", $api_key);
        if ($stmt->execute()) {
            $stmt->bind_result($user_id);
            $stmt->fetch();
            $stmt->close();
            return $user_id;
        } else {
            return NULL;
        }
    }

    /**
     * check api_key is in the db, then it is a valid key
     * @param String $api_key user api_key
     * @return boolean
     */
    
    public function isValidApiKey($api_key) {
        $stmt = $this->conn->prepare("SELECT user_id from users WHERE api_key = ?");
        $stmt->bind_param("s", $api_key);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    /**
     * Generating random Unique MD5 String for user Api key
     */
    
    private function generateApiKey() {
        return md5(uniqid(rand(), true));
    }
    
    /**
     * Fetching all users excluding the requester
     * @param String $user_id id of the user
     */
    
    public function getAllUsers($user_id) {
        $stmt = $this->conn->prepare("SELECT user_id, email, first_name, last_name FROM users WHERE user_id NOT IN (?)");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $users = $stmt->get_result();
        $stmt->close();
        return $users;
    }
    
    
    /* ------------- `messages` table method ------------------ */

    /**
     * send a message
     * @param Int $sender_user_id who send the the message
     * @param Int $receiver_user_id who received the message
     * @param String $message Messages message
     * @param Int $epoch Current UNIX_TIMESTAMP                               
     */
    
    public function sendMessage($sender_user_id, $receiver_user_id, $message) {
        $respose = array();    
        
        $stmt = $this->conn->prepare("INSERT INTO messages (sender_user_id, receiver_user_id, message, epoch) VALUES (?, ?, ?, UNIX_TIMESTAMP(NOW()))");
        $stmt->bind_param("iis", $sender_user_id, $receiver_user_id, $message);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            // new message created 
            // now assign the message to a user
            $user_id = $sender_user_id;
            $new_message_id = $this->conn->insert_id;
            $res = $this->createUserMessage($user_id, $new_message_id);
            if ($res) {
                // Message was sent successfully
                return 'success';
            } else {
                // Message send failed
                return NULL;
            }            
            
        } else { 
            // Message send failed
            return 'failed';
        }
    }
    
    /**
     * Fetching all messages between two users
     * @param Int $user_id_a 
     * @param Int $user_id_b 
     */
    
    public function getAllMessages($user_id_a, $user_id_b) {
        // get all messages between two users
        $stmt = $this->conn->prepare("SELECT message_id, sender_user_id, receiver_user_id, message, epoch FROM messages WHERE sender_user_id IN (?, ?) AND receiver_user_id IN (? , ?) ORDER BY epoch ASC");
        $stmt->bind_param("iiii", $user_id_a, $user_id_b, $user_id_a, $user_id_b);
        $stmt->execute();
        $messages = $stmt->get_result();
        $stmt->close();
        return $messages;
    }
    
    /* ------------- `user_messages` table method ------------------ */

    /**
     * Function to assign a message to user
     * @param Int $user_id id of the users
     * @param Int $message_id id of the Messages                           
     */
    
    public function createUserMessage($user_id, $message_id) {
        $respose = array();    
        
        $stmt = $this->conn->prepare("INSERT INTO user_messages (user_id, message_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $message_id);
        $result = $stmt->execute();

        if (false === $result) {
            die('execute() failed: ' . htmlspecialchars($stmt->error));
        }
        $stmt->close();
        return $result;
    }

}

?>
