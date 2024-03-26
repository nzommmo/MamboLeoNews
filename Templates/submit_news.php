<?php
require_once 'config.php'; // Include the database configuration file

$message = ""; // Initialize variable for message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user input to prevent SQL Injection
    $title = mysqli_real_escape_string($conn, $_POST["title"]);
    $summary = mysqli_real_escape_string($conn, $_POST["summary"]);
    $article_url = mysqli_real_escape_string($conn, $_POST["article_url"]);

    // SQL insert query
    $sql = "INSERT INTO Articles2 (title, summary, article_url) VALUES ('$title', '$summary', '$article_url')";

    if (mysqli_query($conn, $sql)) {
        $message = "New record created successfully";
    } else {
        $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Close connection (not necessary as config.php handles it)
// mysqli_close($conn);
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Add NEWS</title>
  </head>
  <body>

  <div class="container">
    <h2>Add News Article</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" class="form-control" id="title" name="title">
      </div>
      <div class="form-group">
        <label for="summary">Information:</label>
        <textarea class="form-control" id="summary" name="summary" rows="5"></textarea>
      </div>
      <div class="form-group">
        <label for="article_url">article_url:</label>
        <input type="text" class="form-control" id="title" name="article_url">
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <?php echo $message; ?>
  </div>











    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  </body>
</html>