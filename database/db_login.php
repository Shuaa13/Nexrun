<?php 

    include 'dbcon.php'; 

    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];
    
        $stmt = $conn->prepare("SELECT id_user, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
    
            if ($row['password'] == $password) {
                $_SESSION['id_user'] = $row['id_user'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['msg'] = "Login successful";
    
                header("Location: ../home.php");
                exit();

            } 
            else {
                $_SESSION['msg'] = "Incorrect password.";
                $_SESSION['alert'] = "alert-danger";
            }
        } 
        else {
            $_SESSION['msg'] = "User not found.";
            $_SESSION['alert'] = "alert-danger";
        }
    
        $stmt->close();
        $conn->close();
    
        header("Location: ../login.php");
        exit();
    }

?>