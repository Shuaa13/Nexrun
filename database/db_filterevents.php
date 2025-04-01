<?php

    session_start();
    include 'dbcon.php';
    include 'db_fetchevents.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $selectedCategories = $_POST['categories'] ?? [];

        $filteredEvents = array_filter($events, function ($event) use ($selectedCategories) {
            foreach ($event['categories'] as $category) {
                if (in_array($category['category'], $selectedCategories)) {
                    return true;
                }
            }
            return false;
        });

        foreach ($filteredEvents as $event): ?>
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
        <?php endforeach;
    }

?>
