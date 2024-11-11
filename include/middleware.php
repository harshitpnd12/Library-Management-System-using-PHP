<?php
if (isset($_SESSION['is_admins_login'])) {
  return true;
} else {
  header("LOCATION:" . $BASE_URL . "index.php");
  exit;
}
