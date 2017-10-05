<?php
    // A simple PHP script demonstrating how to connect to MySQL.
    // Press the 'Run' button on the top to start the web server,
    // then click the URL that is emitted to the Output tab of the console.

    $servername = getenv('IP');
    $username = getenv('C9_USER');
    $password = "";
    $database = "tech_support";
    $dbport = 3306;

    // Create connection
    $db = new mysqli($servername, $username, $password, $database, $dbport);

    // Check connection
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    } 
    echo "Connected successfully (".$db->host_info.")";
    
    $sql = "SELECT firstName, lastName, city FROM `customers` WHERE state = 'CA' order by lastname";
    $results = $db->prepare($sql);
    $results -> execute();
    $results -> store_result();
    $results -> bind_result($first, $last, $city);
    
    echo "<h1>Query</h1>";
    echo "<table style=\"width:100%\">
  <tr>
    <th>Firstname</th>
    <th>Lastname</th> 
    <th>City</th>
  </tr>";
    while($results -> fetch()) {
        echo "<tr>
    <td><center>$first</center></td>
    <td><center>$last</center></td> 
    <td><center>$city</center></td>
  </tr>";
        // echo "<br />First Name: ".$first;
        // echo "<br />Last Name: ".$last;
        // echo "<br />City: ".$city;
        }
        echo "</table>";
    $results->free_result();
    
    $simple = "SELECT * FROM `products` WHERE `version` = 2.0 LIMIT 0, 30 ";
    $simpleresults = $db->prepare($simple);
    $simpleresults -> execute();
    $simpleresults -> store_result();
    $simpleresults -> bind_result($productcode, $name, $version, $releasedate);
    
    echo "<h1>Simple</h1>";
    
    while($simpleresults -> fetch()) {
        echo "<p><strong>Product Code: ".$productcode."</strong>";
        echo "<br />Name: ".$name;
        echo "<br />Version: ".$version;
        echo "<br />Release Date: ".$releasedate;

        }
        
    $simpleresults->free_result();
    
    $intermediate = "SELECT c.firstName, c.lastName, r.registrationDate, p.productCode, p.name, p.version, p.releaseDate \n"
    . "FROM `registrations` as r\n"
    . "Inner join `products` as p on p.productCode = r.productCode\n"
    . "inner join `customers` as c on c.customerID = r.customerID\n"
    . "WHERE r.customerID = 1004 LIMIT 0, 30 ";
    
    $intermediateresults = $db->prepare($intermediate);
    $intermediateresults -> execute();
    $intermediateresults -> store_result();
    $intermediateresults -> bind_result($first, $last, $regDate, $prodCode, $name, $version, $releaseDate);
    
    echo "<h1>Intermediate and Advanced</h1>";
    
    $count = 0;
    while($intermediateresults -> fetch()) {
        if($count == 0) {
            echo "<h2>" . $first . " " . $last . "'s registration history: </h2>";
        }
        echo "<p><strong>Product Code: " . $prodCode . "</strong>";
        echo "<br />Name: " . $name;
        echo "<br />Version: " . $version;
        echo "<br />Release Date: " . $releaseDate;
        echo "<br />Registration Date: " . $regDate;
        $count++;
        }
        
    $intermediateresults -> free_result();
    
    $db->close();
    
?>
    
    
    