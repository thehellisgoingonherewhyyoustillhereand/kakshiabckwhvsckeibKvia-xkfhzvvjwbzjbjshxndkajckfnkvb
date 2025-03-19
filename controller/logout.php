<?php
session_start();
unset($_SESSION["auth_key"]);
unset($_SESSION["auth_name"]);
session_destroy();
header("Location: /controller/create");
?>