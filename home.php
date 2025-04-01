<?php 

    session_start();

    if(isset($_SESSION['id_user'])) {
        include 'database/db_fetchuser.php'; 
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
        <div class="my-3 col-md-10 mx-auto">
            <div id="carouselExampleIndicators" class="carousel slide">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                    <img src="resources/images/10056533.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                    <img src="resources/images/10147621.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                    <img src="resources/images/566.jpg" class="d-block w-100" alt="...">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

        <div class="my-3 col-md-10 mx-auto">
            Welcome to NexRun – your ultimate marathon and fun run schedule viewer!
            Discover upcoming races, filter events by distance, and stay updated with the latest running events near you. Whether you're a seasoned marathoner or a casual fun-run enthusiast, NexRun makes finding and joining events easier than ever.
            Get ready to lace up, hit the track, and chase your next finish line!
        </div>
    </div>

  

    <?php include 'footer.php'; ?>
</body>

</html>