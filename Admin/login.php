<?php
include "../db_connect/config.php";
session_start();
if (isset($_SESSION['clinic_email']) && isset($_SESSION['clinic_role'])) {
    switch ($_SESSION['role']) {
        case 'Admin':
            header("Location: dashboard/dashboard.php");
            exit();
        case 'Derma':
            header("Location: derma.php");
            exit();
        case 'Staff':
            header("Location: Staff.php");
            exit();
        default:
            $error_message = "Invalid role.";
            break;
    }
}
if (isset($_POST["submit"])) {
  $email = mysqli_real_escape_string($conn, trim($_POST['clinic_email']));
  $password = trim($_POST['clinic_password']);
  $role = $_POST['clinic_role'];

  $sql = mysqli_query($conn, "SELECT * FROM zp_accounts WHERE clinic_email = '$email'");
  $count = mysqli_num_rows($sql);

  if ($count > 0) {
      $fetch = mysqli_fetch_assoc($sql);
      $hashpassword = $fetch["clinic_password"];
      $accountStatus = $fetch["account_status"];

      if (password_verify($password, $hashpassword)) {
          if ($accountStatus === 'active') {
              if ($fetch["clinic_role"] == $role) {
                  $_SESSION['clinic_email'] = $email;
                  $_SESSION['clinic_role'] = $role;

                  switch ($role) {
                      case 'Admin':
                          header("Location: dashboard/dashboard.php");
                          exit();
                      case 'Derma':
                          header("Location: derma.php");
                          exit();
                      case 'Staff':
                          header("Location: Staff.php");
                          exit();
                      default:
                          $error_message = "Invalid role.";
                          break;
                  }
              } else {
                  $error_message = "You do not have access to this role.";
              }
          } else {
              // Account is deactivated
              $error_message = "Deactivated account. Please contact the administrator.";
          }
      } else {
          $error_message = "Invalid email or password, please try again.";
      }
  } else {
      $error_message = "Invalid email or password, please try again.";
  }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap/dist/css/bootstrap.min.css">
    <script src="../bootstrap/dist/js/bootstrap.min.js"></script>
    <title>Document</title>
    <style>
        .gradient-custom-2 {
      background: #fccb90;
      background: -webkit-linear-gradient(to right, #EE18D9, #9721B9,  #3E119C);
      background: linear-gradient(to right, #EE18D9, #9721B9, #3E119C);
      }

@media (min-width: 768px) {
.gradient-form {
height: 100vh !important;
}
}
@media (min-width: 769px) {
.gradient-custom-2 {
border-top-right-radius: .3rem;
border-bottom-right-radius: .3rem;
}
}
    </style>
</head>
<body>
<section class="h-100 gradient-form" style="background-color: #eee;">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-xl-10">
        <div class="card rounded-3 text-black">
          <div class="row g-0">
            <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
              <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                <img src="./image/adminpic.svg" alt="" height="400px" width="400px">
              </div>
            </div>
            <div class="col-lg-6">
              <div class="card-body p-md-5 mx-md-4">

                <div class="text-center">
                  <h4 class="mt-1 mb-5 pb-1">Hello Z Skin</h4>
                </div>

                <form method="post">
                  <p>Please login to your account</p>
                  <div class="form-outline mb-2">
                    <input type="email" id="form2Example11" class="form-control"
                      placeholder="Email address"  name="clinic_email" required/>
                    <label class="form-label" for="form2Example11">Username</label>
                  </div>

                  <div class="form-outline ">
                    <input type="password" id="form2Example22" class="form-control" name="clinic_password" required/>
                    <label class="form-label" for="form2Example22">Password</label>
                  </div>
                  <div class="form-outline ">
                    <select name="clinic_role" id="" class="form-control" required>
                      <option selected="true" disabled></option>
                      <option value="Admin">Admin</option>
                      <option value="Derma">Derma</option>
                      <option value="Staff">Staff</option>
                    </select>
                    <label class="form-label" for="form2Example11">Select you Role</label>
                  </div>
                  <?php
                    if (isset($error_message)) {
                        ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                    <?php
                    }
                    ?>
                  <a class="text-muted" href="#!">Forgot password?</a>
                  <div class="text-center pt-1 mb-5 pb-1">
                    <button type="submit" name="submit" value="Submit" class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3">Log
                      in</button>
                    
                  </div>

                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>

