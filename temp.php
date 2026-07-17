

/*
$last_ticket_result = $conn->query("
      SELECT created_at FROM tickets
      WHERE national_id = '$national_id'
      ORDER BY created_at DESC LIMIT 1
");
*/

/*
if ($lt_row = $last_ticket_result->fetch_assoc()) {
    $last_ticket_time = $lt_row['created_at'];
}
*/