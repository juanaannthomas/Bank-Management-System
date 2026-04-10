<?php
include 'db_connect.php';

$message = "";

if(isset($_POST['withdraw'])){
    $account_number = $_POST['account_number'];
    $amount = $_POST['amount'];

    // 1. First, check if the account exists and get the current balance
    $stmt = $conn->prepare("SELECT balance FROM accounts WHERE account_number = ?");
    $stmt->bind_param("s", $account_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_balance = $row['balance'];

        // 2. Check for sufficient funds
        if($current_balance >= $amount) {
            // Deduct the money
            $update = $conn->prepare("UPDATE accounts SET balance = balance - ? WHERE account_number = ?");
            $update->bind_param("ds", $amount, $account_number);
            
            if($update->execute()) {
                // Optional: Record the transaction
                $conn->query("INSERT INTO transactions(account_number, type, amount) VALUES('$account_number', 'Withdraw', '$amount')");
                
                echo "<script>alert('Withdrawal Successful! New Balance: " . ($current_balance - $amount) . "'); window.location='index.php';</script>";
            }
        } else {
            $message = "<div style='color:red; margin-bottom:15px;'>❌ Insufficient Balance! Current: $" . number_format($current_balance, 2) . "</div>";
        }
    } else {
        $message = "<div style='color:red; margin-bottom:15px;'>❌ Invalid Account Number.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Withdraw Money | Bank System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <span>Withdraw Funds</span>
    <a href="index.php" class="nav-btn">Go to Dashboard</a>
</div>

<div class="container">
    <div class="card">
        <h2 style="margin-top:0; color:#2c3e50;">Withdraw Money</h2>
        
        <?php echo $message; ?>

        <form method="POST">
            <label style="font-weight:bold; display:block; margin-bottom:5px;">Account Number</label>
            <input type="text" name="account_number" placeholder="Enter 12-digit Account Number" required>

            <label style="font-weight:bold; display:block; margin-bottom:5px;">Amount to Withdraw ($)</label>
            <input type="number" step="0.01" name="amount" placeholder="0.00" min="1" required>

            <button type="submit" name="withdraw" style="background:#e67e22;">Confirm Withdrawal</button>
            
            <p style="font-size:12px; color:#7f8c8d; margin-top:15px; text-align:center;">
                Please verify the account number before proceeding.
            </p>
        </form>
    </div>
</div>

</body>
</html>