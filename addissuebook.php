<?php
$BASE_URL = "http://localhost/library/";
$dir_url = "C:/xampp/htdocs/library/";

include_once($dir_url . "configuration\config.php");
include_once($dir_url . "include\middleware.php");
include_once($dir_url . "models\issuebook.php");
include_once($dir_url . "models\book.php");
include_once($dir_url . "models\user.php");
include_once($dir_url . "configuration\db.php");

$currentDate = date('Y-m-d');

if (isset($_POST['save'])) {
  $res = StoreIssueBook($conn, $_POST);
  if ($res == true) {
    $_SESSION['success'] = "Book has been added successfully";
    header("LOCATION: " . $BASE_URL . "issuebooks.php");
  } else {
    $_SESSION['error'] = "Books not available";
    header("LOCATION: " . $BASE_URL . "addissuebook.php");
    exit;
  }
}
?>

<?php
include_once($dir_url . "include/header.php");
include_once($dir_url . "include/topbar.php");
include_once($dir_url . "include/sidebar.php");
?>

<main class="mt-5 pt-3" id="books">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <h4 class="fw-bold text-uppercase ms-3 mt-4">Issue Book</h4>
      </div>
    </div>
    <div class="col-md-10">
      <div class="card">
        <div class="card-header">Fill the form</div>
        <?php include_once($dir_url . "include/alert.php"); ?>
        <div class="card-body">
          <form method="post" action="<?php echo $BASE_URL ?>addissuebook.php">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">Select user</label>
                  <?php
                  $users = getusers($conn);
                  ?>
                  <select name="user_id" class="form-control">
                    <option value="">Please select</option>
                    <?php while ($row = $users->fetch_assoc()) { ?>
                      <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">Select Book</label>
                  <?php
                  $books = getbooks($conn);
                  ?>
                  <select name="book_id" class="form-control">
                    <option value="">Please select</option>
                    <?php while ($row = $books->fetch_assoc()) { ?>
                      <option value="<?php echo $row['id'] ?>"><?php echo $row['title'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">issue Date</label>
                  <input type="date" class="form-control" name="issue_date" value="<?php echo $currentDate; ?>" required />
                </div>
              </div>


              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">Expected Return Date</label>
                  <input type="date" class="form-control" name="return_date" required />
                </div>
              </div>

              <div class="col-md-12">
                <button type="submit" name="save" class="btn btn-success">
                  Save
                </button>

                <a href="issuebooks.php" class="btn btn-secondary">Cancel</a>

                <a href="adduser.php"><button type="submit" class="btn btn-success ms-auto">
                    Add user
                  </button>
                </a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</main>
<?php include_once($dir_url . "include/footer.php") ?>