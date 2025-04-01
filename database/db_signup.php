<?php 

    include 'dbcon.php'; 

    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $username = $_POST['username'];
        $password = $_POST['password'];
        $lastname = $_POST['lastname'];
        $firstname = $_POST['firstname'];
        $middlename = $_POST['middlename'];

        $checkStmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
        $checkStmt->bind_param("s", $username);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $_SESSION['msg'] = "Username has been taken.";
            $_SESSION['alert'] = "alert-danger";
            $checkStmt->close();
            $conn->close();
            header("Location: ../signup.php");
            exit();
        }

        $checkStmt->close();
        
        $stmt = $conn->prepare("INSERT INTO users (username, password, lastname, firstname, middlename) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $password, $lastname, $firstname, $middlename);

        if ($stmt->execute()) {
            $_SESSION['msg'] = "Signup successful.";
            $_SESSION['alert'] = "alert-success";
        } 
        else {
            $_SESSION['msg'] = "Error signup.";
            $_SESSION['alert'] = "alert-danger";
        }

        $stmt->close();
        $conn->close();

        header("Location: ../signup.php");
        exit();
    }

?>