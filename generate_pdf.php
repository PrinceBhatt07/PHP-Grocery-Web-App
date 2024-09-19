<?php
require './vendor/autoload.php'; // Include Composer's autoloader
use Dompdf\Dompdf;
use Dompdf\Options;

// Fetch the data from the database (like you did in index.php)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grocerydash";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the fruits and vegetables data using mysqli
$fruitsResult = $conn->query("SELECT * FROM fruits");
$vegetablesResult = $conn->query("SELECT * FROM vegetables");

// Fetch all fruits and vegetables as associative arrays
$fruits = $fruitsResult->fetch_all(MYSQLI_ASSOC);
$vegetables = $vegetablesResult->fetch_all(MYSQLI_ASSOC);

// Convert image to base64
function imageToBase64($imagePath) {
    $imageData = file_get_contents($imagePath);
    $base64 = base64_encode($imageData);
    return 'data:image/png;base64,' . $base64;
}

$logoBase64 = imageToBase64('./assets/logo.png');
$backImg = imageToBase64('./assets/42258.jpg');

// Instantiate Dompdf with options
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true); // Enable loading of remote resources (e.g., images)
$dompdf = new Dompdf($options);

// Set a record limit per table (for example, 20 records per table)
$recordLimit = 80;

$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruits and Vegetables Table</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    body {
        font-family: Arial, sans-serif;
        background-image: url("https://pikbest.com//png-images/abstract-geometric-organic-vegetable-food-fruits-and-vegetables-background_10001056.html");
    }
    .container {
        width: 100%;

    }
    .table-container {
        display: inline-block; /* Make tables display side by side */
        width: 30%; /* Set each table to take half of the page width */
        margin-right: 1%; /* Small margin between tables */
        vertical-align: top; /* Align tables at the top */
    }
    .table-container:nth-of-type(1){
        padding-right : 20px;
    }
    .table-container:nth-of-type(1){
        padding-left : 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 10px 0; /* Reduced margin for tables */
        font-size: 7px; /* Reduced font size to fit more data */
    }
    table, th, td {
        border: 1px solid #000;
        padding: 2px; /* Reduce padding to minimize row height */
        text-align: center;
    }
    thead th {
        background-color: green;
        color: white;
        text-transform: uppercase;
    }
    .header {
        width: 100%;
        padding: 0 10px 10px;
        background-color: #f1f1f1;
        height : 80px;
    }
    .header .logo {
        display: inline-block;
        width: 100%;
    }
    .header .company {
        text-align: right;
    }
    p{
        font-family:Arial, sans-serif;
        font-size: 14px;
    }
    /* Style for reducing column widths */
    table th, table td {
        width: auto;
        white-space: nowrap; /* Prevent text from wrapping in cells */
        padding : 4px;
    }
    @media print {
        @page {
            size: A4 landscape; /* Force the size to landscape */
            margin: 0.5in; /* Small margin for more space */
        }
    }
    </style>
</head>
<body style="background-color:#FFFFE0">
    <div class="header">
        <div class="logo" style="postion:relative; height:fit-content;">
            <center>
                <img src="' . $logoBase64 . '" alt="Logo" style="height:80px;padding:5px 5px;">
            </center>
            <p style="position: absolute;top: 30px;right:10px">Email: tsaarian7@gmail.com <br>Contact: +61 430 335 397</p>
        </div>
    </div>
    <div class="container">';

// First, display the fruits table(s)
$totalFruits = count($fruits);
for ($i = 0; $i < $totalFruits; $i += $recordLimit) {
    // Open a new table container
    $html .= '<div class="table-container"><table class="table" style="margin-left:10px">
        <thead>
            <tr>
                <th style="text-align : left; border-right:none">Fruit</th>
                <th style="text-align:left;border-left:none">Veggies</th>
                <th>Size</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>';
    
    // Populate rows for the current table
    for ($j = $i; $j < min($i + $recordLimit, $totalFruits); $j++) {
        $html .= '<tr>
            <td style="text-align:left;border-right:none">' . htmlspecialchars($fruits[$j]['name']) . '</td>
            <td style="text-align:left;border-left:none">' . htmlspecialchars($fruits[$j]['veggies']) . '</td>
            <td>' . htmlspecialchars($fruits[$j]['size']) . '</td>
            <td>' .'<strong>$ </strong>'. htmlspecialchars($fruits[$j]['price']) . '</td>
        </tr>';
    }
    
    $html .= '</tbody></table></div>';
}

// Next, display the vegetables table(s)
$totalVegetables = count($vegetables);
for ($i = 0; $i < $totalVegetables; $i += $recordLimit) {
    // Open a new table container
    $html .= '<div class="table-container"><table class="table">
        <thead>
            <tr>
                <th style="text-align : left">Vegetable</th>
                <th>Size</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>';
    
    // Populate rows for the current table
    for ($j = $i; $j < min($i + $recordLimit, $totalVegetables); $j++) {
        $html .= '<tr>
            <td style="text-align : left">' . htmlspecialchars($vegetables[$j]['name']) . '</td>
            <td>' . htmlspecialchars($vegetables[$j]['size']) . '</td>
            <td>' .'<strong>$ </strong>'. htmlspecialchars($vegetables[$j]['price']) . '</td>
        </tr>';
    }
    
    $html .= '</tbody></table></div>';
}

$html .= '</div></body></html>';

// Load the HTML content
$dompdf->loadHtml($html);

// Set landscape orientation and A4 paper size
$dompdf->setPaper('A4', 'landscape');

// Render the PDF
$dompdf->render();

// Output the generated PDF to the browser
$dompdf->stream("fruits_and_vegetables.pdf", ["Attachment" => false]);

// Close the database connection
$conn->close();
?>
