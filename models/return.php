<?php
$BASE_URL = "http://localhost/library/";
$dir_url = "C:/xampp/htdocs/library/";

include_once($dir_url . "configuration\config.php");
include_once($dir_url . "configuration\db.php");

// function to store issuebook
function StoreBookrecord($conn, $form_data)
{
  extract($form_data);

  $result = $conn->query("SELECT units FROM books WHERE id = $book_id");
  $book = $result->fetch_assoc();

  $result = $conn->query("SELECT is_return FROM issuebooks WHERE id = $id");
  $issuebook = $result->fetch_assoc();

  $conn->query("UPDATE books SET units = units + 1 WHERE id = $book_id");
  $conn->query("UPDATE issuebooks SET is_return = 1 WHERE id = $id");

  $datetime = date("Y-m-d H:i:s");
  $sql = "INSERT INTO recordbooks(book_id, user_id, issue_date, return_date, bookcondition, fine, created_at)
            VALUES ($book_id, $user_id, '$issue_date', '$return_date', '$bookcondition', '$fine', '$datetime')";
  return $conn->query($sql);
}

function getbooksrecord($conn)
{
  $sql = "select l.*, b.title as book_title,b.units as book_unit, u.name as user_name
  from recordbooks l
  inner join books b on b.id =l.book_id
  inner join users u on u.id =l.user_id
  order by l.id desc;
  ";
  $result = $conn->query($sql);
  return $result;
}
