<?php
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Tables</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/scripts.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css?v=<?php echo time();?>">
    <link rel="stylesheet" type="text/css" href="css/index.css?v=<?php echo time();?>">

</head>

<body>

    <div class="headersection">
        <div class="wrap-logo">
            <img class="logo" src="./assets/logo.png" alt="Logo">
        </div>
        <div class="info">
            <p style="">Email: tsaarian7@gmail.com</p>
            <p style="">Contact: +61 430 335 397</p>
        </div>
    </div>
    <div>
        <button id="printPDF" class="print_button"> <img src="./assets/print_icon.png" alt="Print Icon"> </button>
    </div>
    </div>
    <div class="container">
        <div class="table-container">
            <div class="outerBox">
                <div class="boxOne">
                    <table id="fruitsTable" class="excel-table">
                        <thead>
                            <tr>
                                <th style="width: 175px;">Fruit</th>
                                <th style="width: 115px;">Veggies</th>
                                <th style="width: 55px;">Size</th>
                                <th style="width: 75px;">Price</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM fruits";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr data-id="' . $row['id'] . '">';
                                    echo '<td><input type="text" name="fruit_name" class="fruit-input" value="' . $row['name'] . '"></td>';
                                    echo '<td><input type="text" name="veggies" class="veggies-input" value="' . $row['veggies'] . '"></td>';
                                    echo '<td><input type="text" name="size" class="size-input" value="' . $row['size'] . '"></td>';
                                    echo '<td class="pricewrap"><span>$</span><input type="text" name="price" class="price-input" value="' . $row['price'] . '"></td>';
                                    echo '<td><button class="remove-row-btn  remove-btn remove-btn-fruits"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
                                    echo '<input type="hidden" class="row-id" value="' . $row['id'] . '">';
                                    echo '</tr>';
                                }
                            } else {
                                echo "<tr><td colspan='5'>No data available</td></tr>";
                            }


                            ?>
                        </tbody>

                    </table>
                    <button class="add-row-btn addBtn add-btn" data-table="fruitsTable">Add Fruit</button>
                </div>
                <div class="boxTwo">
                    <div class="boxTwo">
                        <table id="vegetablesTable">
                            <thead>
                                <tr>
                                    <th>Vegetables</th>
                                    <th style="width: 55px;">Size</th>
                                    <th style="width: 75px;">Price</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $sqlVeg = "SELECT * FROM vegetables";
                                $result = $conn->query($sqlVeg);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<tr data-id="' . $row['id'] . '">';
                                        echo '<td><input type="text" name="vegetable_name" class="vegetable-input" value="' . $row['name'] . '"></td>';
                                        echo '<td><input type="text" name="size" class="size-input" value="' . $row['size'] . '"></td>';
                                        echo '<td class="pricewrap"><span>$</span><input type="text" name="price" class="price-input" value="' . $row['price'] . '"></td>';
                                        echo '<td><button class="remove-row-btn remove-btn remove-row-btn-vegetables"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
                                        echo '<input type="hidden" class="row-id" value="' . $row['id'] . '">';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No data available</td></tr>";
                                }



                                ?>
                            </tbody>
                        </table>
                        <button class="add-row-btn-veg form_submit addBtn" data-table="vegetablesTable">Add Vegetable</button>
                    </div>
                </div>
            </div>





            <!-- Add button remains the same -->
        </div>
        <?php
        //require_once 'vegetables.php';
        ?>
    </div>
    <script>
        document.getElementById('printPDF').addEventListener('click', function() {
            window.open('generate_pdf.php', '_blank');
        });
    </script>
</body>

</html>