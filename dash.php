<?php 

session_start()

//connecting to the database
$conn = new mysqli("localhost", "root", "", "officer_db", 3307);
if ($conn -> connect_error) {
    die("Connection failed:". $conn ->connect_error);
}

$national_id = $_SESSION['national_id'] ?? '';
$login_time = $_SESSION['login_time'] ?? date('Y-m-d H:i:s');


//getting officer location from their station when registering
$location_result = $conn->query("
        SELECT station FROM officers
        WHERE national_id = '$national_id'     
");

$officer_location = "unknown";
if($loc_row = $location_result->fetch_assoc()) {
    $officer_location = $loc_row['station'];
}

//counting tickets made within 10min
$pace_result = $conn->query("
        SELECT COUNT(*) AS pace FROM tickets
        WHERE location = '$officer_location'
        AND created_at >= NOW() - INTERVAL 10 MINUTE
");


$pace_row = $pace_result->fetch_assoc();
$tickets_in_10min = $pace_row['pace'];

//checking the average time gap
$expected_gap = 2;
if($tickets_in_10min > 0) {
    $expected_gap = round(10 / $tickets_in_10min);
}

#saving to location analysis table
$conn->query("
      INSERT INTO location_analysis(location, tickets_per_10min, expected_gap_minutes)
      VALUES('$officer_location', '$tickets_in_10min', '$expected_gap')
      ON DUPLICATE KEY UPDATE
      tickets_per_10min = '$tickets_in_10min',
      expected_gap_minutes = '$expected_gap',
      last_updated = NOW()
");

//getting last ticket time
$last_ticket_time = $_SESSION['last_ticket_time'] ?? null;

  
$warning_count = $_SESSION['warning_count'] ?? 0; 
$reported = $_SESSION['reported'] ?? false; 


$total_today = $conn -> query("SELECT COUNT(*) AS total_today FROM tickets WHERE DATE(created_at) = CURDATE() AND national_id = '$national_id' ") ->fetch_assoc()['total_today'];
$paid_fines = $conn -> query("SELECT COUNT(*) AS paid_fines FROM tickets WHERE payment_status = 'Paid' AND national_id = '$national_id' ") ->fetch_assoc()['paid_fines'];
$unpaid_fines = $conn -> query("SELECT COUNT(*) AS unpaid_fines FROM tickets WHERE payment_status = 'Unpaid' AND national_id = '$national_id' ") ->fetch_assoc()['unpaid_fines'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="process_dash_style.css">

    <title>dashboard_php</title>
</head>
<body>
     <div class="left_pane">
        <h2> Fine Tracker </h2>
        <a href="dash.php">Dashboard</a>
        <a href="Tickets.php">Tickets</a>
        <a href="Login_site.html">Logout</a>
     </div>

     <div class="main">
        <section id="dashboard">
            <h1>Dashboard</h1>

            <div class="card">
                <h3>Total Tickets today</h3>
                    <p><?php echo $total_today; ?></p>
            </div>        
            
            <div class="card">
                <h3>Paid Fines</h3>
                <p><?php echo $paid_fines; ?></p>
            </div>   
            
            <div class="card">
                <h3>Unpaid Fines</h3>
                <p><?php echo $unpaid_fines; ?></p>
            </div>    

            <div class="card">
                <h3>Officer Location</h3>
                <p><?php echo $officer_location; ?></p>
            </div>
        </section>        
    </div>

    <div class="ticket">
        <section id="tickets">
            <h1>Tickets</h1>
            <table>
                <tr>
                    <th>Ticket Type</th>
                    <th>Fine Amount</th>
                </tr>

<?php
$result = $conn->query("
    SELECT ticket_type, fine_amount
    FROM tickets
    WHERE national_id = '$national_id'
    ORDER BY created_at DESC
");

    if ($result && $result ->num_rows > 0) {
        while($row = $result -> fetch_assoc()) {
            echo "<tr> 
                  <td> " . htmlspecialchars($row['ticket_type']) . "</td> 
                  <td>R" . htmlspecialchars($row['fine_amount']) . "</td> 
                 </tr>";
                   }
        } else {
                 echo "<tr><td colspan='2'>No tickets found</td></tr>";
        }
     $conn ->close(); 
     ?> 
  
            </table>
        </section>         
    </div>  
    
    <!--timer amnd pop_up-->
<!--converting minutes to seconds and to miliseconds-->
     <script>
        //expected gap/min/period for tickets
         var expectedGap = <?php echo $expected_gap; ?>;
         var firstWarning = 2 * 60 * 1000; // after 2min - warning officer
         var secondWarning = 3 * 60 * 1000; // second warning to officer
         var reportTime =  4 * 60 * 1000; // report    

         var lastTicketTime = "<?php echo $last_ticket_time ? $last_ticket_time : $login_time; ?>";  // ? means if and : means else
         var warningCount = <?php echo $warning_count; ?>; // get from session
         var reported = <?php echo $reported ? 'true' : 'false'; ?>; // is there any reported officer

         
         function checkInactivity() {
            var now = new Date().getTime();
            var last = new Date(lastTicketTime).getTime(); //getTime converts into miliseconds
            var diff = now - last;

            if(reported) {
                clearInterval(timer); // stop timer if user gives a ticket
                return;
            }
        
            if (diff >= reportTime && warningCount >=2) {
                reported = true;
                clearInterval(timer); 
                
                fetch('save_warning_count.php?count=2&reported=true');
                fetch('save_alert.php?national_id=<?php echo $national_id; ?>&location=<?php echo $officer_location; ?>&type=bribe');
                alert('ALERT: You have been reported to head office!');
            } else if (diff >= secondWarning && warningCount === 1) //this fires exactly when warning count is 1 meaning it has given a warning already
                 {
                warningCount++;
                fetch('save_warning_count.php?count=' + warningCount);
                fetch('save_alert.php?national_id=<?php echo $national_id; ?>&location=<?php echo $officer_location; ?>&type=warning');
                alert('WARNING ' + warningCount + ':  Please do your job!');
            }
            else if(diff >= firstWarning && warningCount < 1) {
                warningCount++;
                fetch('save_warning_count.php?count=' + warningCount);
                fetch('save_alert.php?national_id=<?php echo $national_id; ?>&location=<?php echo $officer_location; ?>&type=warning');
                alert('WARNING ' + warningCount + ': You have not issued a ticket in a while!');
            }
         }

                var timer = setInterval(checkInactivity, 10000);
        

         setInterval(function() {
              fetch('heartbeat.php');
         }, 10000);

        </script>
</body>
</html>
