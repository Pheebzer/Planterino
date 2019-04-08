//TODO
//Format data in pleasant way (from line 32 onwards)
//Secure config file
//Move to 'plant' subdomain on webserver and do a production test.

<?php
function db_connect() {

  static $connection;

  if(!isset($connection)) {
      $config = parse_ini_file('login.ini');
      $connection = mysqli_connect($config['host'],$config['user'],$config['passwd'],$config['dbname']);
    }

        // If connection was not successful, handle the error
    if($connection === false) {
            // Handle error - notify administrator, log to a file, show an error screen, etc.
        return mysqli_connect_error();
    }
    return $connection;
}

// Connect to the database
$connection = db_connect();

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$sql = 'SELECT date, moisture FROM moisturelog';
$result = $connection->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "Date: " . $row["date"]. " | Moisture: " . $row["moisture"] . "\n";
    }
} else {
    echo "0 results";
}
$connection->close();
?>
