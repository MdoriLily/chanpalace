<nav class="navbar navbar-expand-lg fixed-top bg-white">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">จันทร์พาเลส</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="news.php">ประชาสัมพันธ์</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="room.php">ห้องพัก</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">ติดต่อเรา</a>
                </li>
            </ul>
            <?php
            if (isset($_SESSION['login_user'])) {
                $login_query = $mysqli->query("select * from user where user_id='{$_SESSION['login_user']}'");
                $login = $login_query->fetch_array();
                ?>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            คุณ <?php echo $login['name'];?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="user_info.php">ข้อมูลผู้ใช้งาน</a></li>
                            <?php
                            if ($login['level'] == "admin") {
                                ?>
                                <li><a class="dropdown-item" href="admin/index.php">ส่วนผู้ดูแลระบบ</a></li>
                                <?php
                            }
                            ?>
                            <li><a class="dropdown-item" href="change_password.php">เปลี่ยนรหัสผ่าน</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">ออกจากระบบ</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            สถานะจอง
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="booking_register.php">ห้องพักที่เลือกไว้</a></li>
                            <li><a class="dropdown-item" href="booking.php">รายการสั่งจอง</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="prebooking.php" class="btn btn-primary px-3 rounded-pill">จองห้องพัก</a>
                    </li>
                </ul>
                <?php
            }
            else {
                ?>
                <div class="ms-auto mb-2 mb-lg-0">
                    <a href="login.php" class="btn btn-primary rounded-pill"><i class="fa-solid fa-arrow-right-to-bracket me-2"></i>เข้าสู่ระบบ</a>
                    <a href="register.php" class="btn btn-primary rounded-pill">สมัครสมาชิก</a>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</nav>