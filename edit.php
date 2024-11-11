<?php
$BASE_URL = "http://localhost/library/";
$dir_url = "C:/xampp/htdocs/library/";

include_once($dir_url . "configuration\config.php");
include_once($dir_url . "models\book.php");
include_once($dir_url . "include\middleware.php");
include_once($dir_url . "configuration\db.php");

if (isset($_GET['id']) && $_GET['id'] > 0) {
  $book = getBookById($conn, $_GET['id']);
  if ($book->num_rows > 0) {
    $book = mysqli_fetch_assoc($book);
  }
}

if (isset($_POST['update'])) {
  // Fetch the original book data
  $originalBook = getBookById($conn, $_POST['id']);
  $originalBookData = mysqli_fetch_assoc($originalBook);

  // Perform the update
  $res = updateBook($conn, $_POST);

  if ($res) {
    $_SESSION['success'] = "Book has been updated successfully";

    // Determine changes
    $changes = [];
    foreach ($_POST as $key => $value) {
      if ($key != 'id' && $originalBookData[$key] != $value) {
        $changes[] = ucfirst($key) . ' changed from "' . $originalBookData[$key] . '" to "' . $value . '"';
      }
    }

    // Log the changes
    $details = implode(', ', $changes);
    logAction($conn, 'update', $_POST['id'], $_SESSION['user_id'], $details);

    header("LOCATION: " . $BASE_URL . "books.php");
  } else {
    $_SESSION['error'] = "Something went wrong, Try Again";
    header("LOCATION: " . $BASE_URL . "edit.php");
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
        <h4 class="fw-bold text-uppercase mt-4">Edit Books</h4>
      </div>
    </div>
    <div class="col-md-10">
      <div class="card">
        <div class="card-header">Fill the form</div>
        <div class="card-body">
          <form method="post" action="<?php echo $BASE_URL ?>edit.php">
            <input type="hidden" name="id" value="<?php echo $book['id'] ?>">

            <div class="row">
              <div class="col-md-6">
                <div class="mb-2">
                  <label for="exampleInputEmail1" class="form-label">Title</label>
                  <input type="text" name="title" class="form-control" value="<?php echo $book['title'] ?>" required id="exampleInputEmail1" aria-describedby="emailHelp" />
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-2">
                  <label for="exampleInputPassword1" class="form-label">Author Name</label>
                  <input type="text" name="author" class="form-control" value="<?php echo $book['author'] ?>" required id="exampleInputPassword1" />
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-2">
                  <label for="exampleInputPassword1" class="form-label">Isbn number</label>
                  <input type="text" name="isbn" class="form-control" value="<?php echo $book['isbn'] ?>" required id="exampleInputPassword1" />
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-2">
                  <label for="exampleInputPassword1" class="form-label">Publisher</label>
                  <input type="text" name="publisher" class="form-control" value="<?php echo $book['publisher'] ?>" required id="exampleInputPassword1" />
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-2">
                  <label for="exampleInputPassword1" class="form-label">Edition</label>
                  <input type="text" name="edition" class="form-control" value="<?php echo $book['edition'] ?>" required id="exampleInputPassword1" />
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-2">
                  <label for="exampleInputPassword1" class="form-label">Units</label>
                  <input type="text" name="units" class="form-control" value="<?php echo $book['units'] ?>" required id="exampleInputPassword1" />
                </div>
              </div>
            </div>

            <button type="submit" name="update" class="btn btn-primary">Update</button>
            <a href="books.php" class="btn btn-secondary">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include_once($dir_url . "include/footer.php") ?>