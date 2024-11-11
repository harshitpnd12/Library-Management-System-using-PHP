<?php

$BASE_URL = "http://localhost/library/";
$dir_url = "C:/xampp/htdocs/library/";

include_once($dir_url . "configuration\config.php");
include_once($dir_url . "configuration\db.php");

// function to store book

function generateLibraryId($conn)
{
  $datetime = date("Y-m-d H:i:s");
  $prefix = "LB";
  $year = date("Y");
  $month = date("m");
  $current_date = $year . $month;

  // Query to get the latest library_id for the current month and year
  $sql = "SELECT library_id FROM books WHERE library_id LIKE '$prefix$current_date%' ORDER BY library_id DESC LIMIT 1";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $last_id = $row['library_id'];
    $last_number = intval(substr($last_id, -4));
    $new_number = str_pad($last_number + 1, 4, '0', STR_PAD_LEFT);
  } else {
    // No entries for the current month, start with 0001
    $new_number = '0001';
  }

  return $prefix . $current_date . $new_number;
}


function storeBook($conn, $form_data)
{
  extract($form_data);

  $title = addslashes($title);
  $author = addslashes($author);
  $isbn = addslashes($isbn);
  $publisher = addslashes($publisher);
  $edition = addslashes($edition);
  $units = addslashes($units);

  // Generate library_id
  $library_id = generateLibraryId($conn);

  $datetime = date("Y-m-d H:i:s");
  $sql = "INSERT INTO books(title, author, isbn, library_id, publisher, edition, units, created_at)
            VALUES ('$title', '$author', '$isbn', '$library_id', '$publisher', '$edition', $units, '$datetime')";
  $result['success'] = $conn->query($sql);
}



// function to get book
function getbooks($conn)
{
  $sql = "select * from books order by id desc";
  return $conn->query($sql);
}

// function to get book details
function getBookById($conn, $id)
{
  $sql = "select * from books where id =$id ";
  return $conn->query($sql);
}

// delete book
function deleteBook($conn, $id)
{
  $sql = "delete from books where id =$id";
  return $conn->query($sql);
}

// Update book
function updateBooks($conn, $id, $status)
{
  $sql = "update books set status = '$status' where id =$id";
  return $conn->query($sql);
}

// function to update book
function updateBook($conn, $form_data)
{
  extract($form_data);
  $datetime = date("Y-m-d H:i:s");

  $sql = "UPDATE books SET 
  title='$title', 
  author='$author', 
  isbn='$isbn',
  publisher='$publisher',
  edition='$edition',
  units='$units',
  updated_at='$datetime'
  where id=$id;
  ";
  return $conn->query($sql);
}


// logs
function logAction($conn, $action, $book_id, $user_id, $details = '')
{
  $stmt = $conn->prepare("INSERT INTO logs (action, book_id, user_id, details) VALUES (?, ?, ?, ?)");
  $stmt->bind_param('siis', $action, $book_id, $user_id, $details);
  $stmt->execute();
  $stmt->close();
}
