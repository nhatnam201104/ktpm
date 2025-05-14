<aside class="col-md-3">
    <div class="inner-aside">
        <div class="category">
            <h5>Danh mục sản phẩm</h5>
            <ul>
                <li class="<?=empty($category_id) ? "active" : ""?>">
                    <a href="index.php?c=product" title="Tất cả sản phẩm" target="_self">Tất cả sản phẩm
                    </a>
                </li>
                <?php foreach ($categories as $category): ?>
                <li class="<?= !empty($category_id) && $category_id==$category->getId() ? "active" : ""?>">
                    <a href="index.php?c=product&category_id=<?=$category->getId()?>" title="<?=$category->getName()?>"
                        target="_self"><?=$category->getName()?></a>
                </li>
                <?php endforeach ?>
            </ul>
        </div>
        <div class="price-range">
            <h5>Khoảng giá</h5>
            <ul>
                <li>
                    <label for="filter-less-100">
                        <input type="radio" id="filter-less-100" name="filter-price" value="0-10000000"
                            <?=!empty($price_range) && $price_range == "0-10000000" ? "checked" : ""?>>
                        <i class="fa"></i>
                        Giá dưới 10.000.000đ
                    </label>
                </li>
                <li>
                    <label for="filter-100-200">
                        <input type="radio" id="filter-100-200" name="filter-price" value="10000000-20000000"
                            <?=!empty($price_range) &&  $price_range == "10000000-20000000" ? "checked" : ""?>>
                        <i class="fa"></i>
                        10.000.000đ - 20.000.000đ
                    </label>
                </li>
                <li>
                    <label for="filter-200-300">
                        <input type="radio" id="filter-200-300" name="filter-price" value="20000000-30000000"
                            <?=!empty($price_range) &&  $price_range == "20000000-30000000" ? "checked" : ""?>>
                        <i class="fa"></i>
                        20.000.000đ - 30.000.000đ
                    </label>
                </li>
                <li>
                    <label for="filter-300-500">
                        <input type="radio" id="filter-300-500" name="filter-price" value="30000000-50000000"
                            <?=!empty($price_range) &&  $price_range == "30000000-50000000" ? "checked" : ""?>>
                        <i class="fa"></i>
                        30.000.000đ - 50.000.000đ
                    </label>
                </li>
                
                <li>
                    <label for="filter-greater-500">
                        <input type="radio" id="filter-greater-500" name="filter-price" value="50000000-greater"
                            <?=!empty($price_range) &&  $price_range == "50000000-greater" ? "checked" : ""?>>
                        <i class="fa"></i>
                        Giá trên 50.000.000đ
                    </label>
                </li>
            </ul>
        </div>
    </div>
</aside>