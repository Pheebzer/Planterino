<?php
function db_connect() {

  static $connection;
  //parse credentials from file
  if(!isset($connection)) {
      $config = parse_ini_file('/home/pete/private/login.ini');
      $connection = mysqli_connect($config['host'],$config['user'],$config['passwd'],$config['dbname']);
    }

    if($connection === false) {
        return mysqli_connect_error();
    }
    return $connection;
}

$connection = db_connect();

// catch failed connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$sql = 'SELECT date, moisture FROM moisturelog';
$result = $connection->query($sql);

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Plant Monitor</title>
    <style>
    html {
  font-family: sans-serif;
}

.buttondiv {
  padding-top: 30px;
}

.tablediv {
  position: fixed; left: 30%;
  padding-top: 15px;
}

h1 {
  position: fixed; left: 38%;
}

table {
  border-collapse: collapse;
  border: 2px solid rgb(200,200,200);
  letter-spacing: 1px;
  font-size: 2rem;
}

td, th {
  border: 1px solid rgb(190,190,190);
  padding: 10px 20px;
}

th {
  background-color: rgb(235,235,235);
}

td {
  text-align: center;
}

tr:nth-child(even) td {
  background-color: rgb(250,250,250);
}

tr:nth-child(odd) td {
  background-color: rgb(245,245,245);
}

caption {
  padding: 10px;
}
    </style>
  </head>
  <body>
  <h1>PLANT MONITOR</h1>
  <div class="tablediv">
    <table>
      <tr>
        <td>DATE WATERED</td>
        <td>MOISTURE </td>
      </tr>
<?php
if ($result->num_rows > 0) {
    $i = 0;
    while($row = $result->fetch_assoc()) {
      //This if statement is only for index, to limit the number of entries shown
      if($i < 3){
        echo "<tr>".
		"<td>".$row["date"]."</td>".
		"<td>".$row["moisture"]."%"."</td>".
	     "</tr>"."<br>";
        $i++;
     }
    }
} else {
    echo "0 results";
}
$connection->close();
?>
    </table>
    <div class='buttondiv'>
      <a href="history.php"><button>Show full history</button></a>
    </div>
  </div>
</html>
