<?php 
    $currentPage = basename($_SERVER['PHP_SELF']); 
?>

<nav class="navbar navbar-expand-lg bg-light border-bottom border-dark">
    <div class="container-fluid">

        <a class="navbar-brand" href="index.php">
            <img src="resources/images/logo.png" class="img-fluid" height="100" style="max-height: 40px;">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link mynav-link mx-2 text-dark <?= ($currentPage == 'home.php') ? 'active' : '' ?>" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mynav-link mx-2 text-dark <?= ($currentPage == 'events.php') ? 'active' : '' ?>" href="events.php">Race Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mynav-link mx-2 text-dark <?= ($currentPage == 'schedules.php') ? 'active' : '' ?>" href="schedules.php">Schedules</a>
                </li>
            </ul>
        </div>

        <div class="d-flex align-items-center">
      
            <!-- <div class="form-check form-switch me-3">
                <input class="form-check-input" type="checkbox" role="switch" id="darkMode">
                <label class="form-check-label">
                    <i class="fa-solid fa-circle-half-stroke"></i>
                </label>
            </div>   -->
            
            <?php if(isset($_SESSION['id_user'])): ?>
                <div class="dropdown">
                    <a class="dropdown-toggle d-flex align-items-center text-success" type="button" data-bs-toggle="dropdown">
                        <?php
                            $profileImage = !empty($user['profilepath']) ? "resources/profilepics/" . $user['profilepath'] : "resources/images/defaultprofile.png";
                        ?>
                        <img src="<?= htmlspecialchars($profileImage, ENT_QUOTES, 'UTF-8') ?>" class="rounded-circle border" height="40" width="40" loading="lazy"/>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end px-2">
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="profile.php">
                                <i class="fa-solid fa-user me-2"></i>
                                <span>Profile</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="logout.php">
                                <i class="fa-solid fa-power-off me-2"></i> 
                                <span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn btn-dark rounded-0 me-3">Login</a>
                <a href="signup.php" class="btn btn-light border border-dark rounded-0">Signup</a>
            <?php endif; ?>
        </div>

    </div>
</nav>

<style>

    .mynav-link {
        position: relative;
        text-decoration: none;
        transition: color 0.3s ease-in-out;
    }

    .mynav-link::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        width: 0%;
        height: 2px; 
        background-color: black; 
        transition: width 0.3s ease-in-out;
    }

    .mynav-link:hover::after {
        width: 100%;
    }

    .mynav-link.active::after {
        width: 100%;
    }

</style>