<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>View Accounts</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar">
    <span>Manage Accounts</span>
    <a href="index.php" class="nav-btn">Go to Dashboard</a>
</div>

<table>
    <tr>
        <th>Acc Number</th>
        <th>Customer Name</th>
        <th>Type</th>
        <th>Balance</th>
        <th>Actions</th>
    </tr>
    <?php
    $result = $conn->query("SELECT accounts.*, customers.name 
                            FROM accounts 
                            JOIN customers ON accounts.customer_id = customers.customer_id");
    while($row = $result->fetch_assoc()){
        $id = $row['account_number'];
        echo "<tr>
            <td>$id</td>
            <td>".$row['name']."</td>
            <td>".$row['account_type']."</td>
            <td>$".number_format($row['balance'], 2)."</td>
            <td>
                <a href='edit_account.php?id=$id' class='btn-edit'>Edit</a>
                <a href='delete_account.php?id=$id' class='btn-delete' onclick='return confirm(\"Are you sure?\")'>Delete</a>
            </td>
        </tr>";
    }
    ?>
</table>
</body>
</html>