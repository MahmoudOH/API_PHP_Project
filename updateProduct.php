<?php
include 'db_helper.php';

header("Content-Type:application/json");

$db_helper = new DbHelper();
$db_helper->createDbConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 $id = $_POST["id"];
 $name = $_POST["name"];
 $details = $_POST["details"];
 $price = $_POST["price"];
 $quantity = $_POST["quantity"];

 $db_helper->updateProduct($id, $name, $details, $price, $quantity);
}
?>