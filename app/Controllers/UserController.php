<?php
namespace App\Controllers;

use App\Models\Auth;
use App\Models\Catalog;
use App\Models\Order;

class UserController {
    protected $catalogModel;
    protected $orderModel;
    protected $authModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->catalogModel = new Catalog();
        $this->orderModel = new Order();
        $this->authModel = new Auth();
    }

    public function home() {
        $userId = $this->requireLogin();
        $ordersCount = $this->orderModel->countOrdersByUser($userId);
        $cartCount = $this->countCartItems();

        $this->render('home', [
            'ordersCount' => $ordersCount,
            'cartCount' => $cartCount,
        ]);
    }

    public function menu() {
        $this->requireLogin();

        $limit = 8;
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;
        $search = trim((string) ($_GET['search'] ?? ''));
        $categoryId = $_GET['category'] ?? '';
        $sort = $_GET['sort'] ?? 'newest';

        $productsCount = $this->catalogModel->countAvailableProductsFiltered($search, $categoryId);
        $totalPages = max(1, (int) ceil($productsCount / $limit));
        $products = $this->catalogModel->getAvailableProductsFiltered($limit, $offset, $search, $categoryId, $sort);
        $categories = $this->catalogModel->getCategories();

        $this->render('menu', [
            'products' => $products,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'categories' => $categories,
            'search' => $search,
            'categoryId' => $categoryId,
            'sort' => $sort,
            'productsCount' => $productsCount,
        ]);
    }

    public function addToCart() {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=user/menu');
            exit();
        }

        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = max(1, (int) ($_POST['quantity'] ?? 1));

        $product = $this->catalogModel->getProductById($productId);
        if (!$product || strtolower($product['status'] ?? '') !== 'available') {
            $this->flash('danger', 'Selected product is not available anymore.');
            header('Location: index.php?url=user/menu');
            exit();
        }

        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'name' => $product['product_name'],
                'price' => (float) $product['price'],
                'image' => $product['product_img'],
                'quantity' => $quantity,
            ];
        }

        $this->saveCart($cart);
        $this->flash('success', 'Item added to your cart.');

        header('Location: index.php?url=user/cart');
        exit();
    }

    public function cart() {
        $this->requireLogin();

        [$cartItems, $subtotal, $itemsCount] = $this->buildCartSummary();

        $this->render('cart', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'itemsCount' => $itemsCount,
        ]);
    }

    public function updateCart() {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=user/cart');
            exit();
        }

        $cart = $this->getCart();
        $quantities = $_POST['quantity'] ?? [];

        foreach ($quantities as $productId => $quantity) {
            $productId = (int) $productId;
            $quantity = (int) $quantity;

            if ($quantity <= 0) {
                unset($cart[$productId]);
                continue;
            }

            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = min($quantity, 20);
            }
        }

        $this->saveCart($cart);
        $this->flash('success', 'Cart updated successfully.');

        header('Location: index.php?url=user/cart');
        exit();
    }

    public function removeFromCart() {
        $this->requireLogin();

        $productId = (int) ($_GET['id'] ?? 0);
        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $this->saveCart($cart);
            $this->flash('success', 'Item removed from your cart.');
        }

        header('Location: index.php?url=user/cart');
        exit();
    }

    public function checkout() {
        $userId = $this->requireLogin();
        [$cartItems, $subtotal, $itemsCount] = $this->buildCartSummary();

        if (empty($cartItems)) {
            $this->flash('danger', 'Your cart is empty.');
            header('Location: index.php?url=user/menu');
            exit();
        }

        $error = null;
        $user = $this->authModel->findById($userId);
        $rooms = $this->orderModel->getRooms();
        $selectedRoomId = $user['room_id'] ?? ($_SESSION['room_id'] ?? null);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $paymentMethod = $_POST['payment_method'] ?? 'cash';
            $notes = trim($_POST['notes'] ?? '');
            $roomId = isset($_POST['room_id']) ? (int) $_POST['room_id'] : ($user['room_id'] ?? ($_SESSION['room_id'] ?? null));

            // Validate selected room exists
            if ($roomId) {
                $found = false;
                foreach ($rooms as $r) {
                    if ((int) ($r['id'] ?? 0) === $roomId) {
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    throw new \InvalidArgumentException('Invalid room selection.');
                }
            }

            $selectedRoomId = $roomId;

            try {
                $orderId = $this->orderModel->placeOrder(
                    $userId,
                    $roomId,
                    $cartItems,
                    $notes,
                    $paymentMethod
                );

                $this->clearCart();
                $this->flash('success', "Order #{$orderId} placed successfully.");
                header('Location: index.php?url=user/orders');
                exit();
            } catch (\Throwable $e) {
                $error = $e->getMessage();
            }
        }

        $this->render('checkout', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'itemsCount' => $itemsCount,
            'user' => $user,
            'rooms' => $rooms,
            'selectedRoomId' => $selectedRoomId,
            'error' => $error,
        ]);
    }

    public function myOrders() {
        $userId = $this->requireLogin();
        $orders = $this->orderModel->getOrdersByUser($userId);
        $orderRules = $this->buildOrderRules($orders);

        $this->render('my_orders', [
            'orders' => $orders,
            'orderRules' => $orderRules,
        ]);
    }

    public function cancelOrder() {
        $userId = $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=user/orders');
            exit();
        }

        $orderId = (int) ($_POST['order_id'] ?? 0);
        $cancelReason = trim((string) ($_POST['cancel_reason'] ?? ''));
        if (!$orderId) {
            $this->flash('danger', 'Order not found.');
            header('Location: index.php?url=user/orders');
            exit();
        }

        if (mb_strlen($cancelReason) < 5) {
            $this->flash('danger', 'Please provide a cancellation reason with at least 5 characters.');
            header('Location: index.php?url=user/order&id=' . $orderId);
            exit();
        }

        $order = $this->orderModel->getOrderByUser($userId, $orderId);
        if (!$order) {
            $this->flash('danger', 'Order not found.');
            header('Location: index.php?url=user/orders');
            exit();
        }

        if (!$this->canCancelOrder($order)) {
            $this->flash('danger', 'This order can no longer be cancelled. Orders can only be cancelled while processing and within 15 minutes of placement.');
            header('Location: index.php?url=user/order&id=' . $orderId);
            exit();
        }

        $this->orderModel->cancelOrderForUser($userId, $orderId);
        $this->flash('success', 'Order cancelled successfully. Reason: ' . $cancelReason);

        header('Location: index.php?url=user/orders');
        exit();
    }

    public function orderDetails() {
        $userId = $this->requireLogin();
        $orderId = (int) ($_GET['id'] ?? 0);

        if (!$orderId) {
            $this->flash('danger', 'Order not found.');
            header('Location: index.php?url=user/orders');
            exit();
        }

        $order = $this->orderModel->getOrderByUser($userId, $orderId);
        if (!$order) {
            $this->flash('danger', 'Order not found.');
            header('Location: index.php?url=user/orders');
            exit();
        }

        $items = $this->orderModel->getOrderItems($orderId);

        $this->render('order_detail', [
            'order' => $order,
            'items' => $items,
        ]);
    }

    private function render($viewName, $data = []) {
        $currentUrl = $_GET['url'] ?? 'user/home';
        $flash = $this->consumeFlash();

        extract($data);

        require ROOT_PATH . '/views/user/partials/header.php';
        require ROOT_PATH . '/views/user/partials/navbar.php';
        require ROOT_PATH . '/views/user/partials/alerts.php';
        require ROOT_PATH . "/views/user/{$viewName}.php";
        require ROOT_PATH . '/views/user/partials/footer.php';
    }

    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?url=login');
            exit();
        }

        return (int) $_SESSION['user_id'];
    }

    private function flash($type, $message) {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message,
        ];
    }

    private function consumeFlash() {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        return $flash;
    }

    private function getCart() {
        return $_SESSION['cart'] ?? [];
    }

    private function saveCart(array $cart) {
        $_SESSION['cart'] = $cart;
    }

    private function clearCart() {
        unset($_SESSION['cart']);
    }

    private function countCartItems() {
        $cart = $this->getCart();
        $count = 0;

        foreach ($cart as $item) {
            $count += max(1, (int) ($item['quantity'] ?? 1));
        }

        return $count;
    }

    private function canCancelOrder(array $order) {
        $status = strtolower((string) ($order['status'] ?? ''));
        if ($status !== 'processing') {
            return false;
        }

        $createdAt = strtotime((string) ($order['created_at'] ?? ''));
        if (!$createdAt) {
            return false;
        }

        return (time() - $createdAt) <= (15 * 60);
    }

    private function buildOrderRules(array $orders) {
        $rules = [];

        foreach ($orders as $order) {
            $rules[$order['id']] = $this->canCancelOrder($order);
        }

        return $rules;
    }

    private function buildCartSummary() {
        $cart = $this->getCart();
        $cartItems = [];
        $subtotal = 0;
        $itemsCount = 0;

        foreach ($cart as $item) {
            $productId = (int) ($item['product_id'] ?? 0);
            $product = $this->catalogModel->getProductById($productId);

            if (!$product || strtolower($product['status'] ?? '') !== 'available') {
                continue;
            }

            $quantity = max(1, min(20, (int) ($item['quantity'] ?? 1)));
            $unitPrice = (float) $product['price'];
            $lineTotal = $unitPrice * $quantity;

            $cartItems[] = [
                'product_id' => $productId,
                'name' => $product['product_name'],
                'image' => $product['product_img'],
                'quantity' => $quantity,
                'price' => $unitPrice,
                'line_total' => $lineTotal,
            ];

            $subtotal += $lineTotal;
            $itemsCount += $quantity;
        }

        return [$cartItems, $subtotal, $itemsCount];
    }
}