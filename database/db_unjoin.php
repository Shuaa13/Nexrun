<?php
    session_start();
    include 'dbcon.php'; 

    if (!isset($_SESSION['id_user']) || !isset($_POST['event_id'])) {
        header("Location: ../schedules.php");
        exit();
    }

    $id_user = $_SESSION['id_user'];
    $event_id = $_POST['event_id'];

    $query = "DELETE FROM user_events WHERE id_user = ? AND id_events = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $id_user, $event_id);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "You have successfully unjoined the event.";
        $_SESSION['alert'] = "success";
    } else {
        $_SESSION['msg'] = "Error unjoining event.";
        $_SESSION['alert'] = "danger";
    }

    $stmt->close();
    $conn->close();

    header("Location: ../schedules.php");
    exit();

?>
