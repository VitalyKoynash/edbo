<?php
session_start();
require_once './config.php';


foreach ($cfg as $key => $value) {
	$data = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
	$_SESSION[$key] = $data;
}
header('Location: ../login/edbo-login.php');
?>

