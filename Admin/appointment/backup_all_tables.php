<?php
$host = "localhost";
$username = "root";
$password = "";//password to username, if locked
$db_name = "zephyderm_database";//name of database to access here

// Create connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the list of all tables in the database
$sql_tables = "SHOW TABLES";
$result_tables = $conn->query($sql_tables);

if ($result_tables->num_rows > 0) {
    // Initialize the SQL variable to store the SQL commands
    $sql_output = '';

    // Loop through each table
    while ($row_table = $result_tables->fetch_row()) {
        $table_name = $row_table[0];

        // Fetch data from the current table
        $sql_data = "SELECT * FROM $table_name";
        $result_data = $conn->query($sql_data);

        if ($result_data->num_rows > 0) {
            // Create the SQL command to recreate the table
            $sql_output .= "-- Table structure for table: $table_name\n";
            $sql_output .= "CREATE TABLE `$table_name` (\n";

            // Get the table structure
            $sql_structure = "SHOW COLUMNS FROM $table_name";
            $result_structure = $conn->query($sql_structure);

            // Store foreign keys to add them later
            $foreign_keys = [];

            // Loop through each column and add it to the SQL command
            while ($row_structure = $result_structure->fetch_assoc()) {
                $sql_output .= "  `{$row_structure['Field']}` {$row_structure['Type']}";
                if ($row_structure['Null'] == 'NO') {
                    $sql_output .= " NOT NULL";
                }
                if ($row_structure['Default'] !== null) {
                    $sql_output .= " DEFAULT '{$row_structure['Default']}'";
                }
                if (!empty($row_structure['Key']) && $row_structure['Key'] === 'PRI') {
                    $sql_output .= " PRIMARY KEY";
                }
                $sql_output .= ",\n";

                // Check if the column has a foreign key constraint
                if (!empty($row_structure['Key']) && $row_structure['Key'] === 'MUL') {
                    $foreign_keys[] = "FOREIGN KEY (`{$row_structure['Field']}`) REFERENCES `{$row_structure['Field']}_ref_table` (`id`)";
                }
            }

            // Add foreign keys to the table structure
            if (!empty($foreign_keys)) {
                $sql_output .= "  " . implode(",\n  ", $foreign_keys) . ",\n";
            }
            $sql_output .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8;\n\n";

            // Create the SQL command to insert data into the table
            $sql_output .= "-- Data for table: $table_name\n";
            while ($row_data = $result_data->fetch_assoc()) {
                $column_names = implode("`, `", array_keys($row_data));
                $column_values = implode("', '", array_values($row_data));
                $sql_output .= "INSERT INTO `$table_name` (`$column_names`) VALUES ('$column_values');\n";
            }
            $sql_output .= "\n";
        }
    }
    // Close the database connection
    $conn->close();

    // Set the appropriate headers for the download
    header('Content-Type: application/sql');
    header('Content-Disposition: attachment; filename=database_backup.sql');

    // Output the SQL commands to the browser for download
    echo $sql_output;
} else {
    echo "No tables found in the database.";
}
?>
