<?php 
   
    include 'dbcon.php'; 
    session_start();

    header("Content-Type: application/json"); 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $user_id = $_POST['id_user'];
        $event_id = $_POST['id_events'];
        $category = $_POST['category'];

        if (empty($user_id) || empty($event_id) || empty($category)) {
            echo json_encode(["status" => "error", "message" => "Missing required fields"]);
            exit();
        }

        $check_stmt = $conn->prepare("SELECT COUNT(*) FROM user_events WHERE id_user = ? AND id_events = ? AND category = ?");
        $check_stmt->bind_param("iis", $user_id, $event_id, $category);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();

        if ($count > 0) {
            echo json_encode(["status" => "error", "message" => "You have already joined this event and category."]);
            exit();
        } 

        $stmt = $conn->prepare("INSERT INTO user_events (id_user, id_events, category) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $event_id, $category);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Successfully joined the event."]);
        } 
        else {
            echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
        }

        $stmt->close();
    } 
    else {
        echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    }

    $conn->close();
    exit();
?>
