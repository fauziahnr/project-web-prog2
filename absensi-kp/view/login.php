<!DOCTYPE html>
<!-- saved from url=(0040)http://getbootstrap.com/examples/signin/ -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="http://getbootstrap.com/favicon.ico">
    <title>
      Login
    </title>
    <!-- Bootstrap core CSS -->
    <link href="./lib/bootstrap.min.css" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="./lib/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="./lib/signin.css" rel="stylesheet">
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="./lib/ie-emulation-modes-warning.js"></script>
  </head>

  <body>
    <div class="container">
      <form class="form-signin" method="post" action="model/proses.php">
        <h2 class="form-signin-heading" align="center"><b>A</b>BSENSI</h2>
        <br>
        <?php 
            if (isset($_GET['log'])) {
                if ($_GET['log']==2) {
                    echo "<div class='alert alert-danger'><strong>Periksa kembali email & katasandi Anda!</strong></div>";
                }
            }
         ?>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="text" name="username" id="inputEmail" class="form-control" placeholder="Username" required="" autofocus="">
        <br>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="pwd" id="inputPassword" class="form-control" placeholder="Password" required="">
        <br>
        <input class="btn btn-lg btn-primary btn-block" type="submit" name="login" value="Sign in">
      </form>
    </div> <!-- /container -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="./lib/ie10-viewport-bug-workaround.js"></script>
</body>
</html>