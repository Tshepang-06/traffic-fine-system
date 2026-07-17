                    <?php
                   $result = $conn ->query("SELECT tickets.ticket_type, tickets.fine_amount, tickets.payment_status, officers.firstname, officers.lastname, officers.station FROM tickets JOIN officers ON tickets.officer_id = officers.id ORDER BY created_at DESC");
                   
                   if ($result && $result ->num_rows > 0) {
                   while($row = $result -> fetch_assoc()) {
              echo "<tr> 
                    <td> " . htmlspecialchars($row['ticket_type']) . "</td> 
                    <td>R" . htmlspecialchars($row['fine_amount']) . "</td> 
 </tr>";
                   }
                   } else {
                    echo "<tr><td> colspan='2'>No tickets found</td></tr>";
                   }
                    $conn ->close(); 
                   ?> 
               
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['ticket_type']) . "</td>
                <td>R" . htmlspecialchars($row['fine_amount']) . "</td>
                <td>" . htmlspecialchars($row['payment_status']) . "</td>
                <td>" . htmlspecialchars($row['location']) . "</td>
                <td>" . htmlspecialchars($row['notes']) . "</td>
                <td>" . htmlspecialchars($row['created_at']) . "</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No tickets found</td></tr>";
}
$conn->close();
?> 





// use this one
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['ticket_type']) . "</td>
                <td>R" . htmlspecialchars($row['fine_amount']) . "</td>

              </tr>";
    }
} else {
    echo "<tr><td colspan='2'>No tickets found</td></tr>";
}
$conn->close();
?>