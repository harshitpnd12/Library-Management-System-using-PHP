<?php
$BASE_URL = "http://localhost/library/";
$dir_url = "C:/xampp/htdocs/library/";

include_once($dir_url . "configuration\config.php");
include_once($dir_url . "configuration\db.php");
include_once($dir_url . "\models\issuebook.php");
include_once($dir_url . "include\middleware.php");

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$issuebooks = getissuebooks($conn);


// Export books to Excel
if (isset($_GET['action']) && $_GET['action'] == 'export') {
  $data = $issuebooks;
  $spreadsheet = new Spreadsheet();
  $sheet = $spreadsheet->getActiveSheet();

  // Set header row
  $sheet->setCellValue('A1', 'ID');
  $sheet->setCellValue('B1', 'USER NAME');
  $sheet->setCellValue('C1', 'BOOK NAME');
  $sheet->setCellValue('D1', 'ISSUE ID');
  $sheet->setCellValue('E1', 'ISSUE DATE');
  $sheet->setCellValue('F1', 'EXPECTED RETURN DATE');

  // Populate data rows
  $rowIndex = 2;
  while ($row = $data->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowIndex, $row['id']);
    $sheet->setCellValue('B' . $rowIndex, $row['user_name']);
    $sheet->setCellValue('C' . $rowIndex, $row['book_title']);
    $sheet->setCellValue('D' . $rowIndex, $row['issue_id']);
    $sheet->setCellValue('E' . $rowIndex, $row['issue_date']);
    $sheet->setCellValue('F' . $rowIndex, $row['return_date']);
    $rowIndex++;
  }

  // Set headers to download the file
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="issuebookS.xlsx"');
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

// delete issuebooks
// if (isset($_GET['action']) && $_GET['action'] == 'delete') {
//   $del = deleteissuebook($conn, $_GET['id']);
//   if ($del) {
//     $_SESSION['success'] = 'issue book deleted successfully ';
//   }
//   header("LOCATION:" . $BASE_URL . "issuebooks.php");
//   exit;
// }

// Status issuebooks
// if (isset($_GET['action']) && $_GET['action'] == 'status') {
//   $upd = updateissuebooks($conn, $_GET['id'], $_GET['status']);
//   if ($upd) {
//     if ($_GET['status'] == 1)
//       $msg = "issuebook has been returned succesfully";
//     else $msg = "issuebook has not been returned succesfully";
//     $_SESSION['success'] = $msg;
//   }
//   header("LOCATION:" . $BASE_URL . "issuebooks.php");
//   exit;
// }

include_once($dir_url . "include/header.php");
include_once($dir_url . "include/topbar.php");
include_once($dir_url . "include/sidebar.php");
?>
<main class="mt-5 pt-3" id="issuebooks">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <?php include_once($dir_url . "include/alert.php"); ?>
        <h4 class="fw-bold text-uppercase mt-4">Issue Books</h4>
        <p>Records of Issue Books</p>
      </div>
      <div>
        <a href="addissuebook.php"><button type="submit" class="btn btn-success text-uppercase me-auto">
            Issue Book
          </button>
        </a>
        <a href="?action=export"><button type="submit" class="btn btn-warning ms-3">
            Export <i class="fa-solid fa-table-cells"></i>
          </button>
        </a>
      </div>
    </div>
    <table id="example" class="table table-striped table-bordered mt-2">
      <thead>
        <tr>
          <th class="bg-dark" style="color: #ffffff;" scope="col">#</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">User Name</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">Book Name</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">Issue ID</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">issue Date</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">Expected Date</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">Status</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($issuebooks->num_rows > 0) {
          $i = 1;
          while ($row = $issuebooks->fetch_assoc()) {
        ?>
            <tr>
              <th scope="row"><?php echo $i++ ?></th>
              <td><?php echo $row['user_name'] ?> </td>
              <td><?php echo $row['book_title'] ?> </td>
              <td><?php echo $row['issue_id'] ?> </td>
              <td><?php echo date("d-m-y", strtotime($row['issue_date'])) ?> </td>
              <td><?php echo date("d-m-y", strtotime($row['return_date'])) ?> </td>
              <td>
                <?php if ($row['is_return'] == 1) {
                  echo '<span class="badge text-bg-success">Returned</span>';
                } else echo '<span class="badge text-bg-warning">Not-Returned</span>';
                ?>
              </td>

              <td>
                <?php if (!$row['is_return']) { ?>
                  <a href="<?php echo $BASE_URL ?>editissuebook.php?id=<?php echo $row['id'] ?>" class="btn btn-success btn-sm">Return</a>
                <?php } ?>

                <!-- <a href="<?php echo $BASE_URL ?>issuebooks.php?action=delete&id=<?php echo $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>

                <?php if (!$row['is_return']) { ?>
                  <a href="<?php echo $BASE_URL ?>issuebooks.php?action=status&id=<?php echo $row['id'] ?>&status=1" class="btn btn-success btn-sm">Return</a>
                <?php } ?> -->
              </td>

            </tr>
        <?php }
        } ?>
      </tbody>
    </table>
  </div>
</main>
<?php include_once($dir_url . "include/footer.php") ?>