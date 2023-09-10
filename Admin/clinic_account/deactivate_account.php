<?php
include "../function.php";
checklogin();

if (isset($_POST['zep_acc'])) {
    $zep_acc = $_POST['zep_acc'];
    $action = $_POST['action']; // Action will be either 'deactivate' or 'reactivate'
    
    if ($action === 'deactivate') {
        deactivateAccount($zep_acc);
    } elseif ($action === 'reactivate') {
        reactivateAccount($zep_acc);
    }
}

function deactivateAccount($zep_acc) {
    include "../../db_connect/config.php";
    $sql = "UPDATE zp_accounts SET account_status = 'deactivated' WHERE zep_acc = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $zep_acc);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header("Location: clinic_account.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

function reactivateAccount($zep_acc) {
    include "../../db_connect/config.php";
    $sql = "UPDATE zp_accounts SET account_status = 'active' WHERE zep_acc = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $zep_acc);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header("Location: clinic_account.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>