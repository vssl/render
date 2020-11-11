<?php
switch (strtok($_SERVER["REQUEST_URI"], '?')) {
    case "/api/v1/pages":
        header("Content-Type: application/json");
        if (isset($_GET['slug'])) {
            readfile(__DIR__ . "/assets/single.json");
        } elseif (isset($_GET['ids'])) {
            echo json_encode(['pages' => array_map(function ($id) {
                return ['id' => $id];
            }, explode(",", $_GET['ids']))]);
        } else {
            readfile(__DIR__ . "/assets/list.json");
        }
        break;
    case "cache-test":
        echo "cache-test";
        break;
    default:
        return false;
}
