<?php
include 'db_connect.php';
if(isset($_POST['submit'])){
    $name=$_POST['name'];
    $phone=$_POST['phone'];
    $email=$_POST['email'];
    $address=$_POST['address'];
    $dob=$_POST['dob'];
    $gender=$_POST['gender'];
    $type=$_POST['type'];
    $balance=$_POST['balance'];

    $conn->query("INSERT INTO customers(name,phone,email,dob,gender) VALUES('$name','$phone','$email','$dob','$gender')");
    $customer_id=$conn->insert_id;
    $account_number=rand(100000000000,999999999999);
    $conn->query("INSERT INTO accounts(account_number,customer_id,account_type,balance) VALUES('$account_number','$customer_id','$type','$balance')");

    echo "<script>alert('Account Created'); window.location='index.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Account</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar">
    <span>Open New Account</span>
    <a href="index.php" class="nav-btn">Go to Dashboard</a>
</div>
<div class="container">
    <div class="card">
        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="text" name="phone" placeholder="Phone" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="date" name="dob" required>
            <select name="gender"><option>Male</option><option>Female</option></select>
            <select name="type"><option>Savings</option><option>Current</option></select>
            <input type="number" name="balance" placeholder="Initial Deposit" required>
            <button name="submit">Create Account</button>
        </form>
    </div>
</div>
</body>
</html>