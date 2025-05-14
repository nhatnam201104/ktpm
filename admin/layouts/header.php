    <?php
    session_start();
    require_once($baseUrl . '../utils/utility.php');
    require_once($baseUrl . '../database/dbhelper.php');
    $user = getUserToken();

    if ($user == null) {
        header('Location:' . $baseUrl . 'authen/login.php');
        die();
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin</title>
        <link rel="stylesheet" type="text/css" href="../../assets/css//dashboard.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <!-- Option 1: Include in HTML -->

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
        <!-- jquery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <!-- SweetAlert2 CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

        <!-- SweetAlert2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js" integrity="sha512-2rNj2KJ+D8s1ceNasTIex6z4HWyOnEYLVC3FigGOmyQCZc2eBXKgOxQmo3oKLHyfcj53uz4QMsRCWNbLd32Q1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <script src="../../assets/js/coupons.js"></script>
        <script src="../../assets/js/supplier.js"></script>
        <script src="../../assets/js/enter_coupon.js"></script>
        <script src="../../assets/js/order.js"></script>
        <script src="../../assets/js/statistics.js"></script>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
        <!-- Chart -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
        </script>
        <style>
            body,
            html {
                font-family: "Space Grotesk", sans-serif;
                height: 100%;
                margin: 0;
                padding: 0;
                font-size: 16px;
            }



            main {
                flex: 1;
                padding: 20px;
                /* Thêm padding nếu cần */
            }

            .alert-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 999;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s, visibility 0.3s;
            }

            .alert-dialog {
                background-color: white;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
                max-width: 400px;
                width: 90%;
            }

            .alert-dialog h2 {
                margin-top: 0;
            }

            .alert-dialog button {
                margin-top: 10px;
            }
        </style>

    </head>

    <body>
        <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Ban Hang </a>

            <ul class="navbar-nav px-3">
                <li class="nav-item text-nowrap">
                    <a class="nav-link" href="<?= $baseUrl ?>authen/logout.php">Thoát</a>
                </li>
            </ul>
        </nav>

        </nav>
        <div class="">
            <div class="row">
                <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                    <div class=" sidebar-sticky">
                        <ul class="nav flex-column">
                            <li class="img-fluid  ">

                                <img src="https://i.ibb.co/SNJSj9m/00f2ce38-42bd-46a6-a699-e1f9a0b70f97.jpg" alt="Logo" style=" width: 230px; height: 180px;  margin-right: 10px; margin-bottom:20px;object-fit: cover;" class="mr-5  rounded-circle">

                                </a>
                            </li>

                            <?php
                            switch ($_SESSION["user"]["role_id"]) {
                                case 1:
                                    echo '
                                         <li class="nav-item">
                                            <a class="nav-item nav-link" href="' . $baseUrl . 'user/user_index.php">
                                                <i class="bi bi-person-circle"></i>
                                                <span> Quản lý tài khoản </span>
                                            </a>
                                        </li>';
                                    break;
                                case 2:
                                    echo '
                                        <li class="nav-item">
                                            <a class="nav-item nav-link" href="' . $baseUrl . 'chart/chart_index.php">
                                                <i class="bi bi-kanban"></i>
                                                <span> Thống kê</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-item nav-link" href="' . $baseUrl . 'user/user_index.php">
                                                <i class="bi bi-person-circle"></i>
                                                <span> Quản lý tài khoản </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-item nav-link" href="' . $baseUrl . 'feedback/feedback_index.php">
                                                <i class="bi bi-chat-dots"></i>
                                                <span> Quản lý bình luận </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-item nav-link" href="' . $baseUrl . 'order/order_index.php">
                                                <i class="bi bi-journal"></i>
                                                <span>Quản lý đơn hàng</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-item nav-link" href="' . $baseUrl . 'category/category_index.php">
                                                <i class="bi bi-ui-checks-grid"></i>
                                                <span>Quản lý danh mục</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-item nav-link" href="' . $baseUrl . 'product/product_index.php">
                                                <i class="bi bi-handbag"></i>
                                                <span> Quản lý sản phẩm </span>
                                            </a>
                                        </li>
                                        <li class="nav-item ">
                                            <a class="nav-item nav-link" href="' . $baseUrl . 'discount/index_dis.php">
                                                <i class="bi bi-cash"></i>
                                                <span> Quản lý khuyến mãi </span>
                                            </a>
                                        </li>
                                        <li class="nav-item ">
                                            <a class="nav-item nav-link" href="' . $baseUrl . 'supplier/index_sup.php">
                                                <i class="bi bi-people-fill"></i>
                                                <span> Quản lý nhà cung cấp </span>
                                            </a>
                                        </li>
                                        <li class="nav-item ">
                                            <a class="nav-item nav-link" href="' . $baseUrl . 'enter_coupon/index_cou.php">
                                                <i class="bi bi-cart-check"></i>
                                                <span> Quản lý phiếu nhập </span>
                                            </a>
                                        </li>
                                        ';
                                    break;
                                case 3:
                                    echo '
                                        <li class="nav-item">
                                            <a class="nav-item nav-link" href="' . $baseUrl . 'category/category_index.php">
                                                <i class="bi bi-ui-checks-grid"></i>
                                                <span>Quản lý danh mục</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-item nav-link" href="' . $baseUrl . 'product/product_index.php">
                                                <i class="bi bi-handbag"></i>
                                                <span> Quản lý sản phẩm </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-item nav-link" href="' . $baseUrl . 'category/category_index.php">
                                                <i class="bi bi-ui-checks-grid"></i>
                                                <span>Quản lý danh mục</span>
                                            </a>
                                        </li>
                                        <li class="nav-item ">
                                            <a class="nav-item nav-link" href="' . $baseUrl . 'discount/index_dis.php">
                                                <i class="bi bi-cash"></i>
                                                <span> Quản lý khuyến mãi </span>
                                            </a>
                                        </li>
                                        <li class="nav-item ">
                                            <a class="nav-item nav-link" href="' . $baseUrl . 'supplier/index_sup.php">
                                                <i class="bi bi-people-fill"></i>
                                                <span> Quản lý nhà cung cấp </span>
                                            </a>
                                        </li>
                                        <li class="nav-item ">
                                            <a class="nav-item nav-link" href="' . $baseUrl . 'enter_coupon/index_cou.php">
                                                <i class="bi bi-cart-check"></i>
                                                <span> Quản lý phiếu nhập </span>
                                            </a>
                                        </li>
                                        ';
                                        
                                    break;
                                case 4:
                                    echo '
                                        <li class="nav-item">
                                            <a class="nav-item nav-link" href="' . $baseUrl . 'customer/user_index.php">
                                                <i class="bi bi-person-circle"></i>
                                                <span> Quản lý khách hàng </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-item nav-link" href="' . $baseUrl . 'order/order_index.php">
                                                <i class="bi bi-journal"></i>
                                                <span>Quản lý đơn hàng</span>
                                            </a>
                                        </li>';
                                    break;
                            }
                            ?>
                        </ul>
                    </div>
                </nav>
                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-5 ">