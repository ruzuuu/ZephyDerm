<?php
include "../../db_connect/config.php";
$slots_query = "SELECT slots_left FROM slots WHERE id = 1";
$slots_result = mysqli_query($conn, $slots_query);
$slots_row = mysqli_fetch_assoc($slots_result);
$slotsLeft = $slots_row['slots_left'];

if (isset($_POST['update'])) {
    $newSlotsLeft = $_POST['slotsLeft'];
    $updateQuery = "UPDATE slots SET slots_left = $newSlotsLeft WHERE id = 1";

    if ($conn->query($updateQuery) === TRUE) {
        echo "Slots Left updated successfully.";
        $slotsLeft = $newSlotsLeft;
    } else {
        echo "Error updating Slots Left: " . $conn->error;
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Slots Left</title>
</head>
<body>
    <h1>Edit Slots Left</h1>
    <form method="post">
        <label>Slots Left</label>
        <input type="number" class="form-control" name="slotsLeft" value="<?php echo $slotsLeft; ?>" required>
        <input type="submit" name="update" value="Update Slots Left">
    </form>
</body>
</html>