<?php 
class CartController {
	protected $cartStorage;
	function __construct() {
		$this->cartStorage = new CartStorage();
	}
	function display() {
		$cart = $this->cartStorage->fetch();
		echo json_encode($cart->convertToArray());
	}

	function add() {
		$product_id = $_GET["product_id"];
		$qty = $_GET["qty"];
		$cart = $this->cartStorage->fetch();
		global $conn;
		$sql="WITH LatestEntry AS (
            SELECT 
                ed.product_id,
                MIN(ec.enter_day) AS min_enter_day
            FROM enter_coupon ec
            JOIN entry_details ed ON ec.id = ed.entercoupon_id
            WHERE ec.status = 0 AND ed.p_inventory > 0 and ed.product_id=$product_id
            GROUP BY ed.product_id
        )
        SELECT 
			ec.id as batch_id,
        	product.id as product_id,
            ed.p_inventory as p_inventory,
            (ed.enter_price * (1 + (ed.profit_margin / 100))) - 
            ((ed.enter_price * (1 + (ed.profit_margin / 100))) * COALESCE(discount.discount_percentage, 0) / 100) AS price
        FROM product
        JOIN LatestEntry ON product.id = LatestEntry.product_id
        JOIN enter_coupon ec ON ec.enter_day = LatestEntry.min_enter_day
        JOIN entry_details ed ON ec.id = ed.entercoupon_id AND ed.product_id = product.id
        LEFT JOIN discount ON discount.id = product.discount_id
        WHERE ed.p_inventory > 0";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		if($qty> $row['p_inventory'])
		{
			exit;
		}
		$cart->addProduct($product_id, $qty, $row['p_inventory'], $row['batch_id']);

		$this->cartStorage->store($cart);
		//Đổi đối tượng -> chuỗi
		//Đổi tượng thành array, sau đó từ array -> chuỗi
		
		echo json_encode($cart->convertToArray());
	}

	function update() {
		$product_id = $_GET["product_id"];
		$qty = $_GET["qty"];
		$cart = $this->cartStorage->fetch();
		$alert="";
		global $conn;
		$sql="WITH LatestEntry AS (
            SELECT 
                ed.product_id,
                MIN(ec.enter_day) AS min_enter_day
            FROM enter_coupon ec
            JOIN entry_details ed ON ec.id = ed.entercoupon_id
            WHERE ec.status = 0 AND ed.p_inventory > 0 and ed.product_id=$product_id
            GROUP BY ed.product_id
        )
        SELECT 
			ec.id as batch_id,
        	product.id as product_id,
            ed.p_inventory as p_inventory,
            (ed.enter_price * (1 + (ed.profit_margin / 100))) - 
            ((ed.enter_price * (1 + (ed.profit_margin / 100))) * COALESCE(discount.discount_percentage, 0) / 100) AS price
        FROM product
        JOIN LatestEntry ON product.id = LatestEntry.product_id
        JOIN enter_coupon ec ON ec.enter_day = LatestEntry.min_enter_day
        JOIN entry_details ed ON ec.id = ed.entercoupon_id AND ed.product_id = product.id
        LEFT JOIN discount ON discount.id = product.discount_id
        WHERE ed.p_inventory > 0";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		if($qty> $row['p_inventory'])
		{
			exit;
		}
		$cart->deleteProduct($product_id);
		$cart->addProduct($product_id, $qty, $row['p_inventory'], $row['batch_id']);

		$this->cartStorage->store($cart);

		echo json_encode($cart->convertToArray());
	}

	function delete() {
		$product_id = $_GET["product_id"];
		$cart = $this->cartStorage->fetch();

		$cart->deleteProduct($product_id);

		$this->cartStorage->store($cart);

		echo json_encode($cart->convertToArray());
	}
	
}