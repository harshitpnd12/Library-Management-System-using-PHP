<?php
$BASE_URL = "http://localhost/library/";
$dir_url = "C:/xampp/htdocs/library/";

include_once($dir_url . "configuration\config.php");
include_once($dir_url . "configuration\db.php");

function storeuser($conn, $form_data)
{
  extract($form_data);
  $datetime = date("Y-m-d H:i:s");
  $currentYear = date("Y");
  $currentMonth = date("m");

  // Fetch the latest user ID for the current year and month
  $sql = "SELECT u_id FROM users WHERE u_id LIKE 'UID{$currentYear}{$currentMonth}%' ORDER BY u_id DESC LIMIT 1";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $lastUID = $row['u_id'];
    // Extract the increment part and increment it by 1
    $increment = (int)substr($lastUID, -4) + 1;
  } else {
    // If no user ID exists for the current month, start from 1
    $increment = 1;
  }

  // Format the new user ID
  $newUID = sprintf("UID%s%s%04d", $currentYear, $currentMonth, $increment);

  // Prepare the data for insertion
  $name = addslashes($name);
  $email = addslashes($email);
  $u_id = $newUID;
  $phone = addslashes($phone);

  // Insert the data into the database
  $sql = "INSERT INTO users (name, email, u_id, phone, dob, created_at) VALUES ('$name', '$email', '$u_id', '$phone', '$dob', '$datetime')";
  return $conn->query($sql);
}


// function to get user
function getusers($conn)
{
  $sql = "select * from users order by id desc";
  return $conn->query($sql);
}

// function to get user details
function getuserById($conn, $id)
{
  $sql = "select * from users where id =$id ";
  return $conn->query($sql);
}

// delete user
function deleteuser($conn, $id)
{
  $sql = "delete from users where id =$id";
  return $conn->query($sql);
}

// Update user status
function updateusers($conn, $id, $status)
{
  $sql = "update users set status = '$status' where id =$id";
  return $conn->query($sql);
}

// function to update user
function updateuser($conn, $form_data)
{
  extract($form_data);
  $datetime = date("Y-m-d H:i:s");

  $sql = "UPDATE users SET 
  name='$name', 
  email='$email', 
  phone='$phone',
  dob='$dob',
  updated_at='$datetime'
  where id=$id;
  ";
  return $conn->query($sql);
}

function getUser($conn)
{
  $sql = "select id, name from users";
  $result = $conn->query($sql);
  return $result;
}

function getBook($conn)
{
  $sql = "select id, title from books";
  $result = $conn->query($sql);
  return $result;
}

// logs
function logActionuser($conn, $action, $book_id, $user_id, $details = '')
{
  $stmt = $conn->prepare("INSERT INTO logs (action, book_id, user_id, details) VALUES (?, ?, ?, ?)");
  $stmt->bind_param('siis', $action, $book_id, $user_id, $details);
  $stmt->execute();
  $stmt->close();
}
