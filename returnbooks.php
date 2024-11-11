<?php
$BASE_URL = "http://localhost/library/";
$dir_url = "C:/xampp/htdocs/library/";

include_once($dir_url . "configuration/config.php");
include_once($dir_url . "configuration/db.php");
include_once($dir_url . "/models/return.php");
include_once($dir_url . "include/middleware.php");


require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$issuebooks = getbooksrecord($conn);


// Export books to Excel
if (isset($_GET['action']) && $_GET['action'] == 'export') {
  $data = $issuebooks;
  $spreadsheet = new Spreadsheet();
  $sheet = $spreadsheet->getActiveSheet();

  // Set header row
  $sheet->setCellValue('A1', 'ID');
  $sheet->setCellValue('B1', 'User Name');
  $sheet->setCellValue('C1', 'Book Name');
  $sheet->setCellValue('D1', 'Issue Date');
  $sheet->setCellValue('E1', 'Return Date');
  $sheet->setCellValue('F1', 'Book Condition');
  $sheet->setCellValue('G1', 'Fine');
  $sheet->setCellValue('H1', 'Units');

  // Populate data rows
  $rowIndex = 2;
  while ($row = $data->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowIndex, $row['id']);
    $sheet->setCellValue('B' . $rowIndex, $row['user_name']);
    $sheet->setCellValue('C' . $rowIndex, $row['book_title']);
    $sheet->setCellValue('D' . $rowIndex, $row['issue_date']);
    $sheet->setCellValue('E' . $rowIndex, $row['return_date']);
    $sheet->setCellValue('F' . $rowIndex, $row['bookcondition']);
    $sheet->setCellValue('G' . $rowIndex, $row['fine']);
    $sheet->setCellValue('H' . $rowIndex, $row['book_unit']);
    $rowIndex++;
  }

  // Set headers to download the file
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="returnbooks.xlsx"');
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
        <h4 class="fw-bold text-uppercase mt-4">Returns Books</h4>
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
              <h5>Filter By Return Date</h5>
            </div>

            <div class="col-md-4">
              <input type="date" name="date1" value="<?= isset($_GET['date1']) ? $_GET['date1'] : '' ?>" class="form-control">
            </div>


            <div class="col-md-4">
              <input type="date" name="date2" value="<?= isset($_GET['date2']) ? $_GET['date2'] : '' ?>" class="form-control">
            </div>

            <div class="col-md-4">
              <button type="submit" class="btn btn-primary">Filter</button>
              <a href="returnbooks.php" class="btn btn-danger">Reset</a>
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
          <th class="bg-dark" style="color: #ffffff;" scope="col">Book Name</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">Issue Date</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">Return Date</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">Book Condition</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">Fine</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">Status</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">Units</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (isset($_GET['date1']) && isset($_GET['date2']) && !empty($_GET['date1']) && !empty($_GET['date2'])) {
          $date1 = $_GET['date1'];
          $date2 = $_GET['date2'];

          // Debugging: Print the date inputs
          echo "<script>console.log('Date1: $date1, Date2: $date2');</script>";

          function getbooksrecordbyfilter($conn, $date1, $date2)
          {
            $sql = "SELECT l.*, b.title AS book_title, b.units AS book_unit, u.name AS user_name
                    FROM recordbooks l
                    INNER JOIN books b ON b.id = l.book_id
                    INNER JOIN users u ON u.id = l.user_id
                    WHERE l.return_date BETWEEN '$date1' AND '$date2'
                    ORDER BY l.id DESC;";

            $result = $conn->query($sql);
            return $result;
          }

          $issuebooks = getbooksrecordbyfilter($conn, $date1, $date2);

          if ($issuebooks->num_rows > 0) {
            $i = 1;
            while ($row = $issuebooks->fetch_assoc()) {
        ?>
              <tr>
                <th scope="row"><?php echo $i++ ?></th>
                <td><?php echo $row['user_name'] ?> </td>
                <td><?php echo $row['book_title'] ?> </td>
                <td><?php echo date("d-m-y", strtotime($row['issue_date'])) ?> </td>
                <td><?php echo date("d-m-y", strtotime($row['return_date'])) ?> </td>
                <td><?php echo $row['bookcondition'] ?> </td>
                <td><?php echo $row['fine'] ?> </td>
                <td>
                  <?php if ($row['is_return'] == 1) {
                    echo '<span class="badge text-bg-success">Returned</span>';
                  } else echo '<span class="badge text-bg-warning">Not-Returned</span>';
                  ?>
                </td>
                <td><?php echo $row['book_unit'] ?> </td>
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
                <td><?php echo $row['book_title'] ?> </td>
                <td><?php echo date("d-m-y", strtotime($row['issue_date'])) ?> </td>
                <td><?php echo date("d-m-y", strtotime($row['return_date'])) ?> </td>
                <td><?php echo $row['bookcondition'] ?> </td>
                <td><?php echo $row['fine'] ?> </td>
                <td>
                  <?php if ($row['is_return'] == 1) {
                    echo '<span class="badge text-bg-success">Returned</span>';
                  } else echo '<span class="badge text-bg-warning">Not-Returned</span>';
                  ?>
                </td>
                <td><?php echo $row['book_unit'] ?> </td>
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