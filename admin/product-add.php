<?php
$error = [];
$sql = "SELECT * FROM category";
$categories = select_all_records($sql);
?>
<div class="bg-white d-flex flex-column justify-content-center align-items-center gap-5 py-5" style="max-width:1200px; margin: 0 auto">
    <h1 class="text-center text-secondary">Add New Product</h1>
    <form action="" method="post" enctype="multipart/form-data" style="width: 40em">
        <div class="mb-3">
            <label for="cate" class="form-label">Product's category</label>
            <select class="form-control" name="cate" id="cate">
                <option value="" selected>-- Select --</option>
                <?php
                foreach ($categories as $cate) {
                ?>
                    <option value=<?= $cate['cate_id'] ?>><?= $cate['cate_name'] ?></option>
                <?php
                }
                ?>
            </select>
            <small id="helpId" class="form-text text-danger fw-bold">
                <?php
                check_empty("cate", "product's category");
                ?>
            </small>
        </div>
        <!-- product's name -->
        <div class="mb-3 ">
            <label for="product-name" class="form-label">Product's name</label>
            <input type="text" class="form-control" name="product_name" id="product-name" aria-describedby="helpId" placeholder="">
            <small id="helpId" class="form-text text-danger fw-bold">
                <?php
                check_empty("product_name", "product's name");
                ?>
            </small>
        </div>
        <!-- price -->
        <div class="mb-3">
            <label for="price" class="form-label">Product's price</label>
            <input type="number" min=0 class="form-control" name="price" id="price" aria-describedby="helpId" placeholder="">
            <small id="helpId" class="form-text text-danger fw-bold">
                <?php
                check_empty("price", "price");
                ?>
            </small>
        </div>
        <!-- image -->
        <div class="mb-3">
            <label for="image" class="form-label">Product's Image</label>
            <input type="file" class="form-control" name="product_img" id="image" placeholder="" aria-describedby="fileHelpId">
            <small id="fileHelpId" class="form-text fw-bold text-danger">
                <?php
                check_image('product_img', 'submit');
                ?>
            </small>
        </div>
        <!-- discount -->
        <div class="mb-3">
            <label for="discount" class="form-label">Discount</label>
            <input type="number" min=0 class="form-control" name="discount" id="" value=0 aria-describedby="helpId" placeholder="">
        </div>
        <!-- product's description -->
        <div class="mb-3 ">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control d-block w-100"></textarea>
        </div>
        <!-- submit -->
        <div class="mb-3">
            <input type="submit" class="btn bg-dark text-white vertical-align-top" rows="10" name="submit" value="Add Product">
        </div>

    </form>
</div>

<?php
if (isset($_POST['submit'])) {
    if (empty($error)) {
        $cateID = $_POST['cate'];
        $productName = $_POST["product_name"];
        $price = $_POST["price"];
        $discount = $_POST['discount'];
        $description = $_POST['description'];
        // check tên sản phẩm đã tồn tại trong database hay chưa ?

        $isExisting = false;
        $product_names = select_all_records("SELECT product_name FROM product");
        foreach ($product_names as $val) :
            if (strStandardize($productName) == strStandardize($val['product_name'])) :
                $isExisting = true;
                echo "<script>alert(`This product existed!`)</script>";
                break;
            endif;
        endforeach;
        if ($isExisting === false) {
            $path = upload_file('../assets/img/products/', 'product_img');
            $sql = "INSERT INTO product (cate_id, product_name, price, product_img, discount, product_description) 
                VALUES ({$cateID},'{$productName}',{$price},'{$path}','{$discount}','{$description}')";
            execute_query($sql);
            echo "<script>alert(`Add product successfully!`)</script>";
        }
    } else {
        echo "<script>alert(`Failed!`)</script>";
    }
}
