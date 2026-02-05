<?php
// 1. เชื่อมต่อฐานข้อมูลก่อน (ข้างใน db.php มีการเช็ค session อยู่แล้ว)
include 'db.php'; 

// 2. ตรวจสอบสถานะ Session อีกชั้นเพื่อความชัวร์
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$message = '';

if (isset($_POST['login'])) {
    $u = isset($_POST['username']) ? trim($_POST['username']) : '';
    $p = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($u) || empty($p)) {
        $message = "กรุณากรอก Username และ Password ให้ครบ";
    } else {
        // เตรียมคำสั่ง SQL เพื่อป้องกัน SQL Injection
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $u);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        // ตรวจสอบรหัสผ่าน
        if ($user && password_verify($p, $user['password'])) {
            $_SESSION['uid'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: dashboard_admin.php");
                exit();
            } else {
                header("Location: dashboard_user.php");
                exit();
            }
        } else {
            $message = "Username หรือ Password ไม่ถูกต้อง";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        html, body { height:100%; font-family: 'Sarabun', Arial, sans-serif; background: #f3e6ff; }
        body { display:flex; justify-content:center; align-items:center; min-height:100vh; }
        .container {
            background: #fff;
            padding: 40px 40px;
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(128,0,128,0.15);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h2 { color: #6a0dad; margin-bottom: 25px; }
        
        /* สไตล์ส่วนสมัครสมาชิกด้านบน */
        .register-link {
            display: block;
            margin-bottom: 20px;
            font-size: 14px;
            color: #7f8c8d;
        }
        .register-link a {
            color: #9b59b6;
            text-decoration: none;
            font-weight: bold;
            border-bottom: 1px solid transparent;
            transition: 0.3s;
        }
        .register-link a:hover {
            border-bottom: 1px solid #9b59b6;
        }

        input { width:100%; padding:12px; margin-bottom:20px; border-radius:8px; border:1px solid #ddd; font-size:16px; outline: none; transition: 0.3s; }
        input:focus { border-color: #9b59b6; box-shadow: 0 0 8px rgba(155, 89, 182, 0.2); }
        
        button {
            width:100%; padding:12px; border-radius:8px; border:none;
            background: #9b59b6; color:#fff; font-weight:bold; cursor:pointer;
            font-size:16px; transition: background 0.3s;
        }
        button:hover { background: #8e44ad; }
        
        .message { 
            background: #ffe6e6; 
            color: #cc0000; 
            padding: 10px; 
            border-radius: 5px; 
            margin-bottom: 20px; 
            font-size: 14px;
            border: 1px solid #ffcccc;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-link">
            ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิกที่นี่</a>
        </div>

        <h2>เข้าสู่ระบบ</h2>
        
        <?php if(!empty($message)): ?>
            <div class='message'><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <input type="text" name="username" placeholder="Username" required 
                   value="<?php echo isset($u) ? htmlspecialchars($u) : ''; ?>">
            
            <input type="password" name="password" placeholder="Password" required>
            
            <button name="login" type="submit">เข้าสู่ระบบ</button>
        </form>
    </div>
</body>
</html>