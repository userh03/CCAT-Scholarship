<?php
// Assuming you already have a PDO database connection named $pdo
include("connection.php");

// Get the token parameter from the POST data
$token = $_POST['token'];

if (isset($token)) {
  $query = "SELECT * FROM registration_tokens_temp WHERE token = :token AND is_valid = 1";
  $statement = $pdo->prepare($query);
  $statement->bindValue(':token', $token);
  $statement->execute();
  $row = $statement->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    // Valid token
    echo "valid";
  } else {
    // Invalid token
    echo "invalid";
  }
} else {
  // Invalid request
  http_response_code(400);
  echo "Invalid request";
}
?>
