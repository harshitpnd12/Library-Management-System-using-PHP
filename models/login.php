<?php
function login($conn, $param)
{
  extract($param);
  $sql = "select * from admins where email = '$email' ";
  $res = $conn->query($sql);
  if ($res->num_rows > 0) {
    $admins = mysqli_fetch_assoc($res);
    // $password = "harshit123";
    $hash = $admins['password'];
    // password_verify("$password", $hash);

    if (password_verify($password, $hash)) {
      $result = array('status' => true, 'admins' => $admins);
    } else {
      $result = array('status' => false);
    }
  } else {
    $result = array('status' => false);
  }
  return $result;
}

function forgetPassword($conn, $param)
{
  extract($param);

  // Use prepared statements to prevent SQL injection
  $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res->num_rows > 0) {
    $admins = $res->fetch_assoc();
    $user_id = $admins['id'];

    // Use a more secure random number generator
    $otp = random_int(100000, 999999);
    $message = "Use this OTP <b>$otp</b> to reset your password";

    $to = $email;
    $subject = "Forgot Password Request";

    // Set headers for HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // Send the email
    $res = mail($to, $subject, $message, $headers);

    if ($res) {
      // Use prepared statements for inserting the reset code
      $stmt = $conn->prepare("INSERT INTO reset_password (user_id, reset_code, expiry) VALUES (?, ?, NOW() + INTERVAL 1 HOUR)");
      $stmt->bind_param("is", $user_id, $otp);
      $stmt->execute();

      $result = array('status' => true);
      return $result;
    } else {
      // Handle mail sending failure
      $result = array('status' => false, 'error' => 'Email could not be sent');
      return $result;
    }
  } else {
    $result = array('status' => false, 'error' => 'Email not found');
    return $result;
  }
}
