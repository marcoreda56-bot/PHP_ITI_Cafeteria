<?php
use App\Models\Check;
$checks = new Check();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <title>Checks</title>
    </head>
    <body class="d-flex flex-column min-vh-100">
        <main class="flex-grow-1">
            <div class="container">
            <h1 class="text-center text-primary mb-4">Checks</h1>
            <div id="filters">
                <form method="GET" action="index.php">
                    <input type="text" name="url" value="admin/checks" hidden>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="user_id" class="form-label">User</label>
                            <select name="user_id" class="form-control">
                                <option value="">All Users</option>
                                <?php if(!empty($users)): ?>
                                    <?php foreach($users as $user): ?>
                                        <option value="<?= $user['id'] ?>" <?= (isset($one_user) && $one_user == $user['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($user['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Filter</button>
                    <a type="reset" href="index.php?url=admin/checks" class="btn btn-secondary mt-3">Reset filters</a>
                </form>
            </div>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger mt-3"><?= htmlspecialchars($error) ?></div>
                <?php exit; ?>
            <?php endif; ?>
            <div class="accordion accordion-flush mt-4" id="accordionFlushChecks">
                <?php if (empty($users_checks)): ?>
                    <div class="alert alert-info">No checks found for the selected criteria.</div>
                <?php endif; ?>
                <?php if(isset($one_user)):
                    $users_checks = array_filter($users_checks, function($user) use ($one_user) {
                        return $user['id'] == $one_user;
                    });
                endif; ?>
                <?php foreach ($users_checks as $index => $user): ?>
                    <div class="accordion-item border border-primary  mb-2">
                        
                        <!-- Header -->
                        <h2 class="accordion-header" id="flush-heading<?= $index ?>">
                            <button 
                                class="accordion-button collapsed" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#flush-collapse<?= $index ?>" 
                                aria-expanded="false" 
                                aria-controls="flush-collapse<?= $index ?>">
                                
                                <?= htmlspecialchars($user['name']) ?> 
                                - Total Paid: EGP <?= number_format($user['total_paid'], 2) ?>
                            </button>
                        </h2>

                        <!-- Collapse -->
                        <div 
                            id="flush-collapse<?= $index ?>" 
                            class="accordion-collapse collapse" 
                            aria-labelledby="flush-heading<?= $index ?>" 
                            data-bs-parent="#accordionFlushChecks">

                            <div class="accordion-body">
                                <?php
                                    $orders = $checks->getUserOrders($user['id']);
                                ?>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Order Number</th>
                                                <th>Total Price</th>
                                                <th>Status</th>
                                                <th>Ordered At</th>
                                                <th>Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($orders as $order): ?>
                                                <tr>
                                                    <td><?= $order['id'] ?></td>
                                                    <td>EGP <?= number_format($order['total_price'], 2) ?></td>
                                                    <td><?= htmlspecialchars($order['status']) ?></td>
                                                    <td><?= htmlspecialchars($order['created_at']) ?></td>
                                                    <td>
                                                        <button 
                                                            class="btn btn-sm btn-info" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#orderDetailsModal<?= $order['id'] ?>">
                                                            View Details
                                                        </button>

                                                        <!-- Modal -->
                                                        <div 
                                                            class="modal fade" 
                                                            id="orderDetailsModal<?= $order['id'] ?>" 
                                                            tabindex="-1" 
                                                            aria-labelledby="orderDetailsModalLabel<?= $order['id'] ?>" 
                                                            aria-hidden="true">
                                                            
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="orderDetailsModalLabel<?= $order['id'] ?>">Order #<?= $order['id'] ?> Details</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <?php
                                                                            $orderDetails = $checks->getOrderDetails($order['id']);
                                                                        ?>
                                                                        <table class="table table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Product Img</th>
                                                                                    <th>Quantity</th>
                                                                                    <th>Price</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php foreach ($orderDetails as $item): ?>
                                                                                    <tr>
                                                                                        <td><img src="<?= $item['product_img'] ?>" alt="<?= $item['product_name'] ?>" width="50"></td>
                                                                                        <td><?= $item['quantity'] ?></td>
                                                                                        <td>EGP <?= number_format($item['price'], 2) ?></td>
                                                                                    </tr>
                                                                                <?php endforeach; ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if(isset($one_user)) exit; ?>
            </div>
            </div>
        </main>
        <footer class="mt-auto">
            <div class="d-flex justify-content-center mt-3">
                <nav aria-label="Page navigation">
                    <a href=""></a>
                    <ul class="pagination">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link"
                            href="<?= ($page > 1)
                                    ? '?url=admin/checks&start_date=' . $start_date . '&end_date=' . $end_date . '&page=' . ($page - 1) 
                                    : '#' ?>"
                            tabindex="-1"
                            aria-disabled="<?= ($page <= 1) ? 'true' : 'false' ?>">
                                Previous
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= $i === (int)$page ? 'active' : '' ?>">
                                <a class="page-link" href="?url=admin/checks&start_date=<?= $start_date ?>&end_date=<?= $end_date ?>&page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                            <a class="page-link"
                            href="<?= ($page < $total_pages) 
                                    ? '?url=admin/checks&start_date=' . $start_date . '&end_date=' . $end_date . '&page=' . ($page + 1) 
                                    : '#' ?>">
                                Next
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </footer>
    </body>

</html>