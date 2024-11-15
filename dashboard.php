<?php
session_start();
require_once "config.php";

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

$search_query = isset($_POST['search']) ? '%' . $_POST['search'] . '%' : '%';
$products = [];
$sql = "SELECT * FROM products WHERE name LIKE :search_query";
if ($stmt = $pdo->prepare($sql)) {
    $stmt->execute([':search_query' => $search_query]);
    $products = $stmt->fetchAll();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $user_id = $_SESSION["id"];

    $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->execute([':user_id' => $user_id, ':product_id' => $product_id, ':quantity' => $quantity]);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_from_cart'])) {
    $cart_id = $_POST['cart_id'];

    $sql = "DELETE FROM cart WHERE id = :cart_id AND user_id = :user_id";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->execute([':cart_id' => $cart_id, ':user_id' => $_SESSION["id"]]);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['purchase_cart'])) {
    $total_amount = $_POST['total_amount'];
    $payment = $_POST['payment'];

    if ($payment >= $total_amount) {
       
        $user_id = $_SESSION["id"];
        $sql = "INSERT INTO purchases (user_id, total_amount, payment, purchase_date) VALUES (:user_id, :total_amount, :payment, NOW())";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->execute([':user_id' => $user_id, ':total_amount' => $total_amount, ':payment' => $payment]);

            $sql = "DELETE FROM cart WHERE user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':user_id' => $user_id]);
        }
        $purchase_success = true;
    } else {
        $error_message = "Insufficient amount. Please enter the correct total.";
    }
}

$cart_items = [];
$sql = "SELECT cart.id as cart_id, products.name, products.price, cart.quantity 
        FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.user_id = :user_id";
if ($stmt = $pdo->prepare($sql)) {
    $stmt->execute([':user_id' => $_SESSION["id"]]);
    $cart_items = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>POS Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .navbar { margin-bottom: 20px; }
        .main-container { display: flex; }
        .cart-section { width: 30%; padding-right: 20px; }
        .products-section { width: 70%; padding-left: 20px; }
        .product-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .product-card { border: 1px solid #ddd; border-radius: 8px; padding: 10px; text-align: center; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        .product-card img { width: 100%; height: auto; border-radius: 5px; }
        .buy-btn { background-color: #007bff; color: #fff; padding: 8px; border: none; border-radius: 4px; cursor: pointer; transition: background-color 0.3s; }
        .buy-btn:hover { background-color: #0056b3; }
        .alert { display: none; }
       
        #searchResults { 
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            position: absolute;
            top: 50px;
            left: 0;
            width: 100%;
            z-index: 1000;
            display: none;
        }
        .product-card img {
    width: 100%; 
    height: 200px; 
    object-fit: cover; 
    border-radius: 5px;
}

        #searchResults a {
            padding: 10px;
            display: block;
            text-decoration: none;
            color: #333;
        }
        #searchResults a:hover {
            background-color: #f8f9fa;
        }
        .input-group {
            width: 100%;
        }

        .input-group .form-control {
            border-right: none;
            border-radius: 0.25rem 0 0 0.25rem;
        }

        .input-group .btn {
            border-left: none;
            border-radius: 0 0.25rem 0.25rem 0;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">JRP Products</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">Dashboard</a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2>Welcome to the JRP Products Dashboard</h2>
    <div class="main-container"> 

        <div class="cart-section">
            <h3>Your Cart</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_amount = 0;
                    foreach ($cart_items as $item): 
                        $total = $item['price'] * $item['quantity'];
                        $total_amount += $total;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']); ?></td>
                            <td><?= $item['quantity']; ?></td>
                            <td>₱<?= number_format($total, 2); ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="cart_id" value="<?= $item['cart_id']; ?>">
                                    <button type="submit" name="delete_from_cart" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2">Total</td>
                        <td>₱<?= number_format($total_amount, 2); ?></td>
                        <td><button class="btn btn-success" data-toggle="modal" data-target="#paymentModal">Purchase</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="products-section">
            <form method="post" class="mb-3">
                <div class="input-group">
                    <input type="text" id="searchBar" name="search" placeholder="Search products..." class="form-control">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>

            <?php if (isset($purchase_success)): ?>
                <div class="alert alert-success" id="successMessage">Purchase successful!</div>
            <?php elseif (isset($error_message)): ?>
                <div class="alert alert-danger"><?= $error_message; ?></div>
            <?php endif; ?>

            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                        <h5><?= htmlspecialchars($product['name']); ?></h5>
                        <p>₱<span class="price"><?= number_format($product['price'], 2); ?></span></p>
                        <form method="post">
                            <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                            <input type="number" name="quantity" value="1" min="1" class="form-control mb-2 quantity" id="quantity_<?= $product['id']; ?>" data-price="<?= $product['price']; ?>">
                            <p>Total: ₱<span class="total-price" id="total_<?= $product['id']; ?>"><?= number_format($product['price'], 2); ?></span></p>
                            <button type="submit" name="add_to_cart" class="buy-btn">Add to Cart</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              
                <p><strong>Total Amount: ₱<?= number_format($total_amount, 2); ?></strong></p>
                <form method="post">
                    <div class="form-group">
                        <label for="payment">Enter Amount</label>
                        <input type="number" name="payment" class="form-control" placeholder="Amount" required>
                    </div>
                    <input type="hidden" name="total_amount" value="<?= $total_amount; ?>">
                    <button type="submit" name="purchase_cart" class="btn btn-primary">Complete Purchase</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>

$(document).ready(function() {
    $(".quantity").on("input", function() {
        var quantity = $(this).val();
        var price = $(this).data("price");
        var total = (quantity * price).toFixed(2);
        var totalElementId = "#total_" + $(this).attr("id").split("_")[1]; 
        $(totalElementId).text(total); 
    });
});
</script>

</body>
</html>