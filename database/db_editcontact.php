<?php 

    include 'dbcon.php'; 
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $userID = $_SESSION['id_user']; 
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $email = $_POST['email'];

        $sql = "UPDATE users SET phone = ?, address = ?, email = ? WHERE id_user = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $phone, $address, $email, $userID);

        if ($stmt->execute()) {
            header("Location: ../profile.php");
            exit();
        } 
        else {
            echo "Error updating record: " . $conn->error;
        }
    
        $stmt->close();
        $conn->close();
    }

?>