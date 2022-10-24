  <?php
  $host = "localhost";
  $user = "root";
  $pass = "";
  $dbname = "celke";
  $port = "localhost";

 $conn =  new PDO("mysql:host=$host;dbname=".$dbname, $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)); 

  ?>