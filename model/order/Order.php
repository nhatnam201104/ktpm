<?php


class Order
{
	public $id;
	public $created_date;
	public $status;
	public $user_id;
	public $payment_method;
	public $delivered_date;
	public $cus_fullname;
	public $cus_mobile;
	public $cus_address;
	public $total_money;

	function __construct($id, $created_date, $status, $user_id, $payment_method, $delivered_date, $cus_fullname, $cus_mobile, $cus_address, $total_money)
	{
		$this->id = $id;
		$this->created_date = $created_date;
		$this->status = $status;
		$this->user_id = $user_id;
		$this->payment_method = $payment_method;
		$this->delivered_date = $delivered_date;
		$this->cus_fullname = $cus_fullname;
		$this->cus_mobile = $cus_mobile;
		$this->cus_address = $cus_address;
		$this->total_money = $total_money;
	}



	function getId()
	{
		return $this->id;
	}

	function getCreatedDate()
	{
		return $this->created_date;
	}


	function getCustomerId()
	{
		return $this->user_id;
	}

	function getCustomerFullname()
	{
		return $this->cus_fullname;
	}

	function getShippingMobile()
	{
		return $this->cus_mobile;
	}

	function getPaymentMethod()
	{
		return $this->payment_method;
	}


	function getShippingHousenumberStreet()
	{
		return $this->cus_address;
	}

	function getDeliveredDate()
	{
		return $this->delivered_date;
	}

	function setCreatedDate($created_date)
	{
		$this->created_date = $created_date;
		return $this;
	}

	function setStatus($status)
	{
		$this->status = $status;
		return $this;
	}

	function setCustomerFullname($cus_fullname)
	{
		$this->cus_fullname = $cus_fullname;
		return $this;
	}

	function setCustomerId($user_id)
	{
		$this->user_id = $user_id;
		return $this;
	}

	function setShippingMobile($cus_mobile)
	{
		$this->cus_mobile = $cus_mobile;
		return $this;
	}

	function setPaymentMethod($payment_method)
	{
		$this->payment_method = $payment_method;
		return $this;
	}

	function setDeliveredDate($delivered_date)
	{
		$this->delivered_date = $delivered_date;
		return $this;
	}


	function getStatus()
	{
		$statusRepository = new StatusRepository();
		$status = $statusRepository->find($this->status);
		return $status;
	}


	function getCustomer()
	{
		$customerRepository = new CustomerRepository();
		$customer = $customerRepository->find($this->user_id);
		return $customer;
	}


	function getOrderItems()
	{
		$orderItemRepository = new OrderItemRepository();
		$orderItems = $orderItemRepository->getByOrderId($this->id);
		return $orderItems;
	}

	function getTotalPrice()
	{
		$totalPrice = 0;
		$orderItems = $this->getOrderItems();
		foreach ($orderItems as $orderItem) {
			$totalPrice += $orderItem->getTotalPrice();
		}
		return $totalPrice;
	}
}
