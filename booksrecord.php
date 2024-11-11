<?php
$BASE_URL = "http://localhost/library/";
$dir_url = "C:/xampp/htdocs/library/";

include_once($dir_url . "configuration/config.php");
include_once($dir_url . "configuration/db.php");
include_once($dir_url . "models/record.php");
include_once($dir_url . "include/middleware.php");

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$issuebooks = getissuebooks($conn);

if (isset($_GET['action']) && $_GET['action'] == 'export') {
  $data = $issuebooks;
  $spreadsheet = new Spreadsheet();
  $sheet = $spreadsheet->getActiveSheet();

  // Set header row
  $sheet->setCellValue('A1', 'ID');
  $sheet->setCellValue('B1', 'User Name');
  $sheet->setCellValue('C1', 'User_ID');
  $sheet->setCellValue('D1', 'Book Name');
  $sheet->setCellValue('E1', 'Book Id');
  $sheet->setCellValue('F1', 'Issue Id');
  $sheet->setCellValue('G1', 'Issue Date');

  // Populate data rows
  $rowIndex = 2;
  while ($row = $data->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowIndex, $row['id']);
    $sheet->setCellValue('B' . $rowIndex, $row['user_name']);
    $sheet->setCellValue('C' . $rowIndex, $row['u_id']);
    $sheet->setCellValue('D' . $rowIndex, $row['book_title']);
    $sheet->setCellValue('E' . $rowIndex, $row['library_id']);
    $sheet->setCellValue('F' . $rowIndex, $row['issue_id']);
    $sheet->setCellValue('G' . $rowIndex, $row['issue_date']);
    $rowIndex++;
  }

  // Set headers to download the file
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="bookrecord.xlsx"');
  header('Cache-Control: max-age=0');

  $writer = new Xlsx($spreadsheet);
  $writer->save('php://output');
  exit();
}


include_once($dir_url . "include/header.php");
include_once($dir_url . "include/topbar.php");
include_once($dir_url . "include/sidebar.php");
?>
<main class="mt-5 pt-3" id="issuebooks">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <?php include_once($dir_url . "include/alert.php"); ?>
        <h4 class="fw-bold text-uppercase mt-4">Books Records</h4>
        <p>Records of Books</p>
      </div>
    </div>

    <a href="?action=export"><button type="submit" class="btn btn-warning mb-2">
        Export <i class="fa-solid fa-table-cells"></i>
      </button>
    </a>

    <!-- filter start -->
    <div class="row">
      <div class="col-md-7">
        <form action="" method="GET">
          <div class="row">

            <div>
              <h5>Filter By Issue Date</h5>
            </div>
            <div class="col-md-4">
              <input type="date" name="date1" value="<?= isset($_GET['date1']) ? $_GET['date1'] : '' ?>" class="form-control">
            </div>


            <div class="col-md-4">
              <input type="date" name="date2" value="<?= isset($_GET['date2']) ? $_GET['date2'] : '' ?>" class="form-control">
            </div>

            <div class="col-md-4">
              <button type="submit" class="btn btn-primary">Filter</button>
              <a href="booksrecord.php" class="btn btn-danger">Reset</a>
            </div>

          </div>
        </form>
      </div>
    </div>
    <!-- filter end -->

    <table id="example" class="table table-striped table-bordered mt-2">
      <thead>
        <tr>
          <th class="bg-dark" style="color: #ffffff;" scope="col">#</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">User Name</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">User ID</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">Book Name</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">Book ID</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">Issue ID</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">Issue Date</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">Status</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // filter date start
        if (isset($_GET['date1']) && isset($_GET['date2']) && ($_GET['date1'] != '') && ($_GET['date2'] != '')) {

          function getissuebooksbyfilter($conn, $date1, $date2)
          {
            $sql = "SELECT l.*, 
                     b.title AS book_title, 
                     b.library_id AS library_id,
                     u.name AS user_name, 
                     u.u_id AS u_id
                FROM issuebooks l
                INNER JOIN books b ON b.id = l.book_id
                INNER JOIN users u ON u.id = l.user_id
                WHERE l.issue_date BETWEEN '$date1' AND '$date2'
                ORDER BY l.id DESC";

            $result = $conn->query($sql);
            return $result;
          }

          $date1 = $_GET['date1'];
          $date2 = $_GET['date2'];
          $issuebooks = getissuebooksbyfilter($conn, $date1, $date2);

          if ($issuebooks->num_rows > 0) {
            $i = 1;
            while ($row = $issuebooks->fetch_assoc()) {
        ?>
              <tr>
                <th scope="row"><?php echo $i++ ?></th>
                <td><?php echo $row['user_name'] ?> </td>
                <td><?php echo $row['u_id'] ?> </td>
                <td><?php echo $row['book_title'] ?> </td>
                <td><?php echo $row['library_id'] ?> </td>
                <td><?php echo $row['issue_id'] ?> </td>
                <td><?php echo date("d-m-y", strtotime($row['issue_date'])) ?> </td>
                <td>
                  <?php if ($row['is_return'] == 1) {
                    echo '<span class="badge text-bg-success">Returned</span>';
                  } else {
                    echo '<span class="badge text-bg-warning">Not-Returned</span>';
                  } ?>
                </td>
              </tr>
            <?php
            }
          }
        } else {
          if ($issuebooks->num_rows > 0) {
            $i = 1;
            while ($row = $issuebooks->fetch_assoc()) {
            ?>
              <tr>
                <th scope="row"><?php echo $i++ ?></th>
                <td><?php echo $row['user_name'] ?> </td>
                <td><?php echo $row['u_id'] ?> </td>
                <td><?php echo $row['book_title'] ?> </td>
                <td><?php echo $row['library_id'] ?> </td>
                <td><?php echo $row['issue_id'] ?> </td>
                <td><?php echo date("d-m-y", strtotime($row['issue_date'])) ?> </td>
                <td>
                  <?php if ($row['is_return'] == 1) {
                    echo '<span class="badge text-bg-success">Returned</span>';
                  } else {
                    echo '<span class="badge text-bg-warning">Not-Returned</span>';
                  } ?>
                </td>
              </tr>
        <?php
            }
          }
        }
        ?>
      </tbody>
    </table>
  </div>
</main>
<?php include_once($dir_url . "include/footer.php") ?>