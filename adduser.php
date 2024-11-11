<?php
$BASE_URL = "http://localhost/library/";
$dir_url = "C:/xampp/htdocs/library/";

include_once($dir_url . "configuration\config.php");
include_once($dir_url . "models\user.php");
include_once($dir_url . "include\middleware.php");
include_once($dir_url . "configuration\db.php");

// user functionality 
if (isset($_POST['save'])) {
  // checkapos($conn, $_POST);
  $res = storeuser($conn, $_POST);
  if ($res == true) {
    $_SESSION['success'] = "user has been added successfully";
    header("LOCATION: " . $BASE_URL . "users.php");
  } else {
    $_SESSION['error'] = "Something went wrong,Try Again";
    header("LOCATION: " . $BASE_URL . "users.php");
  }
}
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
        <h4 class="fw-bold text-uppercase mt-4">Add Users</h4>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">Fill the form</div>
        <div class="card-body">
          <form method="post" action="<?php echo $BASE_URL ?>adduser.php">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">Full Name</label>
                  <input type="text" class="form-control" name="name" id="exampleInputEmail1" aria-describedby="emailHelp" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">Email Id</label>
                  <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">Phone number</label>
                  <input type="text" class="form-control" name="phone" id="exampleInputEmail1" aria-describedby="emailHelp" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">DOB</label>
                  <input type="date" class="form-control" name="dob" id="exampleInputEmail1" aria-describedby="emailHelp" />
                </div>
              </div>
            </div>

            <button type="submit" name="save" class="btn btn-primary">Save</button>
            <a href="users.php" class="btn btn-secondary">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include_once($dir_url . "include/footer.php") ?>