<?php
$BASE_URL = "http://localhost/library/";
$dir_url = "C:/xampp/htdocs/library/";

include_once($dir_url . "configuration\config.php");
include_once($dir_url . "configuration\db.php");

// function to store issuebook

function StoreIssueBook($conn, $form_data)
{
  extract($form_data);
  $result = $conn->query("SELECT units FROM books WHERE id = $book_id");
  $book = $result->fetch_assoc();

  if ($book['units'] > 0) {
    $conn->query("UPDATE books SET units = units - 1 WHERE id = $book_id");

    // Determine the current year and month
    $year = date("Y");
    $month = date("m");

    // Get the latest issue_id for the current month
    $result = $conn->query("SELECT issue_id FROM issuebooks WHERE issue_id LIKE 'IB$year$month%' ORDER BY issue_id DESC LIMIT 1");
    $row = $result->fetch_assoc();

    // Determine the new auto-increment value
    if ($row) {
      $last_issue_id = $row['issue_id'];
      $last_increment = (int)substr($last_issue_id, -4);
      $new_increment = str_pad($last_increment + 1, 4, '0', STR_PAD_LEFT);
    } else {
      $new_increment = '0001';
    }

    // Create the new issue_id
    $issue_id = "IB$year$month$new_increment";

    $datetime = date("Y-m-d H:i:s");
    $sql = "INSERT INTO issuebooks(book_id, user_id, issue_id, issue_date, return_date, created_at)
            VALUES ($book_id, $user_id, '$issue_id', '$issue_date', '$return_date', '$datetime')";
    return $conn->query($sql);
  } else {
    echo "Book not available.";
  }
}

// function to get issuebook details
function getissuebookById($conn, $id)
{
  $sql = "SELECT * FROM issuebooks WHERE id =$id";
  return $conn->query($sql);
}

// function to get user
// function getissuebooks($conn)
// {
//   $sql = "select l.*, b.title as book_title, b.library_id as library_id,
//   u.name as user_name, u.u_id as u_id,
//   from issuebooks l
//   inner join books b on b.id =l.book_id
//   inner join users u on u.id =l.user_id
//   order by l.id desc;
//   ";
//   $result = $conn->query($sql);
//   return $result;
// }

function getissuebooks($conn)
{
  $sql = "SELECT l.*, 
                 b.title AS book_title, 
                 b.library_id AS library_id,
                 u.name AS user_name, 
                 u.u_id AS u_id
          FROM issuebooks l
          INNER JOIN books b ON b.id = l.book_id
          INNER JOIN users u ON u.id = l.user_id
          ORDER BY l.id DESC";

  $result = $conn->query($sql);
  return $result;
}
