<?php
class DbHelper
{
 private $conn;

 public function createDbConnection()
 {
  try {
   $this->conn = new mysqli("localhost", "root", "", "products");
  } catch (Exception $error) {
   echo $error->getMessage();
  }
 }

 public function createResponse($success, $message, $data = null)
 {
  $response = array(
   'success' => $success,
   'message' => $message,
   'data' => $data
  );

  return json_encode($response);
 }

 public function insertNewProduct($name, $details, $price, $quantity)
 {
  try {
   $sql = "INSERT INTO product(name, details, price, quantity) VALUES ('$name', '$details', '$price', '$quantity')";
   $result = $this->conn->query($sql);

   if ($result == true) {
    echo $this->createResponse(
     true,
     "Data has been inserted",
     $this->createProductResponse(
      $this->conn->insert_id,
      $name,
      $details,
      $price,
      $quantity
     )
    );
   } else {
    echo $this->createResponse(
     false,
     "Data has not been inserted"
    );
   }
  } catch (Exception $error) {
   echo $this->createResponse(
    false,
    $error->getMessage(),
    ""
   );
  }
 }

 private function createProductResponse($id, $name, $details, $price, $quantity)
 {
  $product = array(
   'id' => $id,
   'name' => $name,
   'details' => $details,
   'price' => $price,
   'quantity' => $quantity
  );

  return $product;
 }

 function getAllProducts()
 {
  try {
   $sql = "select * from product";
   $result = $this->conn->query($sql);

   $count = $result->num_rows;
   if ($count > 0) {
    $all_products_array = array();
    while ($row = $result->fetch_assoc()) {

     $id = $row["id"];
     $name = $row["name"];
     $details = $row["details"];
     $price = $row["price"];
     $quantity = $row["quantity"];

     $product_array = $this->createProductResponse($id, $name, $details, $price, $quantity);
     array_push($all_products_array, $product_array);
    }
    $this->createResponse(true, $count, $all_products_array);
   } else {
    echo "No Data Found";
   }
  } catch (Exception $exception) {
   $this->createResponse(false, 0, array("error" => $exception->getMessage()));
  }

 }



 function getProductById($id)
 {
  $sql = "select * from product where id = $id";
  $result = $this->conn->query($sql);
  try {
   if ($result->num_rows == 0) {
    throw new Exception("there are no products with the passed id");
   } else {
    $row = $result->fetch_assoc();
    $id = $row["id"];
    $name = $row["name"];
    $details = $row["details"];
    $price = $row["price"];
    $quantity = $row["quantity"];

    $product_array = $this->createProductResponse($id, $name, $details, $price, $quantity);
    $this->createResponse(true, 1, $product_array);

   }
  } catch (Exception $exception) {
   http_response_code(400);
   $this->createResponse(false, 0, array("error" => $exception->getMessage()));
  }

 }

 function deleteProduct($id)
 {
  try {
   $sql = "delete from product where id = $id";
   $result = $this->conn->query($sql);

   if (mysqli_affected_rows($this->conn) > 0) {
    $this->createResponse(true, 1, array("data" => "Product has been deleted"));
   } else {
    throw new Exception("There are no products with the passed id");
   }
  } catch (Exception $exception) {
   $this->createResponse(false, 0, array("error" => $exception->getMessage()));
  }
 }

 public function updateProduct($id, $name, $details, $price, $quantity)
 {
  try {
   $sql = "UPDATE products SET name='$name', details='$details', price='$price', quantity='$quantity' WHERE id=$id";
   $result = $this->conn->query($sql);

   if ($result) {
    return $this->createResponse(true, "Product updated successfully");
   } else {
    throw new Exception("Failed to update product");
   }
  } catch (Exception $exception) {
   return $this->createResponse(false, $exception->getMessage(), array());
  }
 }


}

?>