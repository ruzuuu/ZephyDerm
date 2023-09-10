<?php
if (isset($_GET['id'])) {
    include "../../db_connect/config.php";
    $id = $_GET['id'];
    $sql = "SELECT * FROM service WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
    if (!$row) {
        echo "FAQ not found.";
        exit;
    }
    } else {
        echo "Invalid request.";
        exit;
    }

if (isset($_POST['edit_submit'])) {
    $service = $_POST['services'];
    $sql = "UPDATE service SET services=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $service, $id);
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header("Location: service.php");
        exit();
    }
    else {
        echo "Error Updating Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">   
    <title>Edit FAQ</title>
</head>

<body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $id; ?>">
    <label for="edit_answer">Answer:</label>
    <textarea name="services" class="summernote"><?php echo $row['services']; ?></textarea>
    <input type="submit" name="edit_submit" value="Submit">
</form>

    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                
            });
        });
    </script>
</body>
</html>
