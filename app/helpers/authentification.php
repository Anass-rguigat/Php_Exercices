<?php 

require_once('config.php');
require_once('connectDB.php');
/* Sign In */
if (isset($_POST['Login'])) {
    $login_email = mysqli_real_escape_string($mysqli, $_POST['login_email']);
    $login_password = sha1(md5(mysqli_real_escape_string($mysqli, $_POST['login_password'])));

    /* Sign In */
    $loginSql = "SELECT * FROM users WHERE UserEmail = '{$login_email}' AND UserPwd = '{$login_password}'";
    $res = mysqli_query($mysqli, $loginSql);
    
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $_SESSION['user_id'] = $row['user_id'];
        //$_SESSION['user_dpic'] = $row['user_dpic'];
        $_SESSION['user_access_level'] = $row['user_access_level'];
        $_SESSION['success'] = 'Login successful';
        $user_access_level = $row['user_access_level'];
        //Inserer ligne dans la table session
        //INSERT INTO `sessions` (`SessionId`, `UserId`, `UserIpAddress`, `SessionToken`, `ConnectedAt`, `ExpiredAt`) VALUES (1, 1, '', '', '2024-11-01 00:00:00', '2024-11-21 00:00:00');
        $ttl = $_ENV['ttl'];
        switch ($user_access_level) {
            case 'level1':
                header('Location: level1.php');
                break;
            case 'level2':
                header('Location: level2.php');
                break;
            
            default:
                header('Location: level0.php');
                break;
        }
        
    } else if (mysqli_num_rows($res) == 0) {
            $err = "Invalid login credentials";
            echo $err;
            exit;
    } //use token to mix email/password
    /*else {
           $err = "Invalid login credentials";
           echo $err;
           exit;
    }*/
}

/* Sign up */
if (isset($_POST['Register'])) {
    $userFirstName = mysqli_real_escape_string($mysqli, $_POST['userFirstName']);
    $userLastName = mysqli_real_escape_string($mysqli, $_POST['userLastName']);
    $userEmail = mysqli_real_escape_string($mysqli, $_POST['userEmail']);
    //$userPhoneNumber = mysqli_real_escape_string($mysqli, $_POST['client_phone_number']);
    $userPassword = sha1(md5(mysqli_real_escape_string($mysqli, $_POST['new_password'])));
    $confirm_password = sha1(md5(mysqli_real_escape_string($mysqli, $_POST['confirm_password'])));
    $userDateJoined = mysqli_real_escape_string($mysqli, date('Y-m-d H:i:s'));
    $userAccessLevel = 'level0';
    
    /* Check If Passwords Match */
    if ($password != $confirm_password) {
        $err = "Passwords Do Not Match";
    } else {
        /* Avoid Redundancy */        
        $duplication_sql = "SELECT * FROM  users WHERE UserEmail = '{$client_email}'";
        $res = mysqli_query($mysqli, $duplication_sql);
        if (mysqli_num_rows($res) > 0) {
            $err = "Un compte avec cet email existe d�j�";
        } else {
            /* Persist */
            $register_sql = "INSERT INTO users (`UserFirstName`, `UserLastName`, `UserEmail`, `UserPwd`, `UserAccessLevel`)
                VALUES('{$userFirstName}', '{$userLarstName}', '{$userEmail}', '{$userPassword}', '{$userAccessLevel}')";

            /* Mailer */
            //deplacer le fichier mailer ici:
            //include('../app/mailers/sign_up_mailer.php');
            if (mysqli_query($mysqli, $register_sql)) {
                $_SESSION['success'] = 'Compte cr�� avec succ�s';
                header('Location: login.php');                
            } else {
                $err = "�chec, veuillez r�essayer plus tard";
            }
        }
    }
}
/* Reset Password Step 1 */
if (isset($_POST['Reset_Password_Step_1'])) {
    $login_email = mysqli_real_escape_string($mysqli, $_POST['login_email']);
    $reset_url = $url . $tk;
    /* Check If Email Exists */
    $sql = "SELECT * FROM users WHERE UserEmail = '{$login_email}'";
    $res = mysqli_query($mysqli, $sql);
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        /* Get User Email Where Reset Link Will Be Sent */
        $email = $row['client_email'];

        /* Persist reset code  */
        $reset_code_sql = "UPDATE users SET UserPwdResetCode = '{$tk}',
        client_password = '' WHERE client_email = '{$login_email}'";
        if (mysqli_query($mysqli, $reset_code_sql)) {
            /* Mailer */
            include('../app/mailers/reset_password.php');
            if ($mail->send()) {
                $success = 'Please check your email for a password reset link';
            } else {
                $info = "We could not send you a password reset link, please try again";
            }
        } else {
            $err = "Failed, please try again later";
        }
    } else if (mysqli_num_rows($res) == 0) {
        /* Reset Staff Password*/
        $sql = "SELECT * FROM users WHERE user_phone_number = '{$login_email}' || user_email = '{$login_email}'";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            /* Get User Email Where Reset Link Will Be Sent */
            $email = $row['user_email'];

            /* Persist reset code  */
            $reset_code_sql = "UPDATE users SET user_password_reset_code = '{$tk}',
            user_password = '' WHERE user_phone_number = '{$login_email}' || user_email = '{$login_email}'";
            
            if (mysqli_query($mysqli, $reset_code_sql)) {
                /* Mailer */
                include('../app/mailers/reset_password.php');
                if ($mail->send()) {
                    $success = 'V�rifier votre boite mail';
                } else {
                    $info = "Nous n'avons pas pu vous envoyer un lien de r�initialisation de mot de passe, veuillez r�essayer";
                }
            } else {     
                $err = "�chec, veuillez r�essayer plus tard";
            }
        } else {
            $err = "Aucun compte avec cet e-mail ou ce num�ro de t�l�phone n'existe";
        }
    } else {
        $err = "Aucun compte avec cet e-mail n'existe";
    }
}

/* Reset Password Step 2 */
if (isset($_POST['Reset_Password_Step_2'])) {
    $new_password = sha1(md5(mysqli_real_escape_string($mysqli, $_POST['new_password'])));
    $confirm_password = sha1(md5(mysqli_real_escape_string($mysqli, $_POST['confirm_password'])));
    $token = mysqli_real_escape_string($mysqli, $_GET['token']);

    if (!empty($token)) {
        /* Check if passwords match */
        if ($new_password != $confirm_password) {
            $err = "Passwords Do Not Match";
        } else {
            /* Check Whos account has this token */
            $sql = "SELECT * FROM users WHERE UserPwdResetCode = '{$token}'";
            $res = mysqli_query($mysqli, $sql);
            if (mysqli_num_rows($res) > 0) {
                /* Update Password */
                $update_sql = "UPDATE users SET UserPwd = '{$new_password}',
            client_password_reset_code = '' WHERE UserPwdResetCode = '{$token}'";
                if (mysqli_query($mysqli, $update_sql)) {
                    $_SESSION['success'] = "Password reset successful";
                    header('Location: login');
                    exit;
                } else {
                    $err = "Failed, please try again later";
                }
            } else if (mysqli_num_rows($res) == 0) {
                /* Check Whos account has this token */
                $sql = "SELECT * FROM users WHERE UserPwdResetCode = '{$token}'";
                $res = mysqli_query($mysqli, $sql);
                if (mysqli_num_rows($res) > 0) {
                    /* Update Password */
                    $update_sql = "UPDATE users SET user_password = '{$new_password}',
                    UserPwdResetCode = '' WHERE UserPwdResetCode = '{$token}'";
                    if (mysqli_query($mysqli, $update_sql)) {
                        $_SESSION['success'] = "Password reset successful";
                        header('Location: login');
                        exit;
                    } else {
                        $err = "Failed, please try again later";
                    }
                } else {
                    $_SESSION['err'] = "Invalid password reset token";
                    header('Location: reset_password');
                    exit;
                }
            } else {
                $_SESSION['err'] = "Invalid password reset token";
                header('Location: reset_password');
                exit;
            }
        }
    } else {
        $_SESSION['err'] = "Invalid password reset token";
        header('Location: reset_password');
        exit;
    }
}