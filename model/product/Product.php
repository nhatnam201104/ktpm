<?php
class Product
{
    protected $id;
    protected $discount_id;
    protected $name;
    protected $price;
    protected $featured_image;
    protected $inventory_qty;
    protected $category_id;
    protected $brand_id;
    protected $created_date;
    protected $description;
    protected $enter_price;
    protected $deleted;
    protected $updated_at;
    protected $featured;


    // Constructor
    function __construct($id, $discount_id, $name, $price, $featured_image, $inventory_qty, $category_id, $brand_id, $created_date, $description, $enter_price, $deleted, $updated_at, $featured)
    {
        $this->id = $id;
        $this->discount_id = $discount_id;
        $this->name = $name;
        $this->price = $price;
        $this->featured_image = $featured_image;
        $this->inventory_qty = $inventory_qty;
        $this->category_id = $category_id;
        $this->brand_id = $brand_id;
        $this->created_date = $created_date;
        $this->description = $description;
        $this->enter_price = $enter_price;
        $this->deleted = $deleted;
        $this->updated_at = $updated_at;
        $this->featured = $featured;
    }

    // Getters
    function getId()
    {
        return $this->id;
    }
    function setFeatured(int $x)
    {
        return $this->featured = $x;
    }
    function getDiscountId()
    {
        return $this->discount_id;
    }

    function getName()
    {
        return $this->name;
    }

    function getPrice()
    {
        return $this->price;
    }

    function getFeaturedImage()
    {
        return $this->featured_image;
    }

    function getInventoryQty()
    {
        return $this->inventory_qty;
    }

    function getCategoryId()
    {
        return $this->category_id;
    }

    function getBrandId()
    {
        return $this->brand_id;
    }

    function getCreatedDate()
    {
        return $this->created_date;
    }

    function getDescription()
    {
        return $this->description;
    }

    function getEnterPrice()
    {
        return $this->enter_price;
    }

    function getDeleted()
    {
        return $this->deleted;
    }

    function getUpdatedAt()
    {
        return $this->updated_at;
    }
    function getFeatured()
    {
        return $this->featured;
    }

    // function getSalePrice()
    // {
    //     return $this->price - ($this->price * $this->getDiscountPercentage()) / 100;
    // }

    function getDiscountPercentage()
    {
        global $conn;
        $dis_percent="SELECT discount_percentage from discount where id=$this->discount_id";
        $result=$conn->query($dis_percent);
        $row=$result->fetch_assoc();
        return $row['discount_percentage'];
    }

    function getCategory()
    {
        $categoryRepository = new CategoryRepository();
        $category = $categoryRepository->find($this->category_id);
        return $category;
    }
    function getBrand()
    {
        $brandRepository = new BrandRepository();
        $brand = $brandRepository->find($this->brand_id);
        return $brand;
    }
    function getComments()
    {
        $commentRepository = new CommentRepository();
        $comments = $commentRepository->getByProductId($this->id);
        return $comments;
    }
    // Setters (if needed)
    function setInventoryQty($inventory_qty)
    {
        $this->inventory_qty = $inventory_qty;
        return $this;
    }
}
