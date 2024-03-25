<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "News";

$message = ""; // Initialize variable for message

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Escape user input to prevent SQL Injection (replace with your actual data)
  $title = mysqli_real_escape_string($conn, $_POST["title"]);
  $info = mysqli_real_escape_string($conn, $_POST["info"]);

  // SQL insert query
  $sql = "INSERT INTO Articles (title, info) VALUES ('$title', '$info')";

  if (mysqli_query($conn, $sql)) {
    $message = "New record created successfully";
  } else {
    $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
}

// Close connection
mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Add News Article</title>
</head>
<body>

  <h2>Add News Article</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="title">Title:</label>
    <input type="text" id="title" name="title"><br><br>
    <label for="info">Information:</label>
    <textarea id="info" name="info" rows="5" cols="30"></textarea><br><br>
    <input type="submit" value="Submit">
  </form>

  <?php echo $message; ?>

</body>
</html>