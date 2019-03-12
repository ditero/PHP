<?php

if (isset($_POST['signup-submit'])) {
    # Database
    require 'dbh.inc.php';

    $username = $_POST['uid'];
    $email = $_POST['mail'];
    $password = $_POST['pwd'];
    $passwordRepeat = $_POST['pwd-repeat'];

    if (empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) {
        # code...
        header("Location: ../signup.php?error=emptyfields&uid=".$username."&mail="."$email");
        exit();
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)&& !preg_match("/^[a-zA-Z0-9]*$/", $username)){
        header("Location: ../signup.php?error=invalidmailuid");
        exit();

    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        header("Location: ../signup.php?error=invalidmail&uid=.$username");
        exit();
    }

    else if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){
        header("Location: ../signup.php?error=invalidmail&uid=".$username."&mail="."$email");
        exit();

    }
    elseif($password !== $passwordRepeat){
        header("Location: ../signup.php?error=passwordcheckbuilduid&uid=".$username."&mail="."$email");
        exit();

    }
    else{
        $sql = "SELECT uidUsers FROM users WHERE uidUsers=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            # code...
            header("Location ../signup.php?error=sqlerror");
            exit();
        }
        else{
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_results($stmt);
            $resultsCheck = msqli_stmt_num_rows($stmt);
            if($resultsCheck > 0){
            header("Location ../signup.php?error=usertaken&mail".$email);
            exit();
            }
            else{
        $sql = "INSERT INTO users (uidUser, emailUsers, pwdUsers) VALUES (?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if (!mysql_stmt_prepare($stmt, $sql)) {
            # code...
            header("Location ../signup.php?error=sqlerror");
            exit();
        }else{
            $hashPwd = password_hash($password, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);
            mysqli_stmt_execute($stmt);
            header("Location ../signup.php?signup= success");
            exit();
        }
            }
        }
    }mysqli_stmt_($stmt);
    mysqli_close($conn);

}
else{
    header("LOcation: ../signup.php");
    exit();
}