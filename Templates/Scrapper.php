<?php
$url = 'https://www.standardmedia.co.ke/'; // Replace with the URL of the website you want to scrape

// Fetch the content from the URL
$html = file_get_contents($url);

if ($html === false) {
    // Handle error if unable to fetch content
    echo "Error fetching URL";
} else {
    // Parse the HTML content to extract relevant data
    $doc = new DOMDocument();
    libxml_use_internal_errors(true); // Disable libxml errors
    $doc->loadHTML($html);
    libxml_clear_errors();

    // Initialize an array to store articles
    $articles = [];

    // Find all anchor tags (links) with a specific class (you may need to inspect the HTML of the website to find appropriate selectors)
    $links = $doc->getElementsByTagName('a');
    foreach ($links as $link) {
        $href = $link->getAttribute('href');
        $text = trim($link->nodeValue);

        // Check if the link leads to an article
        if (strpos($href, '/article/') !== false && !empty($text)) {
            // Store the article link and title in the articles array
            $articles[] = [
                'title' => $text,
                'url' => $href
            ];
        }
    }

    // Insert the extracted data into the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "News";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Insert each article into the database
    foreach ($articles as $article) {
        $title = mysqli_real_escape_string($conn, $article['title']);
        $url = mysqli_real_escape_string($conn, $article['article_url']);
        
        // SQL query to insert data into the database
        $sql = "INSERT INTO Articles (title, article_url) VALUES ('$title', '$url')";

        if (mysqli_query($conn, $sql)) {
            echo "Article inserted successfully: $title <br>";
        } else {
            echo "Error inserting article: $title <br>";
        }
    }

    mysqli_close($conn);
}
?>
