<?php 

    session_start();

    if(isset($_SESSION['id_user'])) {
        include 'database/db_fetchuser.php'; 
    }

    if(!isset($_SESSION['id_user'])) {
        header("Location: login.php");
        exit();
    }

    include 'database/dbcon.php';

    $user_id = $_SESSION['id_user'];
    $current_date = date("Y-m-d");
  
    $query = "
        SELECT DISTINCT events.id_event, events.date, events_category.category
        FROM user_events
        JOIN events ON user_events.id_events = events.id_event
        JOIN events_category ON user_events.category = events_category.category
        WHERE user_events.id_user = ? 
        AND STR_TO_DATE(events.date, '%Y-%m-%d') <= STR_TO_DATE(?, '%Y-%m-%d')
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $user_id, $current_date);
    $stmt->execute();
    $result = $stmt->get_result();

    $total_runs = 0;
    $total_distance = 0;

    while ($row = $result->fetch_assoc()) {
        $total_runs++; 
        $total_distance += intval($row['category']);
    }

    $stmt->close();
    $conn->close();
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'metadata.php'; ?>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container my-5">
        <div class="row mb-3">
            <div class="col-md-4 mb-3">
                <div class="p-3 border rounded shadow-sm">

                    <form action="database/db_profilepic.php" method="POST" enctype="multipart/form-data">  
                        <button  type ="submit" class="btnSavePic d-none btn btn-sm btn-warning">Save image</button>
                        <div class="d-flex justify-content-center align-items-center mb-3">
                            <div class="d-inline-flex position-relative">
                                
                                    <input class="inputProfilePic d-none" type="file" name="profilePic" accept="image/*"/>
                                    <button class="btnProfilePic btn btn-dark  position-absolute bottom-0 end-0 rounded-circle p-0" 
                                        style="width: 40px; height: 40px;"
                                    >
                                        <i class="fa-solid fa-camera"></i>
                                    </button>
                            
                                <?php
                                    $profileImage = !empty($user['profilepath']) ? "resources/profilepics/" . $user['profilepath'] : "resources/images/defaultprofile.png";
                                ?>
                                <img src="<?= htmlspecialchars($profileImage, ENT_QUOTES, 'UTF-8') ?>" class="rounded-circle border" height="100" width="100" />
                            </div>

                            <span class="ms-1">
                                <?= $user['firstname'] ?> 
                                <?= !empty($user['middlename']) ? strtoupper(substr($user['middlename'], 0, 1)) . '.' : '' ?> 
                                <?= $user['lastname'] ?>
                            </span>
                        </div>
                    </form>

                    <div>
                        <h5>Information</h5>
                        <table class="mb-3">
                            <tbody class="">
                                <tr>
                                    <td>Age</td>
                                    <td class="ps-5">
                                        <?php
                                            $birthDate = new DateTime($user['bdate']);
                                            $today = new DateTime();
                                            $age = $today->diff($birthDate)->y;
                                            echo $age;
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Gender</td>
                                    <td class="ps-5">
                                        <?php 
                                            $genders = [1 => "Male", 2 => "Female", 3 => "Others"];
                                            $gndr = $genders[$user['gender']] ?? null;
                                            echo $gndr;
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Occupation</td>
                                    <td class="ps-5"><?= $user['occupation'] ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="btn btn-dark rounded-0" type="button" data-bs-toggle="modal" data-bs-target="#editInfoModal">Edit Info</button>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="border border-dark rounded p-3">
                    <h4 class="">Personal Stats</h4>
                    <small>Based on finished runs</small>
                    <div class="mb-4"></div>
                    <div class="row">
                        <div class="col-md-6 text-center">
                            <div><i class="fa-solid fa-flag-checkered"></i></div>
                            <div>Runs</div>
                            <div class="h1"><?= $total_runs; ?></div>
                        </div>
                        <div class="col-md-6 text-center">
                            <div><i class="fa-solid fa-person-running"></i></div>
                            <div>Total Distance in kilometer</div>
                            <div class="h1"><?= $total_distance; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="p-3 border rounded shadow-sm">
                    <h5>Contact</h5>
                    <table class="mb-3">
                        <tbody>
                            <tr>
                                <td>Phone</td>
                                <td class="ps-5"><?= $user['phone'] ?></td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td class="ps-5"><?= $user['address'] ?></td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td class="ps-5"><?= $user['email'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-dark rounded-0" type="button" data-bs-toggle="modal" data-bs-target="#editContactModal">Edit Contact</button>
                </div>
            </div>
            <div class="col-md-8">
                <div class="border border-dark rounded p-3">
                    <h4>Races</h4>
                    <div class="my-3 text-center">
                        <a href="schedules.php" class="btn btn-success btn-lg rounded-0">View Races</a>
                    </div> 
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Info Modal -->
    <div class="modal fade" id="editInfoModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="database/db_editinfo.php" method="POST">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label class="text-dark">Lastname</label>
                            <input type="text" class="form-control" name="lastname" autocomplete="off" value="<?= $user['lastname'] ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-dark">Firstname</label>
                            <input type="text" class="form-control" name="firstname" autocomplete="off" value="<?= $user['firstname'] ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-dark">Middlename</label>
                            <input type="text" class="form-control" name="middlename" autocomplete="off" value="<?= $user['middlename'] ?>">
                        </div>
                  
                        <select class="form-select mb-3" name="gender">
                            <option value="" <?= (empty($user['gender'])) ? 'selected' : '' ?> disabled class="dropdown-item">Select Gender</option>
                            <option value="1" <?= ($user['gender'] == 1) ? 'selected' : '' ?> class="dropdown-item">Male</option>
                            <option value="2" <?= ($user['gender'] == 2) ? 'selected' : '' ?> class="dropdown-item">Female</option>
                            <option value="3" <?= ($user['gender'] == 3) ? 'selected' : '' ?> class="dropdown-item">Others</option>
                        </select>

                        <div class="form-group mb-3">
                            <label class="text-dark">Occupation</label>
                            <input type="text" class="form-control" name="occupation" autocomplete="off" value="<?= $user['occupation'] ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-dark">Birth Date</label>
                            <input type="date" class="form-control" name="bdate" autocomplete="off" value="<?= $user['bdate'] ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-dark rounded-0">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Contact Modal -->
    <div class="modal fade" id="editContactModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="database/db_editcontact.php" method="POST">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label class="text-dark">Phone</label>
                            <input type="text" class="form-control" name="phone" autocomplete="off" value="<?= $user['phone'] ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-dark">Address</label>
                            <input type="text" class="form-control" name="address" autocomplete="off" value="<?= $user['address'] ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label class="text-dark">Email</label>
                            <input type="text" class="form-control" name="email" autocomplete="off" value="<?= $user['email'] ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-dark rounded-0">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        $(document).ready(function() {
           
            $(".btnProfilePic").click(function(e){
                e.preventDefault(); 
                $(".inputProfilePic").click();
            });

            $(".inputProfilePic").change(function(event) {
                let input = event.target;
                if (input.files && input.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $("img.rounded-circle").attr("src", e.target.result);
                        $(".btnSavePic").removeClass("d-none");
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            });

            $(".btnSavePic").click(function(e){
                $(".btnSavePic").addClass("d-none");
            });

        });
    </script>
</body>

</html>