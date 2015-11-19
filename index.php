<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>RESTful Back End API for Private Messaging System</title>
        <link href='css/style.css' rel='stylesheet' type='text/css'/>
        <link href="css/highlighter/desert.css" rel="stylesheet"/>
        <script src="css/highlighter/run_prettify.js"></script>
        <?php

            /**
             * Get main or base URL 
             * http://localhost/DirName/ or http://www.yourname.com/DirName
             */
            function getBaseUrl() {
                // output: /FolderName/index.php
                $currentPath = $_SERVER['PHP_SELF']; 

                // output: Array ( [dirname] => /FolderName [basename] => index.php [extension] => php [filename] => index ) 
                $pathInfo = pathinfo($currentPath); 

                // output: localhost
                $hostName = $_SERVER['HTTP_HOST']; 

                // output: http://
                $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

                // return: http://localhost/FolderName/
                return $protocol.$hostName.$pathInfo['dirname'];
            }

        ?>
    </head>

    <body>
        <div id="container">
            <div id="header">
                <h1>RESTful API for Private Messaging System</h1>
            </div> 
        
            <div id="content">

                <h3>API URL Structure, CURL Request AND JSON Response</h3>
                
                <table>
                    <tbody>
                        <tr>
                            <td><strong>URL</strong></td>
                            <td><strong>Method</strong></td>
                            <td><strong>Parameters</strong></td>
                            <td><strong>Authorization</strong></td>
                            <td><strong>Description</strong></td>
                        </tr>
                        <tr>
                            <td><?php echo getBaseUrl() . 'api/endpoints/register.php'; ?></td>
                            <td>POST</td>
                            <td>first_name, last_name, email, password</td>
                            <td></td>
                            <td>User Registration</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="curl">
                                <code  class="prettyprint">
                                    <pre>
                                         curl -X POST<br/>
                                         -H "Content-Type: application/json"<br/>
                                         -d '{"first_name": "John", "last_name": "Doe", "email": "john-doe@gmail.com", "password": "password"}'<br/>
                                         <?php echo getBaseUrl() . 'api/endpoints/register.php'; ?>
                                    </pre>
                                </code>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="curl">
                                <code  class="prettyprint">
                                    <pre>{"error":false,"error_id":200,"error_title":"User Registered","error_message":"You are successfully registered"}</pre>
                                </code>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo getBaseUrl() . 'api/endpoints/login.php'; ?></td>
                            <td>POST</td>
                            <td>email, password</td>
                            <td></td>
                            <td>User Login</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="curl">
                                <code  class="prettyprint">
                                    <pre>
                                         curl -X POST<br/>
                                         -H "Content-Type: application/json"<br/>
                                         -d '{"email": "info@apppartner.com", "password": "password"}'<br/>
                                         <?php echo getBaseUrl() . 'api/endpoints/login.php'; ?>
                                    </pre>
                                </code>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="curl">
                                <code  class="prettyprint">
                                    <pre>
                                        {"error":false,"first_name":"John","last_name":"Doe","email":"john-doe@gmail.com",<br/>
                                        "apiKey":"6d4d3ca9ce063b423662c21d44cb1ee4","created_at":"2015-11-18 14:05:23"}
                                    </pre>
                                </code>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo getBaseUrl() . 'api/endpoints/send_message.php'; ?></td>
                            <td>POST</td>
                            <td>sender_user_id, receiver_user_id, message</td>
                            <td>Api Key</td>
                            <td>Send a message one user to another</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="curl">
                                <code  class="prettyprint">
                                    <pre>
                                         curl -X POST<br/>
                                         -H "Authorization: e64e0792874cfa8abecda2ff33122953"<br/>
                                         -H "Content-Type: application/json"<br/>
                                         -d '{"sender_user_id": "1", "receiver_user_id": "2", "message": "Example text"}'<br/>
                                         <?php echo getBaseUrl() . 'api/endpoints/send_message.php'; ?>
                                    </pre>
                                </code>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="curl">
                                <code  class="prettyprint">
                                    <pre>{"error":false,"error_id":200,"error_title":"Message Sent","error_message":"Message was sent successfully"}</pre>
                                </code>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo getBaseUrl() . 'api/endpoints/list_all_users.php'; ?></td>
                            <td>GET</td>
                            <td></td>
                            <td>Api Key</td>
                            <td>Listing all Users excluding requester</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="curl">
                                <code  class="prettyprint">
                                    <pre>
                                         curl -X GET<br/>
                                         -H "Authorization: e64e0792874cfa8abecda2ff33122953"<br/>
                                         -H "Content-Type: application/json"<br/>
                                         <?php echo getBaseUrl() . 'api/endpoints/list_all_users.php'; ?>
                                    </pre>
                                </code>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="curl">
                                <code  class="prettyprint">
                                    <pre>{"error":false,"users":[<br/>                                  
                                    {"user_id":1,"email":"thushara@hotmail.com","first_name":"Thushara","last_name":"Sathkumara"},<br/>
                                    {"user_id":2,"email":"tcolligan@gmail.com","first_name":"Thomas","last_name":"Colligan"}]}</pre>
                                </code>
                            </td>
                        </tr>
                       <tr>
                            <td><?php echo getBaseUrl() . 'api/endpoints/view_messages.php/user=:id'; ?></td>
                            <td>GET</td>
                            <td></td>
                            <td>Api Key</td>
                            <td>Listing all messages between two users</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="curl">
                                <code  class="prettyprint">
                                    <pre>
                                         curl -X GET <br/>
                                         -H "Authorization: e64e0792874cfa8abecda2ff33122953"<br/>
                                         -H "Content-Type: application/json"<br/>
                                         <?php echo getBaseUrl() . 'api/endpoints/view_messages.php/user=2'; ?>
                                    </pre>
                                </code>  
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="curl">
                                <code  class="prettyprint">
                                    <pre>
                                        {"error":false,"messages":[<br/>
                                        {"message_id":1,"sender_user_id":1,"receiver_user_id":2,"message":"Hey what is up?","epoch":1447828446},<br/>
                                        {"message_id":2,"sender_user_id":2,"receiver_user_id":1,"message":"Not much, how are you doing?","epoch":1447828506}]}
                                    </pre>
                                </code>
                            </td>
                        </tr>
                    </tbody>
                
                </table>                
            </div>
        </div
    </body>
</html>
    