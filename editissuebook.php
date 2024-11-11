<?php
$BASE_URL = "http://localhost/library/";
$dir_url = "C:/xampp/htdocs/library/";

include_once($dir_url . "configuration/config.php");
include_once($dir_url . "models/issuebook.php");
include_once($dir_url . "models/book.php");
include_once($dir_url . "models/user.php");
include_once($dir_url . "models/return.php");
include_once($dir_url . "include/middleware.php");
include_once($dir_url . "configuration/db.php");

$currentDate = date('Y-m-d');

if (isset($_GET['id']) && $_GET['id'] > 0) {
  $issuebook = getissuebookById($conn, $_GET['id']);
  if ($issuebook->num_rows > 0) {
    $issuebook = mysqli_fetch_assoc($issuebook);
  }
}

if (isset($_POST['save'])) {
  $res = StoreBookrecord($conn, $_POST);
  if ($res == true) {
    $_SESSION['success'] = "Book Return successfully";
    header("LOCATION: " . $BASE_URL . "booksrecord.php");
    exit;
  } else {
    $_SESSION['error'] = "Books not available";
    header("LOCATION: " . $BASE_URL . "editissuebook.php");
    exit;
  }
}

// if (isset($_POST['update'])) {
//   $res = updateBook($conn, $_POST);
//   if ($res == true) {
//     $_SESSION['success'] = "Book has been updated successfully";
//     header("Location: " . $BASE_URL . "issuebooks.php");
//     exit();
//   } else {
//     $_SESSION['error'] = "Something went wrong, try again. Error: " . $conn->error;
//     header("Location: " . $BASE_URL . "editissuebook.php");
//     exit();
//   }
// }
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
        <h4 class="fw-bold text-uppercase mt-4">Return Books</h4>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">Fill the form</div>
        <div class="card-body">
          <form method="post" action="<?php echo $BASE_URL ?>editissuebook.php">
            <input type="hidden" name="id" value="<?php echo $issuebook['id'] ?>">

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">Select User</label>
                  <?php
                  $users = getusers($conn);
                  ?>
                  <select name="user_id" class="form-control">
                    <option value="">Please select</option>
                    <?php
                    while ($row = $users->fetch_assoc()) {
                      $selected = ($row['id'] == $issuebook['user_id']) ? "selected" : "";
                    ?>
                      <option <?php echo $selected ?> value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
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
                    <?php
                    while ($row = $books->fetch_assoc()) {
                      $selected = ($row['id'] == $issuebook['book_id']) ? "selected" : "";
                    ?>
                      <option <?php echo $selected ?> value="<?php echo $row['id'] ?>"><?php echo $row['title'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">Issue Date</label>
                  <input type="date" class="form-control" name="issue_date" value="<?php echo $issuebook['issue_date']; ?>" required />
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">Return Date</label>
                  <input type="date" class="form-control" name="return_date" value="<?php echo $currentDate; ?>" required />
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">Book Condition</label>
                  <input type="text" class="form-control" name="bookcondition" required />
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label">Fine</label>
                  <input type="text" class="form-control" name="fine" required />
                </div>
              </div>


            </div>
            <button type="submit" name="save" class="btn btn-primary">Return</button>
            <a href="issuebooks.php" class="btn btn-secondary">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include_once($dir_url . "include/footer.php") ?>