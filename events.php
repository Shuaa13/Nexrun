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

    if(!isset($_SESSION['id_user'])) {
        header("Location: login.php");
        exit();
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'metadata.php'; ?>
</head>
<body>

    <?php include 'navbar.php'; ?>
   
    <div class="container">
        <div class="row">
            <h4 class="mb-3 mt-4">Upcoming Events</h4>

            <div class="col-md-3">
                <div class="border border-dark p-2">
                    <div>
                        <h6 class="mb-3">Distance Category</h6>
                        <?php 
                            $uniqueCategories = [];

                            foreach ($events as $event) {
                                foreach ($event['categories'] as $category) {
                                    if (!in_array($category['category'], $uniqueCategories)) {
                                        $uniqueCategories[] = $category['category'];
                                    }
                                }
                            }
                            sort($uniqueCategories);
                        ?>
                        <?php foreach ($uniqueCategories as $category): ?>
                            <div class="form-check">
                                <input class="form-check-input category-filter" type="checkbox" value="<?= htmlspecialchars($category) ?>" checked>
                                <label class="form-check-label">
                                    <?= htmlspecialchars($category) ?>&nbsp;K
                                </label>
                            </div>
                        <?php endforeach; ?>
                        <button id="filter-btn" class="btn btn-dark mt-2 w-100">Filter</button>
                    </div>
                </div>
            </div>

            <div class="col-md-9" id="events-container">
                <?php foreach ($events as $event): ?>
                <div class="event-item row rounded border-lime mb-3">
                    <div class="col-md-3 p-2 d-flex">
                        <img src="resources/events/<?= htmlspecialchars($event['bannerpath']) ?>" class="img-fluid w-100" style="max-height: 100%; object-fit: contain;">
                    </div>
                    <div class="col-md-7 p-2">
                        <h4><?= htmlspecialchars($event['title']) ?></h4>
                        <div>
                            <i class="fa-solid fa-location-dot text-lime me-2"></i>
                            <?= htmlspecialchars($event['location']) ?>
                        </div>
                        <div>
                            <i class="fa-solid fa-calendar text-lime me-2"></i>
                            <small><?= date("F j, Y", strtotime($event['date'])) ?></small>
                        </div>
                    </div>
                    <div class="col-md-2 col-12 d-flex justify-content-center align-items-center p-2">
                        <button type="button" class="btn btn-success rounded-0 view-event-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#eventsModal"
                            data-id="<?= htmlspecialchars($event['id_event']) ?>"
                            data-title="<?= htmlspecialchars($event['title']) ?>"
                            data-description="<?= htmlspecialchars($event['description']) ?>"
                            data-location="<?= htmlspecialchars($event['location']) ?>"
                            data-date="<?= htmlspecialchars(date("F j, Y", strtotime($event['date']))) ?>"
                            data-categories='<?= json_encode($event["categories"]) ?>'>
                            View Event
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
       
            <div class="d-flex justify-content-end">
                <div id="pagination-container">Pagination</div>
            </div>
        </div>
    </div>
    
    <!-- modal -->
    <div class="modal fade" id="eventsModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Event Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Event: <span class="text-center" id="modal-title"></span></p>
                    <p>Details: <span id="modal-description"></span></p>
                    <p>Location: <span class="" id="modal-location"></span></p>
                    <p>Date: <span class="" id="modal-date"></span></p>
                    <p class="text-center">Categories</p>

                    <div id="alert-container" class="container mt-3">
                        <div id="alert-message" class="alert d-none" role="alert"></div>
                    </div>

                    <table class="table" id="modal-categories"></table>
        
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        $(document).ready(function () {

            var items = $(".event-item"); 
            var numItems = items.length;
            var perPage = 5;

            items.slice(perPage).hide();

            $('#pagination-container').pagination({
                items: numItems,
                itemsOnPage: perPage,
                prevText: "&laquo;",
                nextText: "&raquo;",
                onPageClick: function (pageNumber) {
                    var showFrom = perPage * (pageNumber - 1);
                    var showTo = showFrom + perPage;
                    items.hide().slice(showFrom, showTo).show();
                }
            });

            $(".view-event-btn").on("click", function () {
                let eventId = $(this).data("id");
                let title = $(this).data("title");
                let description = $(this).data("description");
                let location = $(this).data("location");
                let date = $(this).data("date");
                let categories = $(this).data("categories");

                $("#modal-title").text(title);
                $("#modal-description").text(description);
                $("#modal-location").text(location);
                $("#modal-date").text(date);
                $("#event_id").val(eventId);

                let categoryList = $("#modal-categories");
                categoryList.empty();

                categoryList.append(`
                    <thead>
                        <tr>
                            <td>Distance (in kilometer)</td>
                            <td>Price (php)</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody></tbody>
                `);

                let tableBody = categoryList.find("tbody");

                $.each(categories, function (index, item) {
                    tableBody.append(`
                        <tr>
                            <form action="database/db_joinevent.php" method="POST">
                                <input type="hidden" name="event_id" value="${eventId}">
                                <input type="hidden" name="category" value="${item.category}">
                                <td>${item.category}</td>
                                <td>${item.price}</td>
                                <td>
                                    <button type="submit" class="btn btn-success w-100 join-btn">
                                        Join
                                    </button>
                                </td>
                            </form>
                        </tr>
                    `);
                });

                $(".join-btn").on("click", function () {
                    let category = $(this).data("category");
                    $("#category").val(category);
                    $("#join-event-form").submit();
                });
  
            });
        });
    </script>

    <script>
        $(document).on("click", ".join-btn", function (e) {
            e.preventDefault();

            var userId = <?= $_SESSION['id_user']; ?>;
            let eventId = $(this).closest("tr").find("input[name='event_id']").val();
            let category = $(this).closest("tr").find("input[name='category']").val();

            $.ajax({
                url: "database/db_joinevent.php",
                type: "POST",
                data: {
                    id_user: userId,
                    id_events: eventId,
                    category: category
                },
                dataType: "json",
                success: function (response) {
                    if (response.status === "success") {
                        showAlert("✅ Successfully joined the event!", "success");
                    } 
                    else if (response.message === "You have already joined this event and category.") {
                        showAlert("⚠️ You have already joined this event and category!", "warning");
                    }
                    else {
                        showAlert("❌ Failed to join event. Please try again.", "danger");
                    }
                },
                error: function () {
                    showAlert("❌ Failed to join event. Please try again.", "danger");
                }
            });

            function showAlert(message, type) {
                let alertBox = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                                    ${message}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>`;

                $("#alert-container").html(alertBox);

                setTimeout(function () {
                    $(".alert").alert('close');
                }, 2000);
            }

        });

    </script>

    <script>
        $(document).ready(function () {
            $("#filter-btn").on("click", function () {
                let selectedCategories = [];

                $(".category-filter:checked").each(function () {
                    selectedCategories.push($(this).val());
                });

                $.ajax({
                    url: "database/db_filterevents.php",
                    type: "POST",
                    data: { categories: selectedCategories },
                    dataType: "html",
                    success: function (response) {
                        $("#events-container").html(response);
                        locaion.reload();
                    },
                    error: function () {
                        alert("Error fetching filtered events. Please try again.");
                    }
                });
            });
        });

    </script>

</body>
</html>