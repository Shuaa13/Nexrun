<?php 
include 'dbcon.php'; 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_FILES["profilePic"]) && $_FILES["profilePic"]["error"] == 0) {
        $uploadDir = "../resources/profilepics/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileExtension = pathinfo($_FILES["profilePic"]["name"], PATHINFO_EXTENSION);
        $randomFileName = mt_rand(100000, 999999) . mt_rand(100000, 999999) . "." . $fileExtension;        
        $uploadFilePath = $uploadDir . $randomFileName;

        $userID = $_SESSION['id_user'] ?? null;

        if ($userID) {
            if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $uploadFilePath)) {
                $query = "UPDATE users SET profilepath = ? WHERE id_user = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("si", $randomFileName, $userID);
                
                if ($stmt->execute()) {
                    header("Location: ../profile.php");
                    exit();
                } 
                else {
                    echo "Database update failed: " . $stmt->error;
                }
            } 
            else {
                echo "Error moving the uploaded file.";
            }
        } 
        else {
            echo "User not logged in.";
        }
    } 
    else {
        header("Location: ../profile.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
