<?php
    session_start();
    $title = "dashboard";
    include '../components/backend_header.php';

    if (isset($_POST['add'])) {
        $image = $_FILES['image']['name'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $category = $_POST['categories'];
        $unit = $_POST['unit'];
        $status = $_POST['status']; // Add this line for the new 'status' column
        $harvestTime = ($status == 'Pre Order') ? $_POST['harvestTime'] : null; // Add this line for 'harvest time'
    
        $targetDir = '../img/';
        $targetFile = $targetDir . basename($image);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
        // Check if the file was uploaded successfully
        if (isset($_FILES["image"]["tmp_name"])) {
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check === false) {
                $uploadOk = 0;
            }
        } else {
            $uploadOk = 0;
        }
    
        // Check if the file already exists
        if (file_exists($targetFile)) {
            $uploadOk = 0;
        }
    
        // Check file size (500KB limit)
        if ($_FILES["image"]["size"] > 500000) {
            $uploadOk = 0;
        }
    
        // Allow only specific image file formats
        $allowedFormats = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowedFormats)) {
            $uploadOk = 0;
        }
    
        if ($uploadOk == 0) {
            echo "<script>alert('Sorry, your file was not uploaded, file already exists.'); window.location.href='product.php';</script>";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
    
                // Prepare and execute the SQL query
                $sql = "INSERT INTO `product_list` (`image`, `title`, `description`, `price`, `quantity`, `categories`, `unit`, `status`, `harvest`) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssssss", $image, $title, $description, $price, $quantity, $category, $unit, $status, $harvestTime);
    
                if ($stmt->execute()) {
                    echo "<script>alert('New product added successfully.'); window.location.href='product.php';</script>";
                } else {
                    echo "<script>alert('New product not added successfully.'); window.location.href='product.php';</script>";
                }
    
                // Close the database connection
                $stmt->close();
                $conn->close();
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.'); window.location.href='product.php';</script>";
            }
        }
    }
   
?>
<style></style>
<body>
    <div class="container-fluid">
    <div class="row flex-nowrap">
            <div class="col-12 col-xl-2 navside">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100 bg">
                    <div class="d-flex">
                        <img src="../img/logo.png" class="logo me-2" alt="pos">
                        <a href="index.php"
                            class="d-flex align-items-center mb-md-0 me-md-auto text-white text-decoration-none">
                            <span class="fw-bold"><?php echo "Welcome, ", $_SESSION['username']; ?></span>
                        </a>
                        <button class="btn text-center menubut" id="menu-sm-screen"><i class='bx bx-menu bx-md' style='color:#ffffff'  ></i></button>
                    </div>
                    <hr>
                    <ul class="nav nav-pills flex-column mb-auto w-100">
                        <li class="nav-item">
                            <a href="dashboard.php" class="nav-link link-light">
                                <i class="bx bxs-dashboard me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="user.php" class="nav-link link-light">
                                <i class='bx bxs-user me-2'></i>User Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="product.php"  class="nav-link active" aria-current="page">
                                <i class="bx bxl-product-hunt me-2"></i>Product Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="service.php" class="nav-link link-light">
                                <i class="bx bx-bulb me-2"></i>Service Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="sales.php" class="nav-link link-light">
                                <i class="bx bxs-cart me-2"></i>Sales Report
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="order.php" class="nav-link link-light">
                                <i class='bx bxs-bookmark me-2'></i>Order Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="service_avail.php" class="nav-link link-light">
                                <i class='bx bxs-hard-hat me-2'></i>Service Avail Management
                            </a>
                        </li>
                    </ul>
                    <hr>
                    <div class="dropdown drop">
                        <a href="#"
                            class="d-flex align-items-center text-white text-decoration-none dropdown-toggle ms-2"
                            id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="../img/logo.png" alt="pos" width="32" height="32" class="rounded-circle me-2">
                            <strong><?php echo $_SESSION['username']; ?></strong>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser2">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="../logout.php">Sign out</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <?php
                function updateProductQuantity($conn, $productId, $quantity) {
                    $query = "UPDATE product_list SET quantity = quantity - $quantity WHERE id = $productId";
                    $result = $conn->query($query);
                    return $result;
                }

                function fetchProductData($conn, $table) {
                    $query = "SELECT * FROM $table";
                    $result = $conn->query($query);
                    $data = array();
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                    return $data;
                }

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_product'])) {
                    $productId = $_POST['product_id'];
                    $orderedQuantity = $_POST['quantity'];

                    updateProductQuantity($conn, $productId, $orderedQuantity);
                }

                $productData = fetchProductData($conn, 'product_list');
            ?>

            <!-- Main Content -->
            <div class="col-12 col-xl-10">
                <div class="col mt-4">
                    <h1 class="mb-4 text-uppercase fw-bolder">Product Management</h1>
                    <hr>
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                        data-bs-target="#addProductModal">
                        Add Products
                    </button>
                    <div class="row">
                        <div class="col">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Categories</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($productData as $product) { ?>
                                        <tr>
                                            <td><?php echo $product['id']; ?></td>
                                            <td><?php echo $product['image']; ?></td>
                                            <td><?php echo $product['title']; ?></td>
                                            <td><?php echo $product['price']; ?></td>
                                            <td><?php echo $product['quantity'] . ' ' . $product['unit']; ?></td>
                                            <td><?php echo $product['categories']; ?></td>
                                            <td><?php echo $product['status'] ?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#editProductModal_<?php echo $product['id']; ?>"
                                                    data-id="<?php echo $product['id']; ?>">
                                                    Edit
                                                </button>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#deleteProductModal"
                                                    data-id="<?php echo $product['id']; ?>">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <?php foreach ($productData as $product) { ?>
                <!-- Edit Product Modal -->
                <div class="modal fade" id="editProductModal_<?php echo $product['id']; ?>" tabindex="-1"
                    aria-labelledby="editProductModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="product_edit.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Product Image</label>
                                        <input type="file" class="form-control" id="image" name="image">
                                    </div>
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Product Title</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            value="<?php echo $product['title']; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Product Description</label>
                                        <textarea class="form-control" id="description"
                                            name="description"><?php echo $product['description']; ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Product Price</label>
                                        <input type="number" class="form-control" id="price" name="price"
                                            value="<?php echo $product['price']; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">Product Quantity</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity"
                                            value="<?php echo $product['quantity']; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="categories" class="form-label">Product Categories</label>
                                        <select class="form-select" id="categories" name="categories">
                                            <option value="Vegetables"
                                                <?php if ($product['categories'] === 'Vegetables') echo 'selected'; ?>>
                                                Vegetables</option>
                                            <option value="Fish"
                                                <?php if ($product['categories'] === 'Fish') echo 'selected'; ?>>
                                                Fish</option>
                                            <option value="Meats"
                                                <?php if ($product['categories'] === 'Meats') echo 'selected'; ?>>
                                                Meats</option>
                                            <option value="Rice"
                                                <?php if ($product['categories'] === 'Rice') echo 'selected'; ?>>
                                                Rice</option>
                                            <option value="Fruit"
                                                <?php if ($product['categories'] === 'Fruit') echo 'selected'; ?>>
                                                Fruit</option>
                                            
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="categories" class="form-label">Product Status</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="On Sale"
                                                <?php if ($product['status'] === 'On Sale') echo 'selected'; ?>>
                                                On Sale</option>
                                            <option value="Pre Order"
                                                <?php if ($product['status'] === 'Pre Order') echo 'selected'; ?>>
                                                Pre Order</option>
                                            <option value="Sold"
                                                <?php if ($product['status'] === 'Sold') echo 'selected'; ?>>
                                                Sold</option>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="submit">Save
                                            changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <!-- Delete Product Modal -->
                <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteProductModalLabel">Delete Product</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete this product?
                            </div>
                            <div class="modal-footer">
                                <form id="deleteForm" method="POST" action="product_delete.php">
                                    <input type="hidden" name="product_id" id="deleteProductId"
                                        value="<?php echo $product['id']; ?>">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger" name="submit">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Product Modal -->
                <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Product Image</label>
                                        <input type="file" class="form-control" id="image" name="image">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="mb-3">
                                                <label for="title" class="form-label">Product Title</label>
                                                <input type="text" class="form-control" id="title" name="title"">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="unit" class="form-label">Unit</label>
                                                <select class="form-control" name="unit" required>
                                                    <option selected disabled></option>
                                                    <option value="Piece">Piece</option>
                                                    <option value="Kilo">Kilo</option>
                                                    <option value="Sack">Sack</option>
                                                </select>  
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select class="form-control" name="status" id="ProductStatus" required onchange="toggleHarvestTime()">
                                                    <option selected disabled>-- Select Status --</option>
                                                    <option value="On Sale">On Sale</option>
                                                    <option value="Pre Order">Pre Order</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-3" id="harvestTime">
                                            <label for="harvestTime" class="form-label" id="harvestlabel">Harvest Time</label>
                                            <input type="date" class="form-control" id="harvestTimeInput" name="harvestTime" disabled>
                                        </div>

                                        <script>
                                            function toggleHarvestTime() {
                                                var statusDropdown = document.getElementById('ProductStatus');
                                                var harvestTimeInput = document.getElementById('harvestTimeInput'); // Changed the ID here
                                                var harvestlabel = document.getElementById('harvestlabel');
                                                if (statusDropdown.value === 'Pre Order') {
                                                    harvestTimeInput.disabled = false; // Enable the input
                                                    harvestTimeInput.style.display = 'block'; // Show the input
                                                } else {
                                                    harvestTimeInput.disabled = true; // Disable the input
                                                    harvestTimeInput.style.display = 'none'; // Hide the input
                                                    harvestlabel.style.display = 'none';
                                                }
                                            }
                                        </script>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Product Description</label>
                                        <textarea class="form-control" id="description" name="description"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Product Price</label>
                                        <input type="number" class="form-control" id="price" name="price">
                                    </div>
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">Product Quantity</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity">
                                    </div>
                                    <div class="mb-3">
                                        <label for="categories" class="form-label">Product Categories</label>
                                        <select class="form-select" id="categories" name="categories">
                                            <option value="Vegetables">Vegetables</option>
                                            <option value="Fish">Fish</option>
                                            <option value="Meats">Meats</option>
                                            <option value="Rice">Rice</option>
                                            <option value="Fruit">Fruit</option>
                                           
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="add">Add Product</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
<!-- Include the necessary JavaScript file -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
  const menuButton = document.getElementById('menu-sm-screen');
  const navSide = document.querySelector('.navside');

  menuButton.addEventListener('click', function () {
    navSide.classList.toggle('active');
  });
});

</script>
</html>