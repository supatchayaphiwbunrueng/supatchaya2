<?php
// รวมไฟล์เชื่อมต่อฐานข้อมูล ซึ่งมีการสั่ง session_start() ไว้แล้ว
include 'db.php';

// ตรวจสอบว่าได้ Login หรือยัง และเป็น Role 'user' จริงหรือไม่
if (!isset($_SESSION['uid']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Panel - ระบบสมาชิก</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { 
            font-family: 'Sarabun', sans-serif; 
            background: #f3e6ff; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
        }
        .panel-box {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
            width: 90%;
            max-width: 500px;
        }
        h2 { color: #6a0dad; margin-bottom: 20px; }
        p { color: #555; margin-bottom: 30px; font-size: 18px; }
        .btn-logout {
            display: inline-block;
            padding: 10px 25px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
        }
        .btn-logout:hover { background: #c0392b; }
    </style>
</head>
<body>

    <div class="panel-box">
        <h2>User Panel</h2>
        <p>ยินดีต้อนรับคุณเข้าสู่ระบบ!</p>
        <hr style="margin-bottom: 20px; border: 0; border-top: 1px solid #eee;">
        <a href="logout.php" class="btn-logout" onclick="return confirm('ยืนยันการออกจากระบบ?')">ออกจากระบบ</a>
    </div>

</body>
</html>