<?php
class PaymentController
{
    function checkout()
    {
        $cartStorage = new CartStorage();
        $cart = $cartStorage->fetch();
        $email = "example@gmail.com";
        if (!empty($_SESSION["email"])) {
            $email = $_SESSION["email"];
            $customerRepository = new CustomerRepository();
            $customer = $customerRepository->findEmail($email);
            require "layout/variable_address.php";
            require "view/payment/checkout.php";
        } else {
            $_SESSION["error"] = "Vui lòng đăng nhập tài khoản trước khi đặt bất kì món hàng nào. Xin cảm ơn!!!";
            header("location: index.php");
        }
    }

    function order()
    {
        //check đơn hàng (số lượng sản phẩm còn trong kho không)
        //sản phẩm không còn trong kho thì không cho đặt hàng
        $cartStorage = new CartStorage();
        $cart = $cartStorage->fetch();
        $items = $cart->getItems();
        $productRepository = new ProductRepository();

        foreach ($items as $item) {
            $product_id = $item["product_id"];
            $product = $productRepository->find($product_id);
            if ($product->getInventoryQty() < $item["qty"]) {
                $_SESSION["error"] = "{$product->getName()} chỉ còn {$product->getInventoryQty()} sản phẩm trong kho, bạn đặt hàng {$item["qty"]} sản phẩm đã vượt quá số lượng. Vui lòng đặt số lượng trong giới hạn của kho.!!!";
                header("location: index.php");
                exit;
            }
        }
        //Lưu đơn hàng

        $email = "khachvanglai@gmail.com";
        if (!empty($_SESSION["email"])) {
            $email = $_SESSION["email"];
        }
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->findEmail($email);
        $orderRepository = new OrderRepository();
        $data = [];
        $data["created_date"] = date("Y-m-d H:i:s");
        $data["status"] = 0; // Đã đặt hàng
        $data["user_id"] = $customer->getId();
        $data["payment_method"] = $_POST["payment_method"];
        $data["cus_fullname"] = $_POST["fullname"];
        $data["cus_mobile"] = $_POST["mobile"];
        $data["cus_address"] =  $_POST["address"];
        $data["delivered_date"] = date("Y-m-d H:i:s", strtotime("+3 days"));

        $orderItemRepository = new OrderItemRepository();
        if ($orderId = $orderRepository->save($data)) {
            // Tính tổng tiền từ các mục hàng trong đơn hàng
            $total_money = 0;
            $items = $cart->getItems();
            foreach ($items as $item) {
                $total_money += $item["total_price"];
                // Lưu các đơn hàng chi tiết
                $dataItem = [];
                $dataItem["product_id"] = $item["product_id"];
                $dataItem["order_id"] = $orderId;
                $dataItem["qty"] = $item["qty"];
                $dataItem["unit_price"] = $item["unit_price"];
                $dataItem["total_price"] = $item["total_price"];
                $dataItem["batch_id"] = $item["batch_id"];
                $orderItemRepository->save($dataItem);
                // Cập nhật lại kho hàng
                // $product = $productRepository->find($dataItem["product_id"]);
                // $updatedInventoryQty = $item["qty"];
                // $product->setInventoryQty($updatedInventoryQty);
                // $product->setFeatured(0);
                // $productRepository->update($product);
            }

            // Lưu tổng tiền vào đơn hàng
            $orderRepository->saveMoney($orderId, $total_money);

            $_SESSION["success"] = "Bạn đã đặt hàng thành công";
            $cartStorage->clear();
        } else {
            $_SESSION["error"] = $orderRepository->getError();
        }


        header("location: index.php");
    }
}
