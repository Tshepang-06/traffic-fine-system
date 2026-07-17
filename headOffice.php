<?php 
session_start();

 $conn = new mysqli ("localhost", "root", "", "officer_db", 3307);
   if($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
   }

   //getting all reported officers from the law_breakers table
   $alerts = $conn->query("
     SELECT law_breakers.national_id, law_breakers.location,
            law_breakers.alert_type, law_breakers.created_at,
            officers.firstname, officers.lastname
     FROM law_breakers
     JOIN officers ON law_breakers.national_id = officers.national_id
     ORDER BY law_breakers.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Head Office</title>
</head>
<body>
    <h1>Head Office - Flagged Officers</h1>

    <table border="1">
        <tr>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>National ID</th>
            <th>location</th>
            <th>Alert type</th>
            <th>Time</th>
        </tr>    
    
     <?php while($row = $alerts->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['firstname']; ?></td>
            <td><?php echo $row['lastname']; ?></td>
            <td><?php echo $row['national_id']; ?></td>
            <td><?php echo $row['location']; ?></td>
            <td><?php echo $row['alert_type']; ?></td>
            <td><?php echo $row['created_at']; ?> </td>
        </tr>    
        <?php endwhile; ?>
    
    
    </table>

    <?php $conn->close(); ?>   

</body>
</html>
