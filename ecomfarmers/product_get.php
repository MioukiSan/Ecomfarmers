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

    $sql = "SELECT id, image, title, description, price, quantity, timestamp FROM product_list $whereClause";
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
            <h5 class="card-title fw-bolder"><?php echo $product['title']; ?></h5>
            <p class="card-text"><?php echo $product['description']; ?></p>
            <p class="card-text price">$<?php echo $product['price']; ?></p>
            <form action="cart.php" method="get">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <button type="submit" class="btn btn-primary border-0" name="submit">Add to Cart</button>
            </form>
        </div>
    </div>
</div>
<?php } ?>