<?php
switch (strtok($_SERVER["REQUEST_URI"], '?')) {
    case "/api/pages":
        header("Content-Type: application/json");
        readfile(__DIR__ . "/assets/" . (isset($_GET['slug']) ? "single" : "list") . ".json");
        break;
    default:
        return false;
}
