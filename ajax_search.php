<?php

require_once "config/Database.php";
require_once "classes/Service.php";

$database = new Database();
$pdo = $database->connect();

$service = new Service($pdo);

$keyword = $_GET['keyword'] ?? '';
$category = $_GET['category'] ?? '';
$location = $_GET['location'] ?? '';

$results = $service->search($keyword, $category, $location);

header('Content-Type: application/json');
echo json_encode($results);