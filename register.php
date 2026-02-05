<?php
include 'db.php'; // เชื่อมต่อฐานข้อมูล

$message = '';

if(isset($_POST['register'])){
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // ตรวจสอบค่าที่กรอก
    if(empty($fullname) || empty($username) || empty($password)){
        $message = "กรุณากรอกข้อมูลให้ครบทุกช่อง";
    } else {
        // ตรวจสอบว่าชื่อผู้ใช้งานซ้ำหรือไม่
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows > 0){
            $message = "ชื่อผู้ใช้นี้มีคนใช้แล้ว";
        } else {
            $stmt->close();

            // เข้ารหัสรหัสผ่าน
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // ใช้ prepared statement เพื่อเพิ่มข้อมูล
            $stmt = $conn->prepare("INSERT INTO users(fullname, username, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $fullname, $username, $hashed_password, $role);

            if($stmt->execute()){
                $message = "สมัครสมาชิกสำเร็จ! <a href='login.php'>เข้าสู่ระบบ</a>";
            } else {
                $message = "เกิดข้อผิดพลาด: กรุณาลองใหม่อีกครั้ง";
            }

            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สมัครสมาชิก</title>
    <style>
        /* Reset margin & padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: Arial, sans-serif;
            background: #f3e6ff; /* สีม่วงอ่อน */
        }

        /* จัด container ให้อยู่กลางหน้าจอ */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: #fff;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(128,0,128,0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            color: #6a0dad; /* สีม่วงเข้ม */
            margin-bottom: 25px;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: none;
            background: #9b59b6;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }

        button:hover {
            background: #8e44ad;
        }

        .message {
            color: red;
            margin-bottom: 15px;
        }

        .message a {
            color: #6a0dad;
            text-decoration: none;
            font-weight: bold;
        }

        .message a:hover {
            text-decoration: underline;
        }

        /* Responsive สำหรับมือถือ */
        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>สมัครสมาชิก</h2>
        <?php if($message) { echo "<div class='message'>$message</div>"; } ?>
        <form method="post">
            <input type="text" name="fullname" placeholder="ชื่อ-สกุล" value="<?= isset($fullname) ? htmlspecialchars($fullname) : '' ?>">
            <input type="text" name="username" placeholder="Username" value="<?= isset($username) ? htmlspecialchars($username) : '' ?>">
            <input type="password" name="password" placeholder="Password">
            <select name="role">
                <option value="user" <?= (isset($role) && $role == 'user') ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= (isset($role) && $role == 'admin') ? 'selected' : '' ?>>Admin</option>
            </select>
            <button name="register" type="submit">สมัครสมาชิก</button>
        </form>
    </div>
</body>
</html>
