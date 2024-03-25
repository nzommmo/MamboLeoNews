<?php
// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "News";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to fetch image from Unsplash API based on title
function getImageFromUnsplash($title) {
    // Your Unsplash API access key
    $access_key = 'lEBtMpL03HaU4tsf5DD0fVu6jKkoyku2na2zga8Hw6Q';

    // Base URL for Unsplash API
    $url = 'https://api.unsplash.com/search/photos';

    // Parameters for the request
    $params = array(
        'query' => $title,
        'client_id' => $access_key
    );

    // Construct the URL with parameters
    $url .= '?' . http_build_query($params);

    // Set up cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if(curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
        return false;
    }

    // Close cURL session
    curl_close($ch);

    // Parse the JSON response
    $result = json_decode($response, true);

    // Return the response from Unsplash API
    return $result;
}

// Fetch titles from the database
$sql = "SELECT title FROM Articles";
$result = mysqli_query($conn, $sql);

// Array to store fetched titles
$titles = array();

if (mysqli_num_rows($result) > 0) {
    // Fetch each row and add title to the array
    while($row = mysqli_fetch_assoc($result)) {
        $titles[] = $row['title'];
    }
} else {
    echo "0 results found in the database";
}

// Close database connection
mysqli_close($conn);
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="../static/Homepage.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>MamboLeoNews</title>
</head>
<body><nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">MamboLeoNews</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Local News
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#">Business</a></li>
            <li><a class="dropdown-item" href="#">Technology </a></li>
            <li><a class="dropdown-item" href="#">Health </a></li>
            <li><a class="dropdown-item" href="#">Culture & Arts </a></li>
            <li><a class="dropdown-item" href="#">Sports </a></li>

          </ul>
        </li>
        <li class="nav-item">
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            National News
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
          <li><a class="dropdown-item" href="#">Business</a></li>
            <li><a class="dropdown-item" href="#">Technology </a></li>
            <li><a class="dropdown-item" href="#">Health </a></li>
            <li><a class="dropdown-item" href="#">Culture & Arts </a></li>
            <li><a class="dropdown-item" href="#">Sports </a></li>
          </ul>
        </li>
        <li class="nav-item">
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Global News
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
          <li><a class="dropdown-item" href="#">Business</a></li>
            <li><a class="dropdown-item" href="#">Technology </a></li>
            <li><a class="dropdown-item" href="#">Health </a></li>
            <li><a class="dropdown-item" href="#">Culture & Arts </a></li>
            <li><a class="dropdown-item" href="#">Sports </a></li>
          
          </ul>
       
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="../Templates/login.php">Login</a>
        </li>

          
        </li>
        <li class="nav-item">
        </li>
        
      
      </ul>

    
      <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>

<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <?php foreach ($titles as $index => $title): ?>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="<?php echo $index; ?>" <?php echo ($index == 0) ? 'class="active"' : ''; ?> aria-current="true" aria-label="Slide <?php echo $index + 1; ?>"></button>
        <?php endforeach; ?>
    </div>
    <div class="carousel-inner" id="caro">
        <?php foreach ($titles as $index => $title): ?>
            <?php
            // Fetch image from Unsplash API
            $response = getImageFromUnsplash($title);

            // Check if image found for the title
            if(isset($response['results'][0]['urls']['regular'])) {
                $imageUrl = $response['results'][0]['urls']['regular'];
                $activeClass = ($index == 0) ? 'active' : ''; // Add 'active' class to first image
                ?>
                <div class="carousel-item <?php echo $activeClass; ?>">
                    <img src="<?php echo $imageUrl; ?>" class="d-block w-100" alt="Image for <?php echo $title; ?>">
                    <div class="carousel-caption d-none d-md-block">
                        <h5><?php echo $title; ?></h5>
                        <p>Some representative placeholder content for the <?php echo $title; ?> slide.</p>
                    </div>
                </div>
            <?php } else {
                echo 'Image not found for ' . $title;
            }
            ?>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>


<div class="container mt-5">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <!-- News cards -->
        <div class="col">
            <div class="card">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">News Title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary">Read More</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">News Title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary">Read More</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">News Title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary">Read More</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">News Title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary">Read More</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">News Title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary">Read More</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">News Title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary">Read More</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">News Title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary">Read More</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <img src="..." class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">News Title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary">Read More</a>
                </div>
            </div>
        </div>
        <!-- Add more similar card divs for additional news -->

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
