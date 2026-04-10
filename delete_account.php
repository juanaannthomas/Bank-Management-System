<?php
include 'db_connect.php';

if(isset($_GET['id']) && !empty($_GET['id'])) {
    $account_number = $_GET['id'];

    // 1. Check if there are any transactions for this account
    $check_stmt = $conn->prepare("SELECT COUNT(*) as total FROM transactions WHERE account_number = ?");
    $check_stmt->bind_param("s", $account_number);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $data = $result->fetch_assoc();

    if($data['total'] > 0) {
        // 2. If transactions exist, DENY the deletion
        echo "<script>
                alert('❌ SECURITY ALERT: This account has " . $data['total'] . " recorded transactions. For audit purposes, accounts with history cannot be deleted.'); 
                window.location='view_accounts.php';
              </script>";
    } else {
        // 3. If NO transactions exist, proceed with deletion
        $delete_stmt = $conn->prepare("DELETE FROM accounts WHERE account_number = ?");
        $delete_stmt->bind_param("s", $account_number);
        
        if($delete_stmt->execute()) {
            echo "<script>alert('✅ Success: Empty account removed.'); window.location='view_accounts.php';</script>";
        } else {
            echo "<script>alert('Error: Could not process request.'); window.location='view_accounts.php';</script>";
        }
    }
} else {
    header("Location: view_accounts.php");
}
?>