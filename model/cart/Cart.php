<?php 
class Cart
{
	protected $items;
	protected $total_price;
	protected $total_product_number;

	function __construct($items = array(), $total_price = 0, $total_product_number = 0){
		$this->items = $items;
		$this->total_price = $total_price;
		$this->total_product_number = $total_product_number;
	}

	function getItems(){
		return $this->items;
	}

	function getTotalPrice(){
		return $this->total_price;
	}

	function getTotalProductNumber(){
		return $this->total_product_number;
	}

	function setItems($items){
		$this->items = $items;
		return $this;
	}

	function setTotalPrice($total_price){
		$this->total_price = $total_price;
		return $this;
	}

	function setTotalProductNumber($total_product_number){
		$this->total_product_number = $total_product_number;
		return $this;
	}

	// function upCart($product_id, $qty, $unit_price, $price) {
	// 	$productRepository = new ProductRepository();
	// 	$product = $productRepository->find($product_id);
	// 	$item = array(
	// 		"product_id" => $product_id,
	// 		"name" => $product->getName(),
	// 		"img" => $product->getFeaturedImage(),
	// 		"qty" => $qty,
	// 		"unit_price" => $unit_price,
	// 		"total_price" => $price,

	// 	);
	// 	$this->upCartItem($item);
	// }

	function addProduct($product_id, $qty, $quant_p, $batch_id) {
		$productRepository = new ProductRepository();
		$product = $productRepository->find($product_id);
		$item = array(
			"product_id" => $product_id,
			"name" => $product->getName(),
			"img" => $product->getFeaturedImage(),
			"qty" => $qty,
			"unit_price" => $product->getPrice(),
			"total_price" => $product->getPrice() * $qty,
			"batch_id" => $batch_id
		);
		$this->addItem($item,$quant_p);
	}

	protected function addItem($item, $quant_p) {
		$product_id = $item["product_id"];
		$img = $item["img"];
		$name = $item["name"];
		$total_price = $item["total_price"];
		$qty = $item["qty"];
		$unit_price = $item["unit_price"];
		$batch_id = $item["batch_id"];
		if (!array_key_exists($product_id, $this->items)) {
			$this->items[$product_id] = array(
				"img" => $img,
				"name" => $name,
				"product_id" => $product_id,
				"qty" => $qty,
				"unit_price" => $unit_price, 
				"total_price" => $total_price,
				"batch_id" => $batch_id
			);
			$this->total_price += $unit_price * $qty;
			$this->total_product_number += $qty;
		}
		else {
			if(($this->items[$product_id]["qty"]+$qty)<=$quant_p){
				$this->items[$product_id]["qty"]+= $qty;
				$this->items[$product_id]["total_price"] = $this->items[$product_id]["qty"] * $unit_price;
				$this->total_price += $unit_price * $qty;
				$this->total_product_number += $qty;
			} 
		}

		
	}

	function deleteProduct($product_id) {
		if (array_key_exists($product_id, $this->items)) {
			unset($this->items[$product_id]);
		}
		//Recalculate total_product_number & total_price
		$this->total_price = 0;
		$this->total_product_number = 0;
		foreach ($this->items as $item) {
			$this->total_price += $item["unit_price"] * $item["qty"];
			$this->total_product_number += $item["qty"];
		}
	}
	
	function convertToArray() {
		$a = array();
		$a["items"] = $this->items;
		$a["total_product_number"] = $this->total_product_number;
		$a["total_price"] = $this->total_price;
		return $a;
	}
}