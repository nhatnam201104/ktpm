<?php

class ProductRepository extends BaseRepository
{
    protected function fetchAll($condition = null, $sort = null, $limit = null)
    {
        global $conn;
        $product_sql ="";
        $products = array();
        if (($condition !== null && strpos( $condition,"BETWEEN") !== false)||($condition !== null && strpos( $condition,">=") !== false)) {
            $product_sql = "
                    WITH LatestEntry AS (
                        SELECT 
                            ed.product_id,
                            MIN(ec.enter_day) AS min_enter_day
                        FROM enter_coupon ec
                        JOIN entry_details ed ON ec.id = ed.entercoupon_id
                        WHERE ec.status = 0 and ed.p_inventory>0
                        GROUP BY ed.product_id
                    )
                    SELECT 
                        product.*,
                        (ed.enter_price * (1 +( ed.profit_margin/100))) - 
                        ((ed.enter_price * (1 +( ed.profit_margin/100))) * COALESCE(discount.discount_percentage, 0) / 100) AS price
                    FROM product
                    JOIN LatestEntry ON product.id = LatestEntry.product_id
                    JOIN category ON product.category_id = category.id
                    JOIN enter_coupon ec ON ec.enter_day = LatestEntry.min_enter_day
                    JOIN entry_details ed ON ec.id = ed.entercoupon_id AND ed.product_id = product.id
                    LEFT JOIN discount ON discount.id = product.discount_id
                    WHERE ed.p_inventory > 0 and product.deleted=0 and category.deleted=0
                ";
                if ($condition) {
                    $product_sql .= " HAVING $condition";
                }
        
                if ($sort) {
                    $product_sql .= " $sort";
                }
        
                if ($limit) {
                    $product_sql .= " $limit";
                }
        } else
        if ($sort !== null && strpos( $sort,"ORDER BY price") !== false) {
            $product_sql = "
                    WITH LatestEntry AS (
                        SELECT 
                            ed.product_id,
                            MIN(ec.enter_day) AS min_enter_day
                        FROM enter_coupon ec
                        JOIN entry_details ed ON ec.id = ed.entercoupon_id
                        WHERE ec.status = 0 and ed.p_inventory>0
                        GROUP BY ed.product_id
                    )
                    SELECT 
                        product.*,
                        (ed.enter_price * (1 +( ed.profit_margin/100))) - 
                        ((ed.enter_price * (1 +( ed.profit_margin/100))) * COALESCE(discount.discount_percentage, 0) / 100) AS price
                    FROM product
                    JOIN LatestEntry ON product.id = LatestEntry.product_id
                    JOIN category ON product.category_id = category.id
                    JOIN enter_coupon ec ON ec.enter_day = LatestEntry.min_enter_day
                    JOIN entry_details ed ON ec.id = ed.entercoupon_id AND ed.product_id = product.id
                    LEFT JOIN discount ON discount.id = product.discount_id
                    WHERE ed.p_inventory > 0 and product.deleted=0 and category.deleted=0
                ";
                if ($condition) {
                    $product_sql .= " HAVING $condition";
                }
        
                if ($sort) {
                    $product_sql .= " $sort";
                }
        
                if ($limit) {
                    $product_sql .= " $limit";
                }
        }
        else 
        {
            $product_sql = "SELECT product.* FROM product JOIN category ON product.category_id = category.id";
            $chuoiCon = explode(' AND ', $condition);
            $ketQua = array_map(function($chuoi) {
                return "product." . $chuoi;
            }, $chuoiCon);
            $ketQuaCuoi = implode(' AND ', $ketQua);
            if ($ketQuaCuoi) {
                $product_sql .= " WHERE $ketQuaCuoi and category.deleted=0";
            }
    
           
            if ($sort) {
                $product_sql .= " $sort";
            }
    
            if ($limit) {
                $product_sql .= " $limit";
            }

           
        }
        $result = $conn->query($product_sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $price_product = "WITH LatestEntry AS (
                        SELECT 
                            ed.product_id,
                            MIN(ec.enter_day) AS min_enter_day
                        FROM enter_coupon ec
                        JOIN entry_details ed ON ec.id = ed.entercoupon_id
                        WHERE ec.status = 0 and ed.p_inventory>0
                        GROUP BY ed.product_id
                    )
                    SELECT 
                        product.*,
                        ed.enter_price as enter_price,
                        (ed.enter_price * (1 +( ed.profit_margin/100))) - 
                        ((ed.enter_price * (1 +( ed.profit_margin/100))) * COALESCE(discount.discount_percentage, 0) / 100) AS price,
                        ed.p_inventory as p_inventory
                    FROM product
                    JOIN LatestEntry ON product.id = LatestEntry.product_id
                    JOIN category ON product.category_id = category.id
                    JOIN enter_coupon ec ON ec.enter_day = LatestEntry.min_enter_day
                    JOIN entry_details ed ON ec.id = ed.entercoupon_id AND ed.product_id = product.id
                    LEFT JOIN discount ON discount.id = product.discount_id
                    WHERE ed.p_inventory > 0 AND product.id=$row[id] and category.deleted=0";
                $data2 = $conn->query($price_product);
                $row1 = $data2->fetch_assoc();
                if (mysqli_num_rows($data2) == 0) {
                    $row1['enter_price'] = 0;
                    $row1['price'] = 0;
                    $row1['p_inventory']=0;
                }

                // echo ($price_product);

                $product = new Product(
                    $row["id"],
                    $row["discount_id"],
                    $row["name"],
                    $row1['price'],
                    $row["featured_image"],
                    $row1['p_inventory'],
                    $row["category_id"],
                    $row["brand_id"],
                    $row["created_date"],
                    $row["description"],
                    $row1['enter_price'],
                    $row["deleted"],
                    $row["updated_at"],
                    $row["featured"]
                );
                $products[] = $product;
            }
        }
        // echo json_encode($products);
        return $products;
    }

    function getAll()
    {
        return $this->getBy();
    }

    // Get products based on conditions and sorting
    function getBy($array_conds = array(), $array_sorts = array(), $page = null, $qty_per_page = null)
    {
        if ($page) {
            $page_index = $page - 1;
        }

        $temp = array();
        foreach ($array_conds as $column => $cond) {
            $type = $cond['type'];
            $val = $cond['val'];
            $str = "$column $type ";
            if (in_array($type, array("BETWEEN", "LIKE"))) {
                $str .= "$val";
            } else {
                $str .= "'$val'";
            }
            $temp[] = $str;
        }
        $condition = null;

        if (count($array_conds)) {
            $condition = implode(" AND ", $temp);
        }

        $temp = array();
        foreach ($array_sorts as $key => $sort) {
            $temp[] = "$key $sort";
        }
        $sort = null;

        if (count($array_sorts)) {
            $sort = "ORDER BY " . implode(" , ", $temp);
        }

        $limit = null;
        if ($qty_per_page) {
            $start = $page_index * $qty_per_page;
            $limit = "LIMIT $start, $qty_per_page";
        }

        return $this->fetchAll($condition, $sort, $limit);
    }

    // Find a product by its ID
    function find($id)
    {
        global $conn;
        $condition = "id = $id";
        $products = $this->fetchAll($condition);
        $product = current($products);
        return $product;
    }

    // Find a product by its barcode
    function findByBarcode($barcode)
    {
        global $conn;
        $condition = "barcode = '$barcode'";
        $products = $this->fetchAll($condition);
        $product = current($products);
        return $product;
    }

    // Save a new product to the database
    function save($data)
    {
        global $conn;
        $name = $data["name"];
        $featured_image = $data["featured_image"];
        $category_id = $data["category_id"];
        $brand_id = $data["brand_id"];
        $created_date = $data["created_date"];
        $description = $data["description"];
        $deleted = $data["deleted"];
        $updated_at = $data["updated_at"];
        $featured = $data["featured"];

        $sql = "INSERT INTO product (name, featured_image, category_id, brand_id, created_date, description, deleted, updated_at, featured) 
        VALUES ('$name', '$featured_image', $category_id, $brand_id, '$created_date', '$description', $deleted, '$updated_at', '$featured')";

        if ($conn->query($sql) === TRUE) {
            $last_id = $conn->insert_id;
            return $last_id;
        }
        $this->error =  "Error: " . $sql . PHP_EOL . $conn->error;
        return false;
    }

    // Update product information
    function update(Product $product)
    {
        global $conn;

        $id = $product->getId();
        $name = $product->getName();
        $price = $product->getPrice();
        $featured_image = $product->getFeaturedImage();
        $inventory_qty = $product->getInventoryQty();
        $category_id = $product->getCategoryId();
        $brand_id = $product->getBrandId();
        $created_date = $product->getCreatedDate();
        $description = $product->getDescription();
        $enter_price = $product->getEnterPrice();
        $deleted = $product->getDeleted();
        $updated_at = $product->getUpdatedAt();
        $featured = $product->getFeatured();

        $price_product = "SELECT enter_coupon.*, entry_details.p_inventory as p_inventory
        FROM enter_coupon 
        LEFT JOIN entry_details ON enter_coupon.id = entry_details.entercoupon_id 
        WHERE enter_coupon.enter_day = (
        SELECT MIN(ec.enter_day) 
        FROM enter_coupon ec
        LEFT JOIN entry_details ed ON ec.id = ed.entercoupon_id
        WHERE ec.status = 0
        AND ed.product_id = $id AND ed.p_inventory>0 
        ) 
        AND entry_details.p_inventory > 0 and entry_details.product_id=$id;";
        $data = $conn->query($price_product);
        $row = $data->fetch_assoc();
        $sql="
            UPDATE entry_details SET p_inventory=$row[p_inventory]-$inventory_qty WHERE entercoupon_id=$row[id] and product_id=$id
        ";
        if ($conn->query($sql) === TRUE) {
            return true;
        }
        $this->error =  "Error: " . $sql . PHP_EOL . $conn->error;
        return false;
    }

    // Delete a product from the database
    function delete(Product $product)
    {
        global $conn;
        $id = $product->getId();
        $sql = "DELETE FROM product WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            return true;
        }
        $this->error =  "Error: " . $sql . PHP_EOL . $conn->error;
        return false;
    }

    // Get products based on a specified pattern
    function getByPattern($pattern)
    {
        $condition = "name like '%$pattern%'";
        return $this->fetchAll($condition);
    }
}
