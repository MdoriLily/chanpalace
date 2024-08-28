<nav class="navbar navbar-expand-lg fixed-top bg-white">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php" style="line-height:1em">จันทร์พาเลส<br><small class="text-primary fs-14">ส่วนผู้ดูแลระบบ</small></a>
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
                    <a class="nav-link" href="booking.php">รายการสั่งจอง</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="user.php">บัญชีผู้ใช้งาน</a>
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
                            <li><a class="dropdown-item" href="../index.php">ส่วนลูกค้า</a></li>
                            <li><a class="dropdown-item" href="change_password.php">เปลี่ยนรหัสผ่าน</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">ออกจากระบบ</a></li>
                        </ul>
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