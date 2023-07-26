<?php

$host = $_SERVER["DOCUMENT_ROOT"];
require_once $host . "/log/log.php";
require_once $host . "/util/functions.php";
require_once $host . "/db/db.php";

global $connection;

if (isset($_SESSION["username"])) {
    $loggedIn = true;
    $sessionName = $_SESSION["name"];
    $sessionUsername = $_SESSION["username"];
    $sessionUserId = $_SESSION["user_id"];
    $sessionRoleType = $_SESSION["role_type"];
} else {
    $loggedIn = false;
}

if (!$loggedIn) {
    $dynamicPanel = "<div>Need to log in to see page</div>";
    return;
}

if ($sessionRoleType === "admin") {
    $adminSectionPanelDiv = "
        <div id='admin-actions-panel'>
            <form action='/web/navigate.php' method='post'>
               <input type='submit' name='users' value='Users'>
               <br>
               <input type='submit' name='add-users' value='Add User'>
               <br>
               <input type='submit' name='edit-users' value='Edit User'>
               <br>
               <input type='submit' name='delete-users' value='Delete User'>
               <br>
               <input type='submit' name='add-book' value='Add Book'>
               <br>
               <input type='submit' name='edit-book' value='Edit Book'>
               <br>
               <input type='submit' name='delete-book' value='Delete Book'>
               <br>
            </form>
        </div>";
}

$selectAllRolesSQL = "select role_name from amazon.roles";
$result = $connection->query($selectAllRolesSQL);
$roleOptions = array();
while ($row = $result->fetch_assoc()) {
    $roleName = $row["role_name"];
    $roleOption = "<option value='$roleName'>$roleName</option>";
    $roleOptions[] = $roleOption;
}
$roleOptionsHtml = "\"" . implode(" ", $roleOptions) . "\"";

$dynamicPanel = <<<EOD
        <form id='find-user-by-username-form' method='post'>
            Username: <input type='text' name='username'>
            <input type='submit' value='Find'>
        </form>
        <hr id=''>
        
        <script>
            const findUserByUsernameForm = document.getElementById('find-user-by-username-form');
            var existsUserDataForm = false;
            findUserByUsernameForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const data = new FormData(this);
                fetch('/blocks/find-user-by-username.php', {
                    method: 'POST',
                    body: data
                })
                .then(response => {
                    if (response.status === 200) {
                        alert('User found');
                        return response.json();
                    } else if (response.status === 404) {
                        alert('User does not exists');
                        throw new Error('User does not exists: ' + response);
                    } else if (response.status === 500) {
                        alert('Server error');
                        throw new Error('Server error: ' + response);
                    } else {
                        throw new Error('Unexpected HTTP response: ' + response.status);
                    }
                })
                .then(data => {
                    console.log(data);
                    
                    if (existsUserDataForm) {
                        return;
                    }
                    
                    var userDataForm = document.createElement('form');
                    userDataForm.method = 'POST';
                    
                    var nameInput = document.createElement('input');
                    nameInput.type = 'text';
                    nameInput.name = 'name';
                    nameInput.placeholder = 'Enter name';
                    
                    var passwordInput = document.createElement('input');
                    passwordInput.type = 'text';
                    passwordInput.name = 'password';
                    passwordInput.placeholder = 'Enter password';
                    
                    var roleSelect = document.createElement('select');
                    roleSelect.name = 'role';
                    roleSelect.innerHTML = $roleOptionsHtml;
                    
                    var deliveryAddressInput = document.createElement('input');
                    deliveryAddressInput.type = 'text';
                    deliveryAddressInput.name = 'address';
                    deliveryAddressInput.placeholder = 'Enter delivery address';
                    
                    var submitButton = document.createElement('input');
                    submitButton.type = 'submit';
                    submitButton.value = 'Send';
                    
                    userDataForm.append('Name:');
                    userDataForm.appendChild(nameInput);
                    userDataForm.appendChild(document.createElement('br'));
                    
                    userDataForm.append('Password:');
                    userDataForm.appendChild(passwordInput);
                    userDataForm.appendChild(document.createElement('br'));
                    
                    userDataForm.append('Role:')
                    userDataForm.appendChild(roleSelect);
                    userDataForm.appendChild(document.createElement('br'));
                    
                    userDataForm.append('Address');
                    userDataForm.appendChild(deliveryAddressInput);
                    userDataForm.appendChild(document.createElement('br'));
                    
                    userDataForm.appendChild(submitButton);
                    userDataForm.appendChild(document.createElement('br'));
                    
                    const ordersPanel = document.getElementById('orders-panel');
                    ordersPanel.appendChild(userDataForm);
                    existsUserDataForm = true;
                    
                    userDataForm.addEventListener('submit', function(userDataFormEvent) {
                        userDataFormEvent.preventDefault();
                        const newData = new FormData(this);
                        fetch('/blocks/edit-user.php', {
                            method: 'POST',
                            body: JSON.stringify({
                                'username': data['username'],
                                'name': newData.get('name'),
                                'password': newData.get('password'),
                                'address': newData.get('address'),
                                'role': newData.get('role')
                            })
                        })
                        .then(response => {
                            if (response.status === 200) {
                                alert('User updated');
                                // console.log(response.json());
                                return response.json();
                            } else if (response.status === 404) {
                                alert('User does not exists');
                                throw new Error('User does not exists: ' + response);
                            } else if (response.status === 500) {
                                alert('Server error');
                                throw new Error('Server error: ' + response);
                            } else {
                                throw new Error('Unexpected HTTP response: ' + response.status);
                            }
                        })
                        .then(data => {
                            console.log(data);
                        })
                        .catch(error => {
                            console.error('Error: ', error);
                        });
                    });
                })
                .catch(error => {
                    console.error('Error: ', error);
                });
            });
        </script>
EOD;

//<br>
//Password: <input type='text' name='edit-users-password'>
//            <br>
//Role:
//            <select name='role' required>
//$roleOptionsHtml
//            </select>
//            <br>
//Address: <input type='text' name='edit-users-address'>
//            <br>

echo <<<_END
    <style>
        #account-panel {
            display: grid;
            background-color: burlywood;
        }
        
        #actions-panel {
            grid-column: 1;
            background-color: cornflowerblue;
        }
        
        #customer-actions-panel {
            background-color: burlywood;
        }
        
        #admin-actions-panel {
            background-color: coral;
        }
        
        #orders-panel {
            grid-column: 2;
            background-color: aliceblue;
        }
    </style>
    
    <div id="account-panel">
        <div id="actions-panel">
            <div id="customer-actions-panel">
                <div><a href="http://localhost:8888/web/account.php?edit=$username">Edit Profile</a></div>
                <div><a href="http://localhost:8888/web/account.php?delete=$username">Delete Profile</a></div>
                <form action="logout.php" method="post">
                    <input type="submit" value="Log out">
                </form>
            </div>
            $adminSectionPanelDiv
        </div>
        <div id="orders-panel">
            <div>Name: $sessionName; Username: $sessionUsername; Role Type: $sessionRoleType</div>
            <hr>
            $dynamicPanel
        </div>
    </div>
_END;
