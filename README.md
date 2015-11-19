## RESTful Back End API for Private Messaging System ##

##### API URL Structure, CURL Request & JSON Response #####

///////////////////////////////////////////////////////////////////////// 

<strong>User Registration</strong>

* URL - http://localhost/RESTful_Pm_System/api/endpoints/register.php 
* Method - POST
* Parameters - 'first_name', 'last_name', 'email', 'password'
* Header Authorization - No

* CURL Request -
    ```
    curl -X POST
    -H "Content-Type: application/json"
    -d '{"first_name": "John", "last_name": "Doe", "email": "john-doe@gmail.com", "password": "password"}'
    http://localhost/RESTful_Pm_System/api/endpoints/register.php 
    ```  
* JSON Response -

    ```    
    {"error":false,"error_id":200,"error_title":"User Registered",
    "error_message":"You are successfully registered"}
    ```   

/////////////////////////////////////////////////////////////////////////   

<strong>User Login</strong>

* URL - http://localhost/RESTful_Pm_System/api/endpoints/login.php 
* Method - POST
* Parameters - 'email', 'password'
* Header Authorization - No

* CURL Request -
    ```
    curl -X POST
    -H "Content-Type: application/json"
    -d '{"email": "john-doe@gmail.com", "password": "password"}'
    http://localhost/RESTful_Pm_System/api/endpoints/login.php 
    ```  
* JSON Response -

    ```    
    {"error":false,"first_name":"John","last_name":"Doe","email":"john-doe@gmail.com",
    "apiKey":"6d4d3ca9ce063b423662c21d44cb1ee4","created_at":"2015-11-18 14:05:23"}
    ```   
    
///////////////////////////////////////////////////////////////////////// 

<strong>Send Message</strong>

* URL - http://localhost/RESTful_Pm_System/api/endpoints/send_message.php 
* Method - POST
* Parameters - 'sender_user_id', 'receiver_user_id', 'message'
* Header Authorization - API_KEY (eg: e64e0792874cfa8abecda2ff33122953)
* Description - Send a message one user to another

* CURL Request -
    ```
    curl -X POST
    -H "Authorization: e64e0792874cfa8abecda2ff33122953"
    -H "Content-Type: application/json"
    -d '{"sender_user_id": "1", "receiver_user_id": "2", "message": "Example text"}'
    http://localhost/RESTful_Pm_System/api/endpoints/send_message.php 
    ```  
* JSON Response -

    ```    
    {"error":false,"error_id":200,"error_title":"Message Sent",
    "error_message":"Message was sent successfully"}
    ```   
    
/////////////////////////////////////////////////////////////////////////     


<strong>List Users</strong>

* URL - http://localhost/RESTful_Pm_System/api/endpoints/list_all_users.php 
* Method - GET
* Parameters - No
* Header Authorization - API_KEY (eg: e64e0792874cfa8abecda2ff33122953)
* Description - Listing all Users excluding the requester

* CURL Request -
    ```
    curl -X POST
    -H "Authorization: e64e0792874cfa8abecda2ff33122953"
    -H "Content-Type: application/json"
    http://localhost/RESTful_Pm_System/api/endpoints/list_all_users.php
    ```  
* JSON Response -

    ```    
    {"error":false,"users":[
    {"user_id":1,"email":"thushara@hotmail.com","first_name":"Thushara","last_name":"Sathkumara"},
    {"user_id":2,"email":"tcolligan@gmail.com","first_name":"Thomas","last_name":"Colligan"}]}
    ```   
    
/////////////////////////////////////////////////////////////////////////     

<strong>List Messages</strong>

* URL - http://localhost/RESTful_Pm_System/api/endpoints/view_messages.php/user=:id 
* Method - GET
* Parameters - No
* Header Authorization - API_KEY (eg: e64e0792874cfa8abecda2ff33122953)
* Description - Listing all messages between two users

* CURL Request -
    ```
    curl -X POST
    -H "Authorization: e64e0792874cfa8abecda2ff33122953"
    -H "Content-Type: application/json"
    http://localhost/RESTful_Pm_System/api/endpoints/view_messages.php/user=2
    ```  
* JSON Response -

    ```    
    {"error":false,"messages":[
    {"message_id":1,"sender_user_id":1,"receiver_user_id":2,"message":"Hey what is up?","epoch":1447828446},
    {"message_id":2,"sender_user_id":2,"receiver_user_id":1,"message":"Not much, how are you doing?",
    "epoch":1447828506}]}
    ```   
    
/////////////////////////////////////////////////////////////////////////     


