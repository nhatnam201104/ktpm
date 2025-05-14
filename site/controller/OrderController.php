<?php 
class OrderController {
	function index() {
		$customerRepository = new CustomerRepository();
		$customer = $customerRepository->findEmail($_SESSION["email"]);
		$orderRepository = new OrderRepository();
		$orders = $orderRepository->getByCustomerId($customer->getId());
		require "view/order/index.php";
	}

	function show() {
		$orderRepository = new OrderRepository();
		$order = $orderRepository->find($_GET["id"]);
		require "view/order/show.php";
	}

	function cancel() {
		$orderRepository = new OrderRepository();
        if (!$orderRepository->updateStatusCancel($_POST["id"], 3)) {
            echo  'Không thể hủy đơn hàng';
            exit;
        }
        
        echo  'Hủy đơn hàng thành công';
        exit;
	}
}