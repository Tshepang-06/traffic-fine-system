           <?php 

           $conn = new mysqli("localhost", "root", "", "officer_db", 3307);
           $result = $conn->query("SELECT id, firstname, lastname, station FROM officers");
           while($row = $result -> fetch_assoc()) {
            echo "<option value'" . htmlspecialchars($row['firstname']) . " "
                                 . htmlspecialchars($row['lastname']) . " ("
                                 . htmlspecialchars($row['station']) . ")</option>";

           }
           $conn -> close();
           ?>
