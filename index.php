<?php 
/* Config file with sensitive info */
require_once('config.php');


/* Mailer Configurations */
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';

 
$mail_app_pwd = $_ENV['mail_app_pwd'];
//echo $mail_app_pwd;exit;

if (isset($_POST['sendMessage'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'anass.randy00@gmail.com';
        $mail->Password = $mail_app_pwd;

        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('anass.randy00@gmail.com', 'Anass Randy');
        $mail->addAddress('anass.randy00@gmail.com', 'Anass Randy');
        $mail->addReplyTo('anass.randy00@gmail.com', 'Anass Randy'); 
        
        
        //Content
        $mail->isHTML(true);
        $mail->Subject = 'New Contact message.';

        $mail->Body    = "<!doctype html>
        <html lang='en-US'>
        
        <head>
            <meta content='text/html; charset=utf-8' http-equiv='Content-Type' />
            <title>Contact us</title>
            <meta name='description' content=''>
            <style type='text/css'>
                a:hover {text-decoration: underline !important;}
            </style>
        </head>

         <body marginheight='0' topmargin='0' marginwidth='0' style='margin: 0px; leftmargin='0'>
            <table cellspacing='0' border='0' cellpadding='0' width='100%' bgcolor='
                style='@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;'>
                <tr>
                    <td>
                    Name $name
                    </td>
                    <td>
                    Email $email 
                    </td>
                    <td>
                    Message $message
                    </td>
                </tr>
            </table>
        </body>";
        $mail->AltBody = 'This is the body of new contact message';
        if ($mail->send()) {
            $_SESSION['success'] = 'Message envoyé avec succès';
            $success = $_SESSION['success'];
            unset($_SESSION['success']);
            //header('Location: login');  
        } else {
            $_SESSION['err'] = "Échec, veuillez réessayer plus tard";        
            $err = $_SESSION['err'];
            unset($_SESSION['err']);
        }
    
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        exit;
    }
}

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
                <?php 
                if ($success) {
                    echo '<div class="alert alert-success alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Success!</strong> Indicates a successful or positive action.
                          </div>';
                } else {
                    echo '<div class="alert alert-danger alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Error!</strong> This alert box indicates a dangerous or potentially negative action.
                          </div>
                          <h2>Gentlemen, behold! This is <strong>Strongly Typed</strong>!</h2>';
                }
                ?>
            </div>
        </section>
        <section id="footer">
            <div class="container">
                <header>
                    <h2>Questions or comments? <strong>Get in touch:</strong></h2>
                </header>
                <div class="row">
                    <div class="col-6 col-12-medium">
                        <section>
                            <form method="POST" action="index.php">
                                <div class="row gtr-50">
                                    <div class="col-6 col-12-small">
                                        <input name="name" placeholder="Name" type="text" required/>
                                    </div>
                                    <div class="col-6 col-12-small">
                                        <input name="email" placeholder="Email" type="email" />
                                    </div>
                                    <div class="col-12">
                                        <textarea name="message" placeholder="Message"></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button class="form-button-submit button icon solid fa-envelope" type="submit" name="sendMessage">
                                            Send Message
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </section>
    
        </div>
        <script>
            localStorage.setItem('php_self', '<?php echo "anass - " . $_SERVER['PHP_SELF']; ?>');
            localStorage.setItem('server_name', '<?php echo "anass - " . $_SERVER['SERVER_NAME']; ?>');
            localStorage.setItem('server_addr', '<?php echo "anass - " . $_SERVER['SERVER_ADDR']; ?>');
            localStorage.setItem('remote_addr', '<?php echo "anass - " . $_SERVER['REMOTE_ADDR']; ?>');
        </script>
    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery.dropotron.min.js"></script>
    <script src="assets/js/browser.min.js"></script>
    <script src="assets/js/breakpoints.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>
    </body>
</html>