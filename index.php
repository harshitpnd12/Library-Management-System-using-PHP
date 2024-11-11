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


if (isset($_POST['save'])) {
  $res = login($conn, $_POST);
  if ($res['status'] == true) {
    $_SESSION['is_admins_login'] = true;
    $_SESSION['admins'] = $res['admins'];
    header("LOCATION: " . $BASE_URL . "dashboard.php");
    exit;
  } else {
    $_SESSION['error'] = "Invalid email or password";
    header("LOCATION: " . $BASE_URL . "index.php");
    exit;
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="css\styless.css" />
  <title>Login page</title>
  <style>
    .bg {

      background: linear-gradient(to bottom, #ffffff 0%, #e5e5e5 100%);

    }
  </style>
</head>

<body class="bg">
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="row">
      <div class="col-md-12 login-form">
        <div class="card mb-3" style="max-width: 900px">
          <div class="row g-0">
            <div class="col-md-5">
              <img src="https://plus.unsplash.com/premium_photo-1703701579680-3b4c2761aa47?w=400&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8c2Nob29sJTIwbGlicmVyeXxlbnwwfHwwfHx8MA%3D%3D" class="img-fluid rounded-start" alt="..." />
            </div>
            <div class="col-md-7">
              <div class="card-body">
                <div class="card-title  fw-bold text-center">
                  <img src="https://cdn.shopify.com/s/files/1/0587/0741/1096/files/Obeetee_logo-black.svg?v=1671691758" class="img-fluid"><br />
                </div>
                <h3 class="card-title  fw-bold text-center pt-2">
                  Library Management Portal
                </h3>
                <p class="card-text text-center fs-4 pt-2">
                  Enter The Username and Password
                </p>
                <?php include_once($dir_url . "include/alert.php"); ?>
                <form method="POST" action="<?php echo $BASE_URL ?>index.php">
                  <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="text" class="form-control" name="email" required />
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required />
                  </div>
                  <button type="submit" name="save" class="btn btn-primary">Login</button>
                </form>
                <!-- <hr />
                <p>
                  <a href="userlogin.php" class="link-underline-light">User Login</a>
                  <br />
                  <a href="Forget-password.php" class="link-underline-light">Forget Password</a> -->
                <!-- <a href="Forget-password.php" class="link-underline-light">Register new user</a> -->
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>