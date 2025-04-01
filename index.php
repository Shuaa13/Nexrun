<?php 

    session_start();
    
    if(isset($_SESSION['id_user'])) {
        include 'database/db_fetchuser.php'; 
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'metadata.php'; ?>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <div class="d-flex flex-column min-vh-100">
        <div class="container flex-grow-1">
            <div class="row mt-5 mb-3 pt-5 align-items-center">
                <div class="col-md-5">

                    <div class="px-4">
                        <img src="resources/images/logo.png" class="img-fluid w-100 mb-4">
                    </div>
                   
                    <h4 class="mb-4">
                        Welcome to NexRun.com. Discover your next challenge, 
                        explore the best race events near you, and make every finish line a new beginning!
                    </h4>
                    <div class="d-flex justify-content-center mb-4">
                        <a href="home.php" class="btn btn-lg px-5 btn-dark rounded-0">Let's Explore</a>
                    </div>
                </div>
                <div class="col-md-7 ps-4 slider">
                    <img src="resources/images/index_slide1.jpg" class="img-fluid rounded-4 shadow-sm active">
                    <img src="resources/images/index_slide2.jpg" class="img-fluid rounded-4 shadow-sm">
                    <img src="resources/images/index_slide3.jpg" class="img-fluid rounded-4 shadow-sm">
                </div>
            </div>
        </div>

        <?php include 'footer.php'; ?>

    </div>

    <script>
        $(document).ready(function() {
            let current = 0;
            let images = $(".slider img");
            let total = images.length;

            images.hide().eq(current).show();

            setInterval(function() {
                images.eq(current).fadeOut(1); 
                current = (current + 1) % total; 
                images.eq(current).fadeIn(1); 
            }, 3000);
        });
    </script>

</body>
</html>