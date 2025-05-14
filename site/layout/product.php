<div style="position: relative; height:360px; width: 100%;" class="product-container text-center box-sd">
    <div style="height: 260px; display: flex; padding-top: 20px;" class="image">
    <img class="img-responsive" src="../admin/<?=$product->getFeaturedImage() ?>" alt="" style="margin: auto; width: auto; max-height: 245px;">
    </div>
    <div class="product-meta">
        <h5 style="min-height: 32px; display: flex;" class="name">
            <a style="margin: auto; padding: 0 12px;" class="product-name" href="index.php?c=product&a=show&id=<?=$product->getId()?>"
                title="<?=$product->getName()?>"><?=$product->getName()?></a>
        </h5>
        <div class="product-item-price">
            <?php if ($product->getPrice() != $product->getEnterPrice()): ?>
                <!-- <span class="product-item-regular"><?=number_format($product->getEnterPrice())?>₫</span> -->
                <div class="box box-discount">
                    <img class="icon1" src="../upload/icon percent.png" alt="">
                    <p style="margin-bottom: 0;padding-right: 8px;">Giảm <?=$product->getDiscountPercentage()?>%</p>
                </div>
                <?php endif ?>
                <span class="product-item-discount"><?=number_format($product->getPrice())==0 ? "không có dữ liệu" : number_format($product->getPrice())?>₫</span>
                <?php if ($product->getInventoryQty() > 0): ?>
                    <div class="box box-stock">
                        <img class="icon1" src="../upload/iconstock.png" alt="">
                        <p style="margin-bottom: 0;padding-right: 8px;">Còn <?php echo $product->getInventoryQty() ?> sản phẩm</p>
                    </div>
                <?php else: ?>
                    <div class="box box-outstock">
                        <img class="icon1" src="../upload/iconoutstock.png" alt="">
                        <p style="margin-bottom: 0;padding-right: 8px;">Hết hàng</p>
                    </div>
            <?php endif ?>
        </div>
    </div>
    <div class="button-product-action clearfix">
        <div class="cart icon">
            <?php if ($product->getInventoryQty() > 0): ?>
            <a class="btn btn-outline-inverse buy" product-id="<?=$product->getId()?>" href="javascript:void(0)"
                title="Thêm vào giỏ">
                Thêm vào giỏ <i class="fa fa-shopping-cart"></i>
            </a>
            <?php else: ?>
                <a href="javascript:void()" class="btn btn-outline-inverse disabled" >Hết hàng</a>
                
            <?php endif ?>
        </div>
        <div class="quickview icon">
            <a class="btn btn-outline-inverse" href="index.php?c=product&a=show&id=<?=$product->getId()?>"
                title="Xem nhanh">
                Xem chi tiết <i class="fa fa-eye"></i>
            </a>
        </div>
    </div>
</div>