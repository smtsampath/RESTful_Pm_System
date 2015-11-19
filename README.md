# RESTful Back End API for Private Messaging System

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
            <td colspan="5">
            </td>
        </tr>
        <tr>
            <td colspan="5">
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
            <td colspan="5">
            </td>
        </tr>
        <tr>
            <td colspan="5">
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
            <td colspan="5">
            </td>
        </tr>
        <tr>
            <td colspan="5">
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
            <td colspan="5">
            </td>
        </tr>
        <tr>
            <td colspan="5">
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
            <td colspan="5">
            </td>
        </tr>
        <tr>
            <td colspan="5">
            </td>
        </tr>
    </tbody>

</table>             
