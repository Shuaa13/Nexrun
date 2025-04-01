<?php 

    include 'dbcon.php'; 

    $stmt = $conn->prepare("SELECT events.*, events_category.category, events_category.price 
                        FROM events 
                        INNER JOIN events_category ON events.id_event = events_category.id_event
                        ORDER BY events.id_event;");
    $stmt->execute();
    $result = $stmt->get_result();

    $events = [];
    while ($row = $result->fetch_assoc()) {
        $id = $row['id_event'];
        
        if (!isset($events[$id])) {
            $events[$id] = [
                "id_event"    => $row["id_event"],
                "title"       => $row["title"],
                "description" => $row["description"],
                "location"    => $row["location"],
                "date"        => $row["date"],
                "bannerpath"  => $row["bannerpath"],
                "categories"  => [],
            ];
        }

        $events[$id]['categories'][] = [
            "category" => $row["category"],
            "price"    => $row["price"]
        ];
    }

    $stmt->close();
    $conn->close(); 

?>