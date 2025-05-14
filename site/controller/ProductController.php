<?php
class ProductController
{
    function index()
    {
        $productRepository = new ProductRepository();
        $categoryRepository = new CategoryRepository();
        $item_per_page = ITEM_PER_PAGE;
        $page = $_GET["page"] ?? 1;
        $conds = ["deleted" => [
                    "type" => "=",
                    "val" => 0
                ]];
        $sorts = [];
        $categoryName = "Tất cả sản phẩm";
        //toán tử 3 ngôi thông thường
        // $category_id = !empty($_GET["category_id"]) ? $_GET["category_id"] : null;

        //toán tử 3 ngôi rút gọn
        $category_id = $_GET["category_id"] ?? null;
        if ($category_id) {
            $conds = [
                "category_id" => [
                    "type" => "=",
                    "val" => $category_id
                ],
                "deleted" => [
                    "type" => "=",
                    "val" => 0
                ]
            ];
            $category = $categoryRepository->find($category_id);
            $categoryName = $category->getName();
        } //SELECT * ... WHERE category_id = 5

        $price_range = $_GET["price-range"] ?? null;
        if ($price_range) {
            $tmp = explode("-", $price_range);
            $start = $tmp[0];
            $end = $tmp[1];
            $conds = [
                "price" => [
                    "type" => "BETWEEN",
                    "val" => "$start AND $end"
                ],
                "deleted" => [
                    "type" => "=",
                    "val" => 0
                ]
            ];
            if ($end == "greater") {
                $conds = [
                    "price" => [
                        "type" => ">=",
                        "val" => $start
                    ],
                    "deleted" => [
                    "type" => "=",
                    "val" => 0
                ]
                ];
                //SELECT * ... WHERE price >= 1000000
            }
        } //SELECT * ... WHERE price BETWEEN 100000 AND 200000

        $sort = $_GET["sort"] ?? null;
        if ($sort) {
            $tmp = explode("-", $sort);
            $first = $tmp[0];
            $second = $tmp[1];
            $mapCol = ["price" => "price", "alpha" => "name", "created" => "created_date"];

            $column = $mapCol[$first];
            $order = $second;
            $sorts = [$column => $order];
        }

        $search = $_GET["search"] ?? null;
        if ($search) {
            $conds = [
                "name" => [
                    "type" => "LIKE",
                    "val" => "'%$search%'"
                ],
                "deleted" => [
                    "type" => "=",
                    "val" => 0
                ]
            ]; //SELECT * ..... WHERE name LIKE '%$search%'
        }

        $products = $productRepository->getBy($conds, $sorts, $page, $item_per_page);

        $totalProducts = $productRepository->getBy($conds, $sorts);

        $pageNumber = ceil(count($totalProducts) / $item_per_page);

        // Lấy tất cả các danh mục
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->getAll();
        require "view/product/index.php";
    }

    function show()
    {
        $id = $_GET["id"];
        $productRepository = new ProductRepository();
        $product = $productRepository->find($id);
        $category_id = $product->getCategoryId();

        // Lấy tất cả các danh mục
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->getAll();
        $conds = [
            "category_id" => [
                "type" => "=",
                "val" => $product->getCategoryId()
            ],
            "id" => [
                "type" => "!=",
                "val" => $id
            ]
        ];
        $relatedProducts = $productRepository->getBy($conds);

        require "view/product/show.php";
    }
    function ajaxSearch()
    {
        $pattern = $_GET["pattern"];
        $productRepository = new ProductRepository();
        $products = $productRepository->getByPattern($pattern);
        require "view/product/ajaxSearch.php";
    }
    function storeComment()
    {
        $data = [
            "email" => $_POST["email"],
            "fullname" => $_POST["fullname"],
            "star" => $_POST["rating"],
            "created_date" => date("Y-m-d H:i:s"),
            "description" => $_POST["description"],
            "product_id" =>  $_POST["product_id"],
        ];

        // Lưu đánh giá vào cơ sở dữ liệu
        $commentRepository = new CommentRepository();
        $commentRepository->save($data);

        // Lấy lại danh sách đánh giá mới
        $productRepository = new ProductRepository();
        $product = $productRepository->find($_POST["product_id"]);
        $comments = $product->getComments(); // Lấy danh sách đánh giá mới

        // Hiển thị danh sách đánh giá
        require "layout/comments.php";
    }
}
