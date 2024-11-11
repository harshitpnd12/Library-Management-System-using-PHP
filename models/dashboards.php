<?php

$BASE_URL = "http://localhost/library/";
$dir_url = "C:/xampp/htdocs/library/";

include_once($dir_url . "configuration\config.php");
include_once($dir_url . "configuration\db.php");



// function to get book
function getcounts($conn)
{
  $counts = array(
    'total_books' => 0,
    'total_users' => 0,
    'total_issuebooks' => 0,
  );
  $sql = "select count(id) as total_books from books";
  $res = $conn->query($sql);
  if ($res->num_rows > 0) {
    $books = mysqli_fetch_assoc($res);
    $counts['total_books'] = $books['total_books'];
  }


  $sql = "select count(id) as total_users from users";
  $res = $conn->query($sql);
  if ($res->num_rows > 0) {
    $users = mysqli_fetch_assoc($res);
    $counts['total_users'] = $users['total_users'];
  }


  $sql = "select count(id) as total_issuebooks from issuebooks";
  $res = $conn->query($sql);
  if ($res->num_rows > 0) {
    $issuebooks = mysqli_fetch_assoc($res);
    $counts['total_issuebooks'] = $issuebooks['total_issuebooks'];
  }
  return $counts;
}



// tab data
function getTabData($conn)
{
  $tabs = array(
    'users' => array(),
    'issuebooks' => array(),
  );

  $sql = "select * from users order by id desc limit 5";
  $res = $conn->query($sql);
  if ($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
      $tabs['users'][] = $row;
    }
  }

  $sql = "select l.*, b.title as book_title, u.name as user_name
  from issuebooks l
  inner join books b on b.id = l.book_id
  inner join users u on u.id = l.user_id
  order by id desc limit 5";
  $res = $conn->query($sql);
  if ($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
      $tabs['issuebooks'][] = $row;
    }
  }

  return $tabs;
}
