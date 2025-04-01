<?php 
session_start();

    if(isset($_SESSION['id_user'])) {
        include 'database/db_fetchuser.php';
        include 'database/db_fetchevents.php'; 
    }

    if(isset($_SESSION['msg'])) {
        unset($_SESSION['msg']);
        unset($_SESSION['alert']);
    }

    if (!isset($_SESSION['id_user'])) {
        header("Location: login.php");
        exit();
    }

    include 'database/dbcon.php';

    $id_user = $_SESSION['id_user'];

    $query = "SELECT events.* FROM user_events 
            JOIN events ON user_events.id_events = events.id_event 
            WHERE user_events.id_user = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $joined_events = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'metadata.php'; ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <div class="text-center h5 my-4">My Schedule</div>

        <?php if (!empty($joined_events)): ?>
            <div class="table-responsive">
                <table id="scheduleTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Location</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($joined_events as $event): ?>
                            <tr>
                                <td><?= htmlspecialchars($event['title']) ?></td>
                                <td><?= htmlspecialchars($event['location']) ?></td>
                                <td><?= date("F j, Y", strtotime($event['date'])) ?></td>
                                <td>
                                    <?= (strtotime($event['date']) <= strtotime(date("Y-m-d"))) ? "Finished" : "Upcoming"; ?>
                                </td>
                                <td>
                                    <form action="database/db_unjoin.php" method="POST" onsubmit="return confirm('Are you sure you want to unjoin this event?');">
                                        <input type="hidden" name="event_id" value="<?= $event['id_event']; ?>">
                                        <button type="submit" class="btn btn-danger">Unjoin</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center">You haven't joined any events yet.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#scheduleTable').DataTable({
                "paging": true,
                "ordering": true,
                "info": true
            });
        });
    </script>
</body>
</html>
