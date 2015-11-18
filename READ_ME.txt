Name : Thushara Sathkumara

Time to complete: 18 Hours

#################
 SDLC Steps
#################

# Requirement gathering and analysis
    Step 1: Trying understand the project requirements
    Step 2: List down the main requiremts.    

# Design
    Step 3: Start Creating the database diagram (Tool: MYSQL Workbench)
    Step 4: Defined the URL structure (endpoints) and HTTP methods (POST, GET)
    Step 5: Selecting a Micro-framework that support for RESTful HTTP CRUD Methods and also lighweight (Framework: Slim)

# Implementation
    Step 6: Createing a database & tables
    Step 7: Creating the folder structure for project and creating the ".htaccess"
    Step 8: Start coding the Class (DbHandler, PassHash) and Main API file (Index.php)
    Step 9: Implementing the security functions "function authenticate()" 

# Testing (Debugginh)
    Step 10: Using "Advanced REST Client" chrome extention
    Step 11: Using terminal to varify the CURL request.
    
# Deployment
    Step 12: Creating a subdomain name in the online server (developer-test.smts.me) 
    Step 13: Creating a Cpanel Database
    Step 14: Uploading all the files in to online server via FTP
    
#################

LINK : http://developer-test.smts.me/api/endpoints/register.php 

#################
 Issues
#################

1. current ednpoints display the file extention (eg: http://developer-test.smts.me/api/endpoints/register.php ). 
    - Since this is a API showing file extention is not good. we can add routes without ".php"
        eg: http://developer-test.smts.me/api/endpoints/register
            http://developer-test.smts.me/api/endpoints/login
            
2. I add a random created api_key to user table. so each time user rgister it will generate unique api key for each user.when user request get methods api requred a api key. this will give extra security for the app and the api.

 
