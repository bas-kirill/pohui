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
    echo "<div>Need to log in to see page</div>";
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
               <hr>
               <input type='submit' name='books' value='Books'>
               <br>
               <input type='submit' name='add-book' value='Add Book'>
               <br>
               <input type='submit' name='edit-book' value='Edit Book'>
               <br>
               <input type='submit' name='delete-book' value='Delete Book'>
               <hr>
               <input type='submit' name='orders' value='Orders'>
               <br>
               <input type='submit' name='add-order' value='Add Order'>
               <br>
               <input type='submit' name='edit-order' value='Edit Order'>
               <br>
               <input type='submit' name='delete-order' value='Delete Order'>
               <br>
            </form>
        </div>";
}

$selectAllCategories = "select category from amazon.categories";
$result = $connection->query($selectAllCategories);
$categoryOptions = array();
while ($row = $result->fetch_assoc()) {
    $categoryName = $row["category"];
    $categoryOption = "<option value='$categoryName'>$categoryName</option>";
    $categoryOptions[] = $categoryOption;
}
$categoryOptionsHtml = "\"" . implode(" ", $categoryOptions) . "\"";

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
                <div><a href="http://localhost:8888/web/account.php?edit=$sessionUsername">Edit Profile</a></div>
                <div><a href="http://localhost:8888/web/account.php?delete=$sessionUsername">Delete Profile</a></div>
                <form action="logout.php" method="post">
                    <input type="submit" value="Log out">
                </form>
            </div>
            $adminSectionPanelDiv
        </div>
        <div id="orders-panel">
            <div>Name: $sessionName; Username: $sessionUsername; Role Type: $sessionRoleType</div>
            <hr>
            <form id='find-book-by-isbn-form' method='post'>
                ISBN: <input type='text' name='isbn'>
                <input type='submit' value='Find'>
            </form>
            <hr id=''>
            
            <script>
                const findBookByISBNForm = document.getElementById('find-book-by-isbn-form');
                var existsBookDataForm = false;
                findBookByISBNForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const data = new FormData(this);
                    fetch('/blocks/find-book-by-isbn.php', {
                        method: 'POST',
                        body: data
                    })
                    .then(response => {
                        if (response.status === 200) {
                            alert('Book found');
                            return response.json();
                        } else if (response.status === 400) {
                            alert('Bad Request');
                            throw new Error('Bad Request: ' + response);
                        } else if (response.status === 404) {
                            alert('Book does not exists');
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
                        
                        if (existsBookDataForm) {
                            return;
                        }
                        
                        var bookDataForm = document.createElement('form');
                        bookDataForm.method = 'POST';
                        
                        var titleInput = document.createElement('input');
                        titleInput.type = 'text';
                        titleInput.name = 'title';
                        titleInput.placeholder = 'Enter title';
                        
                        var descriptionInput = document.createElement('input');
                        descriptionInput.type = 'text';
                        descriptionInput.name = 'description';
                        descriptionInput.placeholder = 'Enter description';
                        
                        var priceNumberInput = document.createElement('input');
                        priceNumberInput.type = 'number';
                        priceNumberInput.name = 'price';
                        priceNumberInput.placeholder = 'Enter price';
                        
                        var creationTsTextInput = document.createElement('input');
                        creationTsTextInput.type = 'text';
                        creationTsTextInput.name = 'creation-ts';
                        creationTsTextInput.placeholder = 'Enter creation timestamp (yyyy-mm-dd hh:mm:ss)';
                        
                        var isbnTextInput = document.createElement('input');
                        isbnTextInput.type = 'text';
                        isbnTextInput.name = 'isbn';
                        isbnTextInput.placeholder = 'Enter ISBN';
                        
                        var categorySelect = document.createElement('select');
                        categorySelect.name = 'category';
                        categorySelect.innerHTML = $categoryOptionsHtml;
                        
                        var submitButton = document.createElement('input');
                        submitButton.type = 'submit';
                        submitButton.value = 'Edit';
                        
                        bookDataForm.append('Title:');
                        bookDataForm.appendChild(titleInput);
                        bookDataForm.appendChild(document.createElement('br'));
                        
                        bookDataForm.append('Description:');
                        bookDataForm.appendChild(descriptionInput);
                        bookDataForm.appendChild(document.createElement('br'));
                        
                        bookDataForm.append('Price:')
                        bookDataForm.appendChild(priceNumberInput);
                        bookDataForm.appendChild(document.createElement('br'));
                        
                        bookDataForm.append('Creation Timestamp:');
                        bookDataForm.appendChild(creationTsTextInput);
                        bookDataForm.appendChild(document.createElement('br'));
                        
                        bookDataForm.append('Category:');
                        bookDataForm.appendChild(categorySelect);
                        bookDataForm.appendChild(document.createElement('br'));
                        
                        bookDataForm.appendChild(submitButton);
                        bookDataForm.appendChild(document.createElement('br'));
                        
                        const ordersPanel = document.getElementById('orders-panel');
                        ordersPanel.appendChild(bookDataForm);
                        existsBookDataForm = true;
                        
                        bookDataForm.addEventListener('submit', function(booksDataFormEvent) {
                            booksDataFormEvent.preventDefault();
                            const newData = new FormData(this);
                            fetch('/blocks/edit-book.php', {
                                method: 'POST',
                                body: JSON.stringify({
                                    'isbn': data['isbn'],
                                    'title': newData.get('title'),
                                    'description': newData.get('description'),
                                    'price': newData.get('price'),
                                    'creation-ts': newData.get('creation-ts'),
                                    'category': newData.get('category')
                                })
                            })
                            .then(response => {
                                if (response.status === 200) {
                                    alert('Book updated');
                                    return response.json();
                                } else if (response.status === 404) {
                                    alert('Book does not exists');
                                    throw new Error('Book does not exists: ' + response);
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
        </div>
    </div>
_END;
