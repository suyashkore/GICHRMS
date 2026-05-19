<?php
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$host = getenv('DB_HOST');
$db = getenv('DB_DATABASE');
$user = getenv('DB_USERNAME');
$pass = getenv('DB_PASSWORD');
echo "$host $db $user\n";
$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$email = 'suyash.kore@globalinfocloud.com';
$from = '2026-05-02';
$to = '2026-05-18';
$stmt = $pdo->prepare(
    'select r.id,r.staff_id,r.request_type_id,r.from_date,r.to_date,r.status,rt.code as request_code from hrm_employee_requests r left join hrm_request_types rt on rt.id=r.request_type_id where r.staff_id=(select staffid from tblstaff where email=:email) and r.status in (\'pending\',\'approved\') and r.from_date<=:to and r.to_date>=:from'
);
$stmt->execute(['email' => $email, 'from' => $from, 'to' => $to]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows, JSON_PRETTY_PRINT);
