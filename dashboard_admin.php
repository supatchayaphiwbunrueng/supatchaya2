<?php
// 1. รวมไฟล์เชื่อมต่อฐานข้อมูล (ซึ่งมีการเช็ค session_status อยู่ด้านในตามที่เราแก้กันไป)
include 'db.php';

// 2. ตรวจสอบความปลอดภัย: ต้องมี session และต้องมีสิทธิ์เป็น 'admin' เท่านั้น
if (!isset($_SESSION['uid']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | ระบบจัดการหลังบ้าน</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #f8f0ff;      /* ม่วงขาว */
            --primary-purple: #9b59b6; /* ม่วงหลัก */
            --dark-purple: #6a0dad;    /* ม่วงเข้ม */
            --light-purple: #e0c3fc;   /* ม่วงอ่อนมาก */
            --sidebar-color: #4b2c61;  /* ม่วงดำสำหรับเมนู */
        }

        * { margin:0; padding:0; box-sizing:border-box; }
        body { 
            font-family: 'Sarabun', sans-serif; 
            background: var(--bg-color); 
            display: flex; 
            min-height: 100vh; 
        }

        /* Sidebar สไตล์ Admin */
        .sidebar {
            width: 260px;
            background: var(--sidebar-color);
            color: white;
            padding: 25px 0;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }
        .sidebar .brand {
            padding: 0 25px 30px;
            font-size: 24px;
            font-weight: bold;
            color: var(--light-purple);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
            text-align: center;
        }
        .sidebar a { 
            color: #d1d1d1; 
            text-decoration: none; 
            padding: 15px 25px; 
            transition: 0.3s;
            display: block;
        }
        .sidebar a:hover { 
            background: rgba(255,255,255,0.1); 
            color: white; 
            padding-left: 35px;
        }
        .sidebar a.active { 
            background: var(--primary-purple); 
            color: white; 
            border-left: 5px solid var(--light-purple);
        }

        /* ส่วนเนื้อหาหลัก */
        .main-content {
            flex: 1;
            padding: 30px;
        }
        .top-nav {
            background: white;
            padding: 15px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .role-badge {
            background: #d4edda;
            color: #155724;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: bold;
        }

        /* กล่องเนื้อหา */
        .card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(155, 89, 182, 0.1);
        }
        .welcome-text h1 { color: var(--dark-purple); margin-bottom: 10px; }
        .welcome-text p { color: #666; font-size: 1.1rem; }

        .btn-logout {
            display: inline-block;
            margin-top: 25px;
            background: #e74c3c;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-logout:hover { 
            background: #c0392b; 
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        @media (max-width: 768px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; padding: 10px 0; }
        }
    </style>
</head>
<body>

    <nav class="sidebar">
        <div class="brand">Admin Panel</div>
        <a href="#" class="active">🏠 แดชบอร์ด</a>
        <a href="#">👥 จัดการสมาชิก</a>
        <a href="#">📊 รายงานประจำวัน</a>
        <a href="#">⚙️ ตั้งค่าระบบ</a>
        <div style="margin-top: auto; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
            <a href="logout.php" onclick="return confirm('คุณต้องการออกจากระบบใช่หรือไม่?')">Logout</a>
        </div>
    </nav>

    <main class="main-content">
        <header class="top-nav">
            <div>
                <span class="role-badge">สิทธิ์การใช้งาน: ผู้ดูแลระบบ</span>
            </div>
            <div style="color: #555;">
                ยินดีต้อนรับคุณ <strong>Administrator</strong>
            </div>
        </header>

        <section class="card">
            <div class="welcome-text">
                <h1>ยินดีต้อนรับสู่ระบบจัดการหลังบ้าน</h1>
                <p>คุณสามารถจัดการข้อมูลเว็บไซต์และตรวจสอบกิจกรรมต่างๆ ได้จากเมนูด้านซ้ายมือ</p>
                <hr style="margin: 25px 0; border: 0; border-top: 1px solid #eee;">
                
                <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <div style="flex: 1; background: #f3e5f5; padding: 20px; border-radius: 10px; text-align: center; border: 1px solid #e1bee7;">
                        <h3 style="color: #6a0dad;">ผู้ใช้งานทั้งหมด</h3>
                        <p style="font-size: 24px; font-weight: bold;">150</p>
                    </div>
                    <div style="flex: 1; background: #f3e5f5; padding: 20px; border-radius: 10px; text-align: center; border: 1px solid #e1bee7;">
                        <h3 style="color: #6a0dad;">กำลังออนไลน์</h3>
                        <p style="font-size: 24px; font-weight: bold;">12</p>
                    </div>
                </div>

                <a href="logout.php" class="btn-logout" onclick="return confirm('ยืนยันการออกจากระบบ?')">ออกจากระบบ</a>
            </div>
        </section>
    </main>

</body>
</html>