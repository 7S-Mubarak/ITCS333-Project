<?php
session_start();

if (isset($_POST['remove'])) {
    $key = $_POST['remove_key'];
    if (isset($_SESSION['cart'][$key])) {
        unset($_SESSION['cart'][$key]);
        // Re-index the cart array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
    header('Location:cart.php');
    exit;
}
?>
