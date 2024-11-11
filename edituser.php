<?php
$BASE_URL = "http://localhost/library/";
$dir_url = "C:/xampp/htdocs/library/";

include_once($dir_url . "configuration\config.php");
include_once($dir_url . "models\user.php");
include_once($dir_url . "include\middleware.php");
include_once($dir_url . "configuration\db.php");

if (isset($_GET['id']) && $_GET['id'] > 0) {
  $user = getuserById($conn, $_GET['id']);
  if ($user->num_rows > 0) {
    $user = mysqli_fetch_assoc($user);
  }
}

// update user 


if (isset($_POST['update'])) {
  $originaluser = getuserById($conn, $_POST['id']);
  $originaluserData = mysqli_fetch_assoc($originaluser);

  $res = updateuser($conn, $_POST);

  if ($res) {
    $_SESSION['success'] = "user has been updated successfully";
    $changes = [];
    foreach ($_POST as $key => $value) {
      if ($key != 'id' && $originaluserData[$key] != $value) {
        $changes[] = ucfirst($key) . ' changed from "' . $originaluserData[$key] . '" to "' . $value . '"';
      }
    }

    // Log the changes
    $details = implode(', ', $changes);
    logActionuser($conn, 'update', $_SESSION['book_id'], $_POST['id'], $details);

    header("LOCATION: " . $BASE_URL . "users.php");
  } else {
    $_SESSION['error'] = "Something went wrong, Try Again";
    header("LOCATION: " . $BASE_URL . "edituser.php");
  }
}




// if (isset($_POST['update'])) {
//   $res = updateuser($conn, $_POST);
//   if ($res == true) {
//     $_SESSION['success'] = "user has been updated successfully";
//     header("LOCATION: " . $BASE_URL . "users.php");
//   } else {
//     $_SESSION['error'] = "Something went wrong,Try Again";
//     header("LOCATION: " . $BASE_URL . "edituser.php");
//   }
// }
?>

<?php
include_once($dir_url . "include/header.php");
include_once($dir_url . "include/topbar.php");
include_once($dir_url . "include/sidebar.php");
?>

<main class="mt-5 pt-3" id="users">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <h4 class="fw-bold text-uppercase mt-4">Edit users</h4>
      </div>
    </div>
    <div class="col-md-10">
      <div class="card">
        <div class="card-header">Fill the form</div>
        <div class="card-body">
          <form method="post" action="<?php echo $BASE_URL ?>edituser.php">
            <input type="hidden" name="id" value="<?php echo $user['id'] ?>">

            <div class="row">
              <div class="col-md-6">
                <div class="mb-2">
                  <label for="exampleInputEmail1" class="form-label">name</label>
                  <input type="text" name="name" class="form-control" value="<?php echo $user['name'] ?>" required aria-describedby="emailHelp" />
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-2">
                  <label for="exampleInputPassword1" class="form-label">email</label>
                  <input type="email" name="email" class="form-control" value="<?php echo $user['email'] ?>" required />
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-2">
                  <label for="exampleInputPassword1" class="form-label">Phone number</label>
                  <input type="text" name="phone" class="form-control" value="<?php echo $user['phone'] ?>" required />
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-2">
                  <label for="exampleInputPassword1" class="form-label">Dob</label>
                  <input type="date" name="dob" class="form-control" value="<?php echo $user['dob'] ?>" required />

                </div>
              </div>
            </div>

            <button type="submit" name="update" class="btn btn-primary">Update</button>
            <a href="users.php" class="btn btn-secondary">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include_once($dir_url . "include/footer.php") ?>