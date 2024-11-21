<?php 
require_once('app/helpers/authentification.php');

//$login_password = sha1(md5(mysqli_real_escape_string($mysqli, 'testtest')));
//echo $login_password;exit;
//2a63eca1020db4020c46be9b5dc8de4467d00912
//INSERT INTO `users` (`UserId`, `UserFirstName`, `UserLastName`, `UserEmail`, `UserPwd`, `UserAccessLevel`, `UserPwdResetCode`) VALUES (1, 'Marc', 'Dupont', 'marc.dupont@gmail.com', '2a63eca1020db4020c46be9b5dc8de4467d00912', NULL, NULL);
//UserEmail
//UserPwd
?>

<html>
	<head>
		<title>Strongly Typed by HTML5 UP</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	</head>
    <body class="homepage is-preload">
        <div id="page-wrapper">
        <section id="features">
            <div class="container">
				<header>
                    <div class="alert alert-success alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>
                            <?php 
                                if (isset($_SESSION['success'])) {
                                 echo $_SESSION['success']; 
                                unset($_SESSION['success']);
                                }
                            ?>
                        </strong> 
                    </div>
                    <div class="alert alert-danger alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>
                            <?php 
                                if (isset($_SESSION['err'])) {
                                    echo $_SESSION['err'];
                                    unset($_SESSION['err']);
                                }
                            ?>
                        </strong>
                    </div>
				</header>
            </div>
        </section>
        <section id="footer">
            <div class="container">
                <header>
                    <h2><strong>Sign In?</strong></h2>
                </header>
                <div class="row">
                    <div class="col-6 col-12-medium">
                        <section>
                            <form method="POST" class="bg-light p-5 contact-form">
                                     <div class="form-group">
                                           Adresse e-mail 
                                        <input id="login-email" name="login_email" required type="email" />
                                     </div>

                                      <div>
                                     <div class="form-group">
                                            Mot de passe
                                            <a href="reset_password.php">Mot de passe oubli&eacute;?</a>
                                        </div>
                                        <input id="login-password" required name="login_password" type="password" />
                                    </div>

                                     <div class="form-group">
                                             <button class="btn btn-primary py-3 px-5" type="submit" name="Login">
                                                            Login
                                             </button>
                                     </div>
                             </form>
                        </section>
                    </div>
                </div>
            </div>
        </section>
    
        </div>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery.dropotron.min.js"></script>
    <script src="assets/js/browser.min.js"></script>
    <script src="assets/js/breakpoints.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>
    </body>
</html>