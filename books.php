<?php
$BASE_URL = "http://localhost/library/";
$dir_url = "C:/xampp/htdocs/library/";

include_once($dir_url . "configuration\config.php");
include_once($dir_url . "configuration\db.php");
include_once($dir_url . "\models\book.php");
include_once($dir_url . "include\middleware.php");

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$books = getbooks($conn);


// Export books to Excel
if (isset($_GET['action']) && $_GET['action'] == 'export') {
  $data = $books;
  $spreadsheet = new Spreadsheet();
  $sheet = $spreadsheet->getActiveSheet();

  // Set header row
  $sheet->setCellValue('A1', 'ID');
  $sheet->setCellValue('B1', 'Title');
  $sheet->setCellValue('C1', 'Author');
  $sheet->setCellValue('D1', 'ISBN');
  $sheet->setCellValue('E1', 'Library Book Id');
  $sheet->setCellValue('F1', 'Publisher');
  $sheet->setCellValue('G1', 'Edition');
  $sheet->setCellValue('H1', 'Units');

  // Populate data rows
  $rowIndex = 2;
  while ($row = $data->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowIndex, $row['id']);
    $sheet->setCellValue('B' . $rowIndex, $row['title']);
    $sheet->setCellValue('C' . $rowIndex, $row['author']);
    $sheet->setCellValue('D' . $rowIndex, $row['isbn']);
    $sheet->setCellValue('E' . $rowIndex, $row['library_id']);
    $sheet->setCellValue('F' . $rowIndex, $row['publisher']);
    $sheet->setCellValue('G' . $rowIndex, $row['edition']);
    $sheet->setCellValue('H' . $rowIndex, $row['units']);
    $rowIndex++;
  }

  // Set headers to download the file
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="books.xlsx"');
  header('Cache-Control: max-age=0');

  $writer = new Xlsx($spreadsheet);
  $writer->save('php://output');
  exit();
}

// delete books
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
  $del = deleteBook($conn, $_GET['id']);
  if ($del) {
    $_SESSION['success'] = 'Book deleted successfully ';
  }
  header("LOCATION:" . $BASE_URL . "books.php");
  exit;
}

// Status books
if (isset($_GET['action']) && $_GET['action'] == 'status') {
  $upd = updateBooks($conn, $_GET['id'], $_GET['status']);
  if ($upd) {
    if ($_GET['status'] == 1)
      $msg = "Book has been successfully activated";
    else $msg = "Book has been successfully deactivated";
    $_SESSION['success'] = $msg;
  }
  header("LOCATION:" . $BASE_URL . "books.php");
  exit;
}

include_once($dir_url . "include/header.php");
include_once($dir_url . "include/topbar.php");
include_once($dir_url . "include/sidebar.php");
?>
<main class="mt-5 pt-3" id="books">
  <div class="container-fluid">
    <div class="row ">
      <div class="col-md-12">
        <!-- <?php include_once($dir_url . "include/alert.php"); ?> -->
        <h4 class="fw-bold text-uppercase mt-4">Books</h4>
        <p>Records of books</p>
      </div>
      <div>
        <a href="addbook.php"><button type="submit" class="btn btn-success text-uppercase me-auto">
            Add Book
          </button>
        </a>
        <a href="?action=export"><button type="submit" class="btn btn-warning ms-3">
            Export <i class="fa-solid fa-table-cells"></i>
          </button>
        </a>
      </div>
    </div>
    <div class="table-responsive">

      <table id="example" class="table table-striped table-bordered mt-2">
        <thead>
          <tr>
            <th class="bg-dark" style="color: #ffffff;" scope="col">#</th>
            <th class="bg-dark" style="color: #ffffff;" scope="col">Title</th>
            <th class="bg-dark" style="color: #ffffff;" scope="col">Author</th>
            <th class="bg-dark" style="color: #ffffff;" scope="col">ISBN</th>
            <th class="bg-dark" style="color: #ffffff;" scope="col">Library Book Id</th>
            <th class="bg-dark" style="color: #ffffff;" scope="col">Publisher</th>
            <th class="bg-dark" style="color: #ffffff;" scope="col">Edition</th>
            <th class="bg-dark" style="color: #ffffff;" scope="col">Units</th>
            <th class="bg-dark" style="color: #ffffff;" scope="col">Status</th>
            <th class="bg-dark" style="color: #ffffff;" scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($books->num_rows > 0) {
            $i = 1;
            while ($row = $books->fetch_assoc()) {
          ?>
              <tr>
                <th scope="row"><?php echo $i++ ?></th>
                <td><?php echo $row['title'] ?> </td>
                <td><?php echo $row['author'] ?> </td>
                <td><?php echo $row['isbn'] ?> </td>
                <td><?php echo $row['library_id'] ?> </td>
                <td><?php echo $row['publisher'] ?> </td>
                <td><?php echo $row['edition'] . " edition" ?> </td>
                <td><?php echo $row['units'] ?> </td>
                <td>
                  <?php if ($row['units'] >= 1) {
                    echo '<span class="badge text-bg-success">Available</span>';
                  } else echo '<span class="badge text-bg-danger">Not available</span>';
                  ?>
                </td>
                <td>
                  <a href="<?php echo $BASE_URL ?>edit.php?id=<?php echo $row['id'] ?>" class="btn btn-primary btn-sm">Edit</a>

                  <a href="addissuebook.php" class="btn btn-secondary btn-sm">
                    Issue
                  </a>

                  <!-- <a href="<?php echo $BASE_URL ?>books.php?action=delete&id=<?php echo $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a> -->
                </td>
              </tr>
          <?php }
          } ?>
        </tbody>
      </table>
    </div>
  </div>
</main>
<?php include_once($dir_url . "include/footer.php") ?>