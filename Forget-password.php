<?php

$BASE_URL = "http://localhost/library/";
// if (isset($_SESSION['is_admins_login'])) {
//   header("LOCATION: " . $BASE_URL . "dashboard.php");
//   exit;
// }
$dir_url = "C:/xampp/htdocs/library/";

include_once($dir_url . "configuration\config.php");
include_once($dir_url . "configuration\db.php");
include_once($dir_url . "\models\login.php");



if (isset($_POST['save'])) {
  $res = forgetpassword($conn, $_POST);
  print_r($res);
  exit;
  if ($res['status'] == true) {
    $_SESSION['success'] = "Reset password code has been sent successfully";
    header("LOCATION: " . $BASE_URL . "reset_password.php");
    exit;
  } else {
    $_SESSION['error'] = "Email not found";
    header("LOCATION: " . $BASE_URL . "forget-password.php");
    exit;
  }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css\style.css" />
  <link rel="stylesheet" href="css\dataTables.bootstrap5.min.css" />
  <link rel="stylesheet" href="css\bootstrap.min.css" />
  <title>Forget-password</title>
  <style>
    .bg {
      /* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#ffffff+0,e5e5e5+100;White+3D */
      background: linear-gradient(to bottom, #ffffff 0%, #e5e5e5 100%);
      /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */

    }
  </style>
</head>

<body class="bg">
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="row">
      <div class="col-md-12 login-form">
        <div class="card mb-3" style="max-width: 900px">
          <div class="row g-0">
            <div class="col-md-4">
              <img src="https://plus.unsplash.com/premium_photo-1703701579680-3b4c2761aa47?w=400&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8c2Nob29sJTIwbGlicmVyeXxlbnwwfHwwfHx8MA%3D%3D" class="img-fluid rounded-start" alt="..." />
            </div>
            <div class="col-md-8">
              <div class="card-body">
                <h1 class="card-title  fw-bold text-center">
                  <img src="https://cdn.shopify.com/s/files/1/0587/0741/1096/files/Obeetee_logo-black.svg?v=1671691758" class="img-fluid"><br />
                </h1>
                <p class="card-text text-center fs-4 pt-2">Enter The Email To Reset The Password</p>
                <?php include_once($dir_url . "include/alert.php"); ?>
                <form method="POST" action="<?php echo $BASE_URL ?>forget-password.php">
                  <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input type="email" class="form-control" name="email" />
                  </div>
                  <button type="submit" name="save" class="btn btn-primary">
                    Submit
                  </button>
                </form>
                <hr />
                <p>
                  <a href="index.php" class="card-text text-center link-underline-light">Login</a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="js\bootstrap.bundle.min.js"></script>
  <script src="js\1c26fb5c51.js"></script>
</body>

</html>