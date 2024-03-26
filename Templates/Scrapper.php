<?php
require_once 'config.php'; // Include the database configuration file

function scrapeAndUploadArticles() {
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
                    'url' => $href,
                    'image' => ''
                ];
            }
        }

        // Find all image tags with a specific class (you may need to inspect the HTML of the website to find appropriate selectors)
        $images = $doc->getElementsByTagName('img');
        $imageIndex = 0;
        foreach ($images as $image) {
            $src = $image->getAttribute('src');

            // Store the image URL in the corresponding article
            if (!empty($src) && $imageIndex < count($articles)) {
                $articles[$imageIndex]['image'] = $src;
                $imageIndex++;
            }
        }

        global $conn; // Access the database connection from config.php

        // Check if articles already exist in the database
        $existingArticles = [];
        $result = mysqli_query($conn, "SELECT title, article_url FROM Articles2");
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $existingArticles[$row['title']] = $row['article_url'];
            }
            mysqli_free_result($result);
        }

        // Insert new articles into the database
        foreach ($articles as $article) {
            $title = mysqli_real_escape_string($conn, $article['title']);
            $url = mysqli_real_escape_string($conn, $article['url']);
            $image = mysqli_real_escape_string($conn, $article['image']);
            $summary = ""; // Summary is not extracted in the current scraping logic

            // Check if the article is new
            if (!isset($existingArticles[$title])) {
                // SQL query to insert data into the database
                $sql = "INSERT INTO Articles2 (title, summary, article_url, image_url) VALUES ('$title', '$summary', '$url', '$image')";

                if (mysqli_query($conn, $sql)) {
                    echo "New article inserted successfully: $title <br>";
                } else {
                    echo "Error inserting new article: $title <br>";
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            }
        }
    }
}

// Run the scraping and uploading function
scrapeAndUploadArticles();
?>
