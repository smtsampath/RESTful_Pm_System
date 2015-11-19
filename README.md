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
*
    ```    
    {"error":false,"error_id":200,"error_title":"User Registered","error_message":"You are successfully registered"}
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

