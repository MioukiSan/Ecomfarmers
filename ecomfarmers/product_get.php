<?php
    include './connect.php';

    $selectedCategories = [];
    if (isset($_GET['category']) && is_array($_GET['category'])) {
        $selectedCategories = $_GET['category'];
    }

    $whereClause = "";
    if (!empty($selectedCategories)) {
        $categoryConditions = [];
        foreach ($selectedCategories as $selectedCategory) {
            $escapedCategory = mysqli_real_escape_string($conn, $selectedCategory);
            $categoryConditions[] = "categories = '$escapedCategory'";
        }
        $whereClause = "WHERE " . implode(" OR ", $categoryConditions);
    }

    $sql = "SELECT id, image, title, description, price, quantity, timestamp, unit, harvest, status FROM product_list $whereClause";
    $result = $conn->query($sql);

    $products = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
?>

<?php foreach ($products as $product) { ?>
<div class="col-md-4">
    <div class="card">
        <img src="img/<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['title']; ?>">
        <div class="card-body">
            <div class="d-sm-flex">
                <div class="col-md-6">
                    <h5 class="card-title fw-bolder"><?php echo $product['title']; ?></h5>
                </div>
                <div class="col-md-6">
                    <p class="card-text price">₱ <?php echo $product['price'] .'/'. $product['unit']; ?></p>
                </div>
            </div>
            <p class="card-text"><?php echo $product['description']; ?></p>
            <form action="cart.php" method="get">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <div class="text-center">
                    <?php if($product['status'] === 'On Sale'){ ?>
                    <button type="submit" class="btn btn-primary border-0" name="submit">Add to
                        Cart</button>
            </form>
            <form action="" method="POST">
                <?php } elseif($product['status'] === 'Pre Order') { ?>
                    <p><?php echo 'harvest Date:' . $product['harvest'] ?></p>
                    <button class="btn btn-primary border-0" name="preorder" type="submit">Pre Order</button>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
</div>
<?php } ?>