<?php
include 'db_connect.php';

// 1. Check if ID is provided
if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: view_accounts.php");
    exit;
}

$id = $_GET['id'];

// 2. Fetch data (Including Email from customers table)
$stmt = $conn->prepare("SELECT accounts.*, customers.name, customers.phone, customers.email 
                        FROM accounts 
                        JOIN customers ON accounts.customer_id = customers.customer_id 
                        WHERE accounts.account_number = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if(!$row) {
    die("<script>alert('Account not found'); window.location='view_accounts.php';</script>");
}

// 3. Handle Update
if(isset($_POST['update'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $balance = $_POST['balance'];

    // Update Customer details (Name, Phone, Email)
    $u1 = $conn->prepare("UPDATE customers SET name=?, phone=?, email=? WHERE customer_id=?");
    $u1->bind_param("sssi", $name, $phone, $email, $row['customer_id']);
    
    // Update Account details (Balance)
    $u2 = $conn->prepare("UPDATE accounts SET balance=? WHERE account_number=?");
    $u2->bind_param("ds", $balance, $id);

    if($u1->execute() && $u2->execute()) {
        echo "<script>alert('Account Updated Successfully!'); window.location='view_accounts.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Account | Bank System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Specific tweaks for a better Edit Page look */
        .edit-label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c3e50;
            font-size: 14px;
        }
        .info-text {
            font-size: 12px;
            color: #7f8c8d;
            margin-bottom: 15px;
            display: block;
        }
        .card h2 {
            margin-top: 0;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <span>Modify Account Details</span>
    <a href="index.php" class="nav-btn">Go to Dashboard</a>
</div>

<div class="container">
    <div class="card">
        <h2>Edit Account #<?php echo $id; ?></h2>
        
        <form method="POST">
            <span class="edit-label">Full Name</span>
            <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>

            <span class="edit-label">Email Address</span>
            <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>

            <span class="edit-label">Phone Number</span>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>" required>

            <span class="edit-label">Current Balance ($)</span>
            <input type="number" step="0.01" name="balance" value="<?php echo $row['balance']; ?>" required>
            <span class="info-text">Note: Adjusting balance manually should be done with caution.</span>

            <button type="submit" name="update" style="background: #3498db;">Update Account Information</button>
            <a href="view_accounts.php" style="display:block; text-align:center; margin-top:15px; color:#7f8c8d; text-decoration:none; font-size:14px;">Cancel and Go Back</a>
        </form>
    </div>
</div>

</body>
</html>