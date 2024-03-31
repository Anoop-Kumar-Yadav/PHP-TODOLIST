<?php
    // Database configuration
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "todo_master";
    
    // Establishing connection to MySQL database
    $conn = mysqli_connect($server, $username, $password, $database);

    // Checking for connection errors
    if ($conn->connect_errno) {
        die("Connection to MySQL Failed : ".$conn->connect_error);
    }

    //------------------------------------------------------------------------------------------------------------------------------------------
    // Adding Items Function
    if (isset($_POST["add"])) {
        // Retrieving task item from the form
        $item = $_POST['item'];

        // Validating if item is not empty
        if (!empty($item)) {
            // Query to insert task into the database
            $query = "INSERT INTO todo (name) VALUES ('$item')";
            
            // Executing the query
            if (mysqli_query($conn, $query)) {
                // Displaying success message
                echo '
                    <center>
                        <div class="alert alter-success" role="alert">
                            Item Added Successfully !
                        </div>
                    </center>
                ';
            } else {
                // Displaying error message if query execution fails
                echo mysqli_error($conn);
            }
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------
    // Mark as Done Function and Remove Function
    if (isset($_GET["action"])) {
        // Retrieving task ID from the URL
        $itemId = $_GET['item'];

        if ($_GET['action'] == 'done') {
            // Query to mark task as done
            $query = "UPDATE todo SET status = 1 WHERE id = '$itemId'";
            
            // Executing the query
            if (mysqli_query($conn, $query)) {
                // Displaying success message
                echo '
                    <center>
                        <div class="alert alter-info" role="alert">
                            Item Marked as Done !
                        </div>
                    </center>
                ';
            } else {
                // Displaying error message if query execution fails
                echo mysqli_error($conn);
            }
        } elseif ($_GET['action'] == 'remove') {
            // Query to remove task from the database
            $query = "DELETE FROM todo WHERE id = '$itemId'";
            
            // Executing the query
            if (mysqli_query($conn, $query)) {
                // Displaying success message
                echo '
                    <center>
                        <div class="alert alter-danger" role="alert">
                            Item Removed Successfully !
                        </div>
                    </center>
                ';
            } else {
                // Displaying error message if query execution fails
                echo mysqli_error($conn);
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .done {
            text-decoration: line-through;
        }
    </style>
    <style>
        .app-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .app-icon {
            width: 50px; 
            height: 50px; 
            margin-right: 10px;
        }
        .app-name-container {
            display: flex;
            align-items: center;
        }
        .app-name {
            font-size: 24px;
            font-weight: bold;
            color: #007bff; 
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2); 
        }
        .app-author {
            font-size: 14px; 
            font-family: 'Indie Flower', cursive; 
            color: #555; 
            margin-left: 5px; 
        }
    </style>
</head>
<body>
    <main>
        <div class="container pt-5">
            <div class="row">
                <div class="col-sm-12 col-md-3"></div>
                <div class="col-sm-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="app-container">
                                <!-- App Icon -->
                                <img src="icon.png" alt="App Icon" class="app-icon">
                                <div class="app-name-container">
                                    <!-- App Name -->
                                    <h1 class="app-name">TODO - LIST</h1>
                                </div>
                                <!-- App Author -->
                                <span class="app-author">by Anoop Kumar Yadav</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Form to add new todo items -->
                            <form method="post" action="<?= $_SERVER['PHP_SELF']?>">
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="item" placeholder="Add a Todo Item">
                                </div>
                                <input type="submit" class="btn btn-dark" name="add" value="Add Item">
                            </form>
                            <!-- List of todo items -->
                            <div class="mt-5 mb-5">
                                <?php
                                    // Query to retrieve todo items from database
                                    $query = "SELECT * FROM todo";
                                    $result = mysqli_query($conn, $query);
                                    
                                    // Checking if there are any todo items
                                    if ($result->num_rows > 0) {
                                        $i = 1;
                                        // Loop through each todo item
                                        while ($row = $result->fetch_assoc()) {
                                            $done = $row['status'] == 1 ? "done" : "";
                                            // Displaying each todo item
                                            echo '
                                                <div class="row mt-4">
                                                    <div class="col-sm-12 col-md-1"><h5>', $i, '</h5></div>
                                                    <div class="col-sm-12 col-md-6"><h5 class= "', $done, '">', $row["name"], '</h5></div>
                                                    <div class="col-sm-12 col-md-7">
                                                        <a href="?action=done&item=', $row["id"], '" class="btn btn-outline-dark">Mark as Done</a>
                                                        <a href="?action=remove&item=', $row["id"], '" class="btn btn-outline-danger">Remove</a>
                                                    </div>
                                                </div>';
                                            $i++;
                                        }
                                    } else {
                                        // Displayed when there are no todo items
                                        echo '
                                            <center>
                                                <img src="folder.png" width="50px" alt="Empty List"><br><span>Your List is Empty</span>
                                            </center>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    
    <script>
        // Function to fade out alert messages
        $(document).ready(function(){
            $(".alert").fadeTo(5000,500).slideUp(500,function(){
                $(".alert").slideUp(500);
            });
        })
    </script>
</body>
</html>

