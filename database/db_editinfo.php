<?php 

    include 'dbcon.php'; 
    session_start();

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $userID = $_SESSION['id_user']; 

        $lastname = $_POST['lastname'];
        $firstname = $_POST['firstname'];
        $middlename = $_POST['middlename'];
        $gender = $_POST['gender'];
        $occupation = $_POST['occupation'];
        $bdate = !empty($_POST['bdate']) ? $_POST['bdate'] : NULL;

        $sql = "UPDATE users SET lastname = ?, firstname = ?, middlename = ?, gender = ?, occupation = ?, bdate = ? WHERE id_user = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $lastname, $firstname, $middlename, $gender, $occupation, $bdate, $userID);

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