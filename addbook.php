<?php

$BASE_URL = "http://localhost/library/";
$dir_url = "C:/xampp/htdocs/library/";

include_once($dir_url . "configuration\config.php");
include_once($dir_url . "include\middleware.php");
include_once($dir_url . "models\book.php");
include_once($dir_url . "configuration\db.php");

if (isset($_POST['save'])) {
  $res = storeBook($conn, $_POST);
  if ($res == true) {
    $_SESSION['success'] = "Book has been added successfully";
    header("LOCATION: " . $BASE_URL . "books.php");
  } else {
    $_SESSION['error'] = "Something went wrong,Try Again";
    header("LOCATION: " . $BASE_URL . "books.php");
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
        <h4 class="fw-bold text-uppercase ms-3 mt-4">Add Books</h4>
      </div>
    </div>
    <div class="col-md-10">
      <div class="card">
        <div class="card-header">Fill the form</div>
        <div class="card-body">
          <form method="post" action="<?php echo $BASE_URL ?>addbook.php">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-2">
                  <class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required aria-describedby="emailHelp" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-2">
                  <class="form-label">Author Name</label>
                    <input type="text" name="author" class="form-control" required />
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-2">
                  <class="form-label">Isbn number</label>
                    <input type="text" name="isbn" class="form-control" required />
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-2">
                  <class="form-label">Publisher</label>
                    <input type="text" name="publisher" class="form-control" required />
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-2">
                  <class="form-label">Edition</label>
                    <input type="text" name="edition" class="form-control" required />
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-2">
                  <class="form-label">Units</label>
                    <input type="text" name="units" class="form-control" required />
                </div>
              </div>
            </div>

            <button type="submit" name="save" class="btn btn-primary">Save</button>
            <a href="books.php" class="btn btn-secondary">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include_once($dir_url . "include/footer.php") ?>