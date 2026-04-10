<?php
include 'db_connect.php';

$message = "";

if(isset($_POST['deposit'])){
    $account_number = $_POST['account_number'];
    $amount = $_POST['amount'];

    // 1. Check if the account exists first
    $check_stmt = $conn->prepare("SELECT balance FROM accounts WHERE account_number = ?");
    $check_stmt->bind_param("s", $account_number);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if($result->num_rows > 0) {
        // 2. Account exists, perform the update
        $update_stmt = $conn->prepare("UPDATE accounts SET balance = balance + ? WHERE account_number = ?");
        $update_stmt->bind_param("ds", $amount, $account_number);
        
        if($update_stmt->execute()){
            // 3. Optional: Record the transaction in the history table
            $conn->query("INSERT INTO transactions(account_number, type, amount) VALUES('$account_number', 'Deposit', '$amount')");
            
            echo "<script>alert('Deposit Successful!'); window.location='index.php';</script>";
        } else {
            $message = "<div style='color:red; margin-bottom:15px;'>❌ System Error. Please try again.</div>";
        }
    } else {
        $message = "<div style='color:red; margin-bottom:15px;'>❌ Account Number not found.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Deposit Money | Bank System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <span>Deposit Funds</span>
    <a href="index.php" class="nav-btn">Go to Dashboard</a>
</div>

<div class="container">
    <div class="card">
        <h2 style="margin-top:0; color:#2c3e50;">Deposit Money</h2>
        
        <?php echo $message; ?>

        <form method="POST">
            <label style="font-weight:bold; display:block; margin-bottom:5px;">Account Number</label>
            <input type="text" name="account_number" placeholder="Enter 12-digit Account Number" required>

            <label style="font-weight:bold; display:block; margin-bottom:5px;">Amount to Deposit ($)</label>
            <input type="number" step="0.01" name="amount" placeholder="0.00" min="1" required>

            <button type="submit" name="deposit" style="background:#27ae60;">Submit Deposit</button>
            
            <p style="font-size:12px; color:#7f8c8d; margin-top:15px; text-align:center;">
                The funds will be available immediately after processing.
            </p>
        </form>
    </div>
</div>

</body>
</html>