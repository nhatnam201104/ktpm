<?php require "layout/header.php" ?>
<main id="maincontent" class="page-main">
    <div class="container">
        <div class="row">
            <div class="col-xs-9">
                <ol class="breadcrumb">
                    <li><a href="/" target="_self">Trang chủ</a></li>
                    <li><span>/</span></li>
                    <li class="active"><span>Tài khoản</span></li>
                </ol>
            </div>
            <div class="clearfix"></div>
            <?php require "view/customer/sidebar.php" ?>
            <div class="col-md-9 order">
                <div class="row">
                    <div class="col-xs-6">
                        <h4 class="home-title">Đơn hàng của tôi</h4>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <!-- Mỗi đơn hàng -->
                        <?php foreach ($orders as $order): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Đơn hàng <a
                                        href="index.php?c=order&a=show&id=<?= $order->getId() ?>">#<?= $order->getId() ?></a>
                                </h5>
                                <span class="date">
                                    Đặt hàng ngày <?= $order->getCreatedDate() ?> </span>
                                <hr>
                                <?php foreach ($order->getOrderItems() as $orderItem):
                                        $product = $orderItem->getProduct();
                                        ?>
                                <div class="row">
                                    <div class="col-md-2">
                                        <img src="<?= $product->getFeaturedImage() ?>" alt="" class="img-responsive">
                                    </div>
                                    <div class="col-md-3">
                                        <a class="product-name"
                                            href="index.php?c=product&a=show&id=<?= $product->getId() ?>"><?= $product->getName() ?></a>
                                    </div>
                                    <div class="col-md-2">
                                        Số lượng: <?= $orderItem->getQty() ?>
                                    </div>

                                </div>
                                <?php endforeach ?>

                                <?php if ($order->getStatus()->getDescription() == "Chờ xác nhận"): ?>
                                <div class="col-md-3">
                                    Chờ xác nhận đơn hàng
                                    <button id="cancelOrder" onclick="cancelOrder(<?= $order->getId() ?>)">Hủy đơn
                                        hàng</button>
                                </div>
                                <?php elseif ($order->getStatus()->getDescription() == "Đã xác nhận"): ?>
                                <div class="col-md-3">
                                    <?= $order->getDeliveredDate() ?>: Dự kiến giao hàng
                                </div>
                                <?php elseif ($order->getStatus()->getDescription() == "Đã giao"): ?>
                                <div class="col-md-3">
                                    Đơn hàng đã giao thành công
                                </div>
                                <?php elseif ($order->getStatus()->getDescription() == "Hủy"): ?>
                                <div class="col-md-3">
                                    Đơn hàng đã bị hủy
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require "layout/footer.php" ?>