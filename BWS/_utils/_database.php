<?php

include "../../setting.php";
header('Content-Type: text/html; charset=utf-8');
$login = 0;

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $result = Query("SELECT id_user, token FROM BWS_Users WHERE (email, password) = ('$email','$password')");
    if ($result->num_rows) {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        if ($row['id_user'] == 2) {
            $login = 1;
            setcookie('token', "$row[token]", [
                'expires' => time() + 60 * 60 * 24 * 30,
                'path' => "/",
                // 'samesite' => 'None',
                'secure' => 'Secure',
                'httponly' => false,
            ]);
        }
    }
}

if (isset($_COOKIE['token'])) {
    $token = $_COOKIE['token'];
    $id_user = Query("SELECT id_user FROM BWS_Users WHERE token = '$token'")->fetch_array(MYSQLI_ASSOC)['id_user'];
    if ($id_user == 2) {
        $login = 1;
        setcookie('token', "$token", [
            'expires' => time() + 60 * 60 * 24 * 30,
            'path' => "/",
            // 'samesite' => 'None',
            'secure' => 'Secure',
            'httponly' => false,
        ]);
    }
}
