<?php

$BASE_URL = "http://localhost/library/";
if (isset($_SESSION['is_admins_login'])) {
  header("LOCATION: " . $BASE_URL . "dashboard.php");
  exit;
}
$dir_url = "C:/xampp/htdocs/library/";

include_once($dir_url . "configuration\config.php");
include_once($dir_url . "configuration\db.php");
include_once($dir_url . "\models\login.php");



// if (isset($_POST['save'])) {
//   $res = forgetpassword($conn, $_POST);
//   if ($res['status'] == true) {
//     header("LOCATION: " . $BASE_URL . "reset-password.php");
//     exit;
//   } else {
//     $_SESSION['error'] = "Invalid email";
//     exit;
//   }
// }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css\style.css" />
  <link rel="stylesheet" href="css\dataTables.bootstrap5.min.css" />
  <link rel="stylesheet" href="css\bootstrap.min.css" />

  <title>Reset Password</title>
</head>

<body style="background-color: #212529">
  <div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="row">
      <div class="col-md-12 login-form">
        <div class="card mb-3" style="max-width: 900px">
          <div class="row g-0">
            <div class="col-md-5">
              <img src="image\image.webp" class="img-fluid rounded-start im" />
            </div>
            <div class="col-md-7">
              <div class="card-body">
                <h1 class="card-title text-uppercase fw-bold">
                  Obeetee library
                </h1>
                <p class="card-text">Reset password</p>
                <!-- <?php include_once($dir_url . "include/alerts.php"); ?> -->

                <form method="post" action="<?php echo $BASE_URL ?>reset_password.php">
                  <div class="mb-3">
                    <label class="form-label">Reset password code</label>
                    <input type="text" class="form-control" name="reset_code" />
                  </div>

                  <div class="mb-3">
                    <label class="form-label">New password</label>
                    <input type="password" class="form-control" name="password" />
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Confirm password</label>
                    <input type="password" class="form-control" name="conf_pass" />
                  </div>
                  <button type="submit" name="submit" class="btn btn-primary">
                    Submit
                  </button>
                </form>

                <hr />

                <a href="index.php" class="card-text text-center link-underline-light">Login Now</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="./assets/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>