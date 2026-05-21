<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_POST['action'] ?? '';

function cart_summary(): array
{
    $items = array_values($_SESSION['cart']);
    $count = 0;
    $total = 0;

    foreach ($items as $item) {
        $qty = (int) ($item['qty'] ?? 0);
        $price = (float) ($item['price'] ?? 0);
        $count += $qty;
        $total += $price * $qty;
    }

    return [
        'items' => $items,
        'count' => $count,
        'total' => round($total, 2)
    ];
}

/* ================= ADD ================= */
if ($action === 'add') {
    $productId = $_POST['product_id'] ?? '';
    $price = (float) ($_POST['price'] ?? 0);
    $image = $_POST['image'] ?? '';
    $permalink = $_POST['permalink'] ?? '';

    foreach ($_SESSION['cart'] as &$item) {
        if (($item['id'] ?? '') == $productId && (float) ($item['price'] ?? 0) === $price) {
            $item['qty'] = (int) ($item['qty'] ?? 0) + 1;
            echo json_encode(["success" => true] + cart_summary());
            exit;
        }
    }
    unset($item);

    $_SESSION['cart'][] = [
        'id' => $productId,
        'name' => $_POST['name'] ?? '',
        'price' => $price,
        'image' => $image,
        'permalink' => $permalink,
        'qty' => 1
    ];

    echo json_encode(["success" => true] + cart_summary());
    exit;
}

/* ================= UPDATE QTY ================= */
if ($action === 'update') {

    $index = (int) ($_POST['index'] ?? -1);
    $qty = (int) $_POST['qty'];

    if (isset($_SESSION['cart'][$index])) {
        if ($qty <= 0) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        } else {
            $_SESSION['cart'][$index]['qty'] = $qty;
        }
    }

    echo json_encode(["success" => true] + cart_summary());
    exit;
}

/* ================= REMOVE ================= */
if ($action === 'remove') {

    $index = (int) ($_POST['index'] ?? -1);

    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }

    echo json_encode(["success" => true] + cart_summary());
    exit;
}

/* ================= CLEAR ================= */
if ($action === 'clear') {
    $_SESSION['cart'] = [];

    echo json_encode(["success" => true] + cart_summary());
    exit;
}

/* ================= GET CART ================= */
if ($action === 'get') {
    echo json_encode(["success" => true] + cart_summary());
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid cart action"] + cart_summary());
