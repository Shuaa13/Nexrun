<?php 

    include 'dbcon.php'; 

    if(isset($_SESSION['msg'])) {
        unset($_SESSION['msg']);
        unset($_SESSION['alert']);
    }

    $userID = $_SESSION['id_user'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE id_user = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

?>