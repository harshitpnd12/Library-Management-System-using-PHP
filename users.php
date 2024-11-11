<?php
$BASE_URL = "http://localhost/library/";
$dir_url = "C:/xampp/htdocs/library/";

include_once($dir_url . "configuration\config.php");
// include_once($dir_url . "include\middleware.php");
include_once($dir_url . "configuration\db.php");
include_once($dir_url . "\models\user.php");
include_once($dir_url . "include\middleware.php");


require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$users = getusers($conn);

if (isset($_GET['action']) && $_GET['action'] == 'export') {
  $data = $users;
  $spreadsheet = new Spreadsheet();
  $sheet = $spreadsheet->getActiveSheet();

  $sheet->setCellValue('A1', 'ID');
  $sheet->setCellValue('B1', 'Name');
  $sheet->setCellValue('C1', 'Email');
  $sheet->setCellValue('D1', 'User ID');
  $sheet->setCellValue('E1', 'Phone Number');
  $sheet->setCellValue('F1', 'DOB');


  // Populate data rows
  $rowIndex = 2;
  while ($row = $data->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowIndex, $row['id']);
    $sheet->setCellValue('B' . $rowIndex, $row['name']);
    $sheet->setCellValue('C' . $rowIndex, $row['email']);
    $sheet->setCellValue('D' . $rowIndex, $row['u_id']);
    $sheet->setCellValue('E' . $rowIndex, $row['phone']);
    $sheet->setCellValue('F' . $rowIndex, $row['dob']);
    $rowIndex++;
  }

  // Set headers to download the file
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="users.xlsx"');
  header('Cache-Control: max-age=0');

  $writer = new Xlsx($spreadsheet);
  $writer->save('php://output');
  exit();
}

// delete users
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
  $del = deleteuser($conn, $_GET['id']);
  if ($del) {
    $_SESSION['success'] = 'user deleted successfully ';
  }
  header("LOCATION:" . $BASE_URL . "users.php");
  exit;
}

// Status users
if (isset($_GET['action']) && $_GET['action'] == 'status') {
  $upd = updateusers($conn, $_GET['id'], $_GET['status']);
  if ($upd) {
    if ($_GET['status'] == 1)
      $msg = "user has been successfully activated";
    else $msg = "user has been successfully deactivated";
    $_SESSION['success'] = $msg;
  }
  header("LOCATION:" . $BASE_URL . "users.php");
  exit;
}

include_once($dir_url . "include/header.php");
include_once($dir_url . "include/topbar.php");
include_once($dir_url . "include/sidebar.php");
?>
<main class="mt-5 pt-3" id="users">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <?php include_once($dir_url . "include/alert.php"); ?>
        <h4 class="fw-bold text-uppercase mt-4">Users</h4>
        <p>Records of Users</p>
      </div>
      <div>
        <a href="adduser.php"><button type="submit" class="btn btn-success text-uppercase me-auto">
            Add user
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
          <th class="bg-dark" style="color: #ffffff;" scope="col">Name</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">Email</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">User ID</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">Phone number</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">Dob</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">Status</th>
          <th class="bg-dark" style="color: #ffffff;" scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($users->num_rows > 0) {
          $i = 1;
          while ($row = $users->fetch_assoc()) {
        ?>
            <tr>
              <th scope="row"><?php echo $i++ ?></th>
              <td><?php echo $row['name'] ?> </td>
              <td><?php echo $row['email'] ?> </td>
              <td><?php echo $row['u_id'] ?> </td>
              <td><?php echo $row['phone'] ?> </td>
              <td><?php echo date("d-m-y", strtotime($row['dob'])) ?> </td>
              <td>
                <?php if ($row['status'] == 1) {
                  echo '<span class="badge text-bg-success">Active</span>';
                } else echo '<span class="badge text-bg-danger">Deactive</span>';
                ?>
              </td>
              <td>
                <a href="<?php echo $BASE_URL ?>edituser.php?id=<?php echo $row['id'] ?>" class="btn btn-primary btn-sm">Edit</a>

                <a href="addissuebook.php" class="btn btn-secondary btn-sm">
                  Issue
                </a>

                <!-- <a href="<?php echo $BASE_URL ?>users.php?action=delete&id=<?php echo $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a> -->

                <!-- <?php if ($row['status'] == 1) { ?>
                  <a href="<?php echo $BASE_URL ?>users.php?action=status&id=<?php echo $row['id'] ?>&status=0" class="btn btn-warning btn-sm">Deactive</a>
                <?php } ?> -->

                <?php if ($row['status'] == 0) { ?>
                  <a href="<?php echo $BASE_URL ?>users.php?action=status&id=<?php echo $row['id'] ?>&status=1" class="btn btn-success btn-sm">Active</a>
                <?php } ?>
              </td>
            </tr>
        <?php }
        } ?>
      </tbody>
    </table>
  </div>
</main>
<?php include_once($dir_url . "include/footer.php") ?>