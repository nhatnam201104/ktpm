<?php
class Pagination
{
  public $quantityPerPage;
  public $quantityProduct;
  public $quantityPage;
  public $currentPage;

  public function __construct($array, $quantityPerPage, $currentPage)
  {
    $this->quantityProduct = count($array);
    $this->quantityPerPage = $quantityPerPage;
    $this->quantityPage = ceil($this->quantityProduct / $quantityPerPage);
    $this->currentPage = $currentPage;
  }
  public function startProduct()
  {
    return ($this->currentPage - 1) * $this->quantityPerPage;
  }
  public function endProduct()
  {
    $end = $this->currentPage * $this->quantityPerPage - 1;
    if ($end > $this->quantityProduct - 1) {
      $end = $this->quantityProduct - 1;
    }
    return $end;
  }
  public function nextDot()
  {
    if ($this->currentPage == $this->quantityPage) {
      return $this->quantityPage;
    } else {
      return $this->currentPage + 1;
    }
  }
  public function prevDot()
  {
    if ($this->currentPage == 1) {
      return 1;
    } else {
      return $this->currentPage - 1;
    }
  }
}