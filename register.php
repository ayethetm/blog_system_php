<?php

session_start();
require 'config/config.php';
require 'config/common.php';

if ($_POST) {

  if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password']) < 4) 
  {
    if (empty($_POST['name']))
    {
      $nameError = 'Username cannot be null';
    }
    if (empty($_POST['email']))
    {
      $emailError = 'Email cannot be null';
    }
    if (empty($_POST['password']))
    {
      $passwordError = 'Password cannot be null';
    }
    if (strlen($_POST['password']) < 4)
    {
      $passwordError = 'Password should be at least 4 characters.';
    }
    
  }
  else{
    //ACCEPT POST REQUEST DATA
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT); //pwd hash

    //FIRST, check EMAIL exists or not in table
    $statement = $pdo->prepare("SELECT * FROM users WHERE email=:email");
    //bind value
    $statement->bindValue(':email',$email);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if ($result) {
      echo '<script>alert("Email address is already taken");</script>';
    }
    else
    {
      $stmt = $pdo->prepare("INSERT INTO users(name,email,password) VALUES(:name,:email,:password)");
      $result = $stmt->execute(
          array(':name' => $name,
               ':email' => $email,
                ':password' => $password
                )
      );
  
      if($result)
      {
          echo '<script>alert("Successfully created a user account.");window.location.href="login.php";</script>';
      }
    }
  }
    

}


?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blog | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#">Login</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Register as a new membership</p>

      <form action="register.php" method="post">
      <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
      <p style="color:red;"><?php echo empty($nameError)? '' : '*'.$nameError ?></p>
      <div class="input-group mb-3">
          <input type="text" name="name" class="form-control" placeholder="Name">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <p style="color:red;"><?php echo empty($emailError)? '' : '*'.$emailError ?></p>
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <p style="color:red;"><?php echo empty($passwordError)? '' : '*'.$passwordError ?></p>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mb-0">
        <a href="login.php" class="text-center">I have an account already</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="./js/adminlte.min.js"></script>

</body>
</html>
