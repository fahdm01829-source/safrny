<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "tourism";
$conn = mysqli_connect($host, $user, $password, $db);

// Handle Delete Action
if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM users WHERE id = $deleteId");
    header("Location: index.php");
    exit();
}

// Handle Update Status Action
if (isset($_POST['update_status'])) {
    $userId = intval($_POST['user_id']);
    $newStatus = mysqli_real_escape_string($conn, $_POST['status']);
    mysqli_query($conn, "UPDATE users SET status = '$newStatus' WHERE id = $userId");
    header("Location: index.php");
    exit();
}

$query = "SELECT * FROM users";
$users = mysqli_query($conn, $query);
$userCount = mysqli_num_rows($users);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings</title>
    <link rel="stylesheet" href="../bootstrap.min.css">
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
        }

        .search-card {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0px 4px 25px rgba(0, 0, 0, 0.03);
        }

        .search-container {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .search-input-wrapper {
            position: relative;
            flex: 1;
        }

        .search-input-wrapper .bi-search {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
        }

        .search-container #tableSearchInput {
            width: 100%;
            padding: 12px 16px 12px 48px;
            border: 1px solid #e2e8f0;
            border-radius: 30px;
            font-size: 0.925rem;
            height: 48px;
            box-sizing: border-box;
            color: #334155;
            background-color: #fff;
        }

        .search-container #tableSearchInput::placeholder {
            color: #94a3b8;
        }

        .search-container #tableSearchInput:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .search-container select {
            min-width: 100px;
            height: 48px;
            padding: 0 16px;
            border: 1px solid #e2e8f0;
            border-radius: 30px;
            background-color: white;
            font-size: 0.9rem;
            color: #475569;
            cursor: pointer;
        }

        .table-card {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0px 4px 25px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .filterable-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .filterable-table thead th {
            padding: 16px 20px;
            background-color: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            text-align: left;
            font-weight: 700;
            color: #475569;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .filterable-table tbody td {
            padding: 16px 20px;
            font-size: 0.875rem;
            color: #475569;
            border-bottom: 1px solid #f8fafc;
            vertical-align: middle;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #1e3a8a;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.85rem;
            font-weight: 600;
            flex-shrink: 0;
        }

        .user-name {
            color: #0f172a;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-subtext {
            color: #94a3b8;
            font-size: 0.775rem;
        }

        /* Badges for status */
        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            text-align: center;
            cursor: pointer;
            border: none;
        }

        .status-active {
            color: #166534;
            background-color: #dcfce7;
        }

        .status-inactive {
            color: #475569;
            background-color: #f1f5f9;
        }

        .status-suspended {
            color: #991b1b;
            background-color: #fee2e2;
        }

        .action-btn {
            color: #64748b;
            width: 32px;
            height: 32px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .action-btn:hover {
            background-color: #f1f5f9;
            color: #0f172a;
        }

        .action-btn.delete:hover {
            background-color: #fee2e2;
            color: #ef4444;
        }
    </style>
</head>

<body>
    <div class="main-content">
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-12 py-3">
                    
                    <!-- Search Controls Section -->
                    <div class="card border-0 search-card mb-4">
                        <div class="card-body p-3">
                            <div class="search-container">
                                <div class="search-input-wrapper">
                                    <i class="bi bi-search"></i>
                                    <input type="text" id="tableSearchInput" placeholder="Search by ID, customer, email, or package...">
                                </div>
                                <select id="filterCategory">
                                    <option value="all">All</option>
                                </select>
                                <select id="statusFilter">
                                    <option value="all">All</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Suspended">Suspended</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="card border-0 table-card">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="filterable-table" id="myFilterableTable">
                                    <thead>
                                        <tr>
                                            <th>Booking ID</th>
                                            <th>Customer</th>
                                            <th>Package</th>
                                            <th>Travel Date</th>
                                            <th>Travelers</th>
                                            <th>Amount</th>
                                            <th>Payment</th>
                                            <th>Status</th>
                                            <th class="text-end pe-4">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $user) { 
                                            $nameParts = explode(' ', trim($user['fullname']));
                                            $initials = strtoupper(
                                                substr($nameParts[0], 0, 1) .
                                                (count($nameParts) > 1 ? substr($nameParts[count($nameParts) - 1], 0, 1) : '')
                                            );
                                            $bookingId = 'BK-' . str_pad($user['id'], 4, '0', STR_PAD_LEFT);
                                            $status = $user['status'] ?? 'Active';
                                        ?>
                                            <tr>
                                                <td class="fw-semibold text-dark"><?php echo $bookingId; ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="user-avatar"><?php echo $initials; ?></div>
                                                        <div class="d-flex flex-column">
                                                            <span class="user-name"><?php echo htmlspecialchars($user['fullname']); ?></span>
                                                            <span class="user-subtext"><?php echo htmlspecialchars($user['email']); ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($user['country'] ?? 'Standard'); ?></td>
                                                <td><?php echo date('M d, Y', strtotime($user['created_at'] ?? 'now')); ?></td>
                                                <td>1 Person</td>
                                                <td class="fw-semibold">$298.00</td>
                                                <td><span class="badge bg-light text-dark border">Credit Card</span></td>
                                                <td>
                                                    <!-- Fast Update Form for Status -->
                                                    <form method="POST" class="d-inline">
                                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                        <input type="hidden" name="update_status" value="1">
                                                        <select name="status" onchange="this.form.submit()" 
                                                            class="status-badge <?php 
                                                                echo $status == 'Active' ? 'status-active' : ($status == 'Inactive' ? 'status-inactive' : 'status-suspended'); 
                                                            ?>">
                                                            <option value="Active" <?php echo $status == 'Active' ? 'selected' : ''; ?>>Active</option>
                                                            <option value="Inactive" <?php echo $status == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                                                            <option value="Suspended" <?php echo $status == 'Suspended' ? 'selected' : ''; ?>>Suspended</option>
                                                        </select>
                                                    </form>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <a href="./info.php?id=<?php echo $user['id']; ?>" class="action-btn" title="View"><i class="bi bi-eye"></i></a>
                                                    <a href="./edit.php?edit=<?php echo $user['id']; ?>" class="action-btn" title="Edit"><i class="bi bi-pencil"></i></a>
                                                    <a href="./index.php?delete=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?');" class="action-btn delete" title="Delete"><i class="bi bi-trash"></i></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        // Real-time Search Filter
        document.getElementById('tableSearchInput').addEventListener('keyup', function () {
            const query = this.value.toLowerCase();
            const rows = document.querySelectorAll('#myFilterableTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });

        // Status Filter Dropdown
        document.getElementById('statusFilter').addEventListener('change', function () {
            const selectedStatus = this.value.toLowerCase();
            const rows = document.querySelectorAll('#myFilterableTable tbody tr');

            rows.forEach(row => {
                const statusSelect = row.querySelector('select[name="status"]');
                const statusValue = statusSelect ? statusSelect.value.toLowerCase() : '';
                
                if (selectedStatus === 'all' || statusValue === selectedStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>