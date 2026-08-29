<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "tourism";
$conn = mysqli_connect($host, $user, $password, $db);

$message = "";
$bookingData = null;

if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $selectQuery = "SELECT * FROM bookings WHERE id = $id";
    $result = mysqli_query($conn, $selectQuery);
    $bookingData = mysqli_fetch_assoc($result);
}

if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $booking_id = $_POST['booking_id'];
    $travel_date = $_POST['travel_date'];
    $travelers = intval($_POST['travelers']);
    $amount = floatval($_POST['amount']);
    $payment_status = $_POST['payment_status'];
    $status = $_POST['status'];

    $updateQuery = "UPDATE bookings SET 
                    booking_id='$booking_id', 
                    travel_date='$travel_date', 
                    travelers=$travelers, 
                    amount=$amount, 
                    payment_status='$payment_status', 
                    status='$status' 
                    WHERE id=$id";
    
    if (mysqli_query($conn, $updateQuery)) {
        header("Location: index.php");
        exit();
    } else {
        $message = "Error updating booking: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>
    <link rel="stylesheet" href="../bootstrap.min.css">
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #fafafa;
            margin: 0;
        }

        .card,
        .card-header {
            background-color: white !important;
            border-radius: 25px !important;
        }

        .card {
            border: 0;
            box-shadow: 0px 0px 35px -4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            border-color: #f1f5f9;
            font-size: 1.125rem;
            font-weight: 600;
        }

        .card-body label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #737373;
        }

        .card input::placeholder,
        .card select::placeholder {
            color: #90a1b9;
        }

        .card input:focus,
        .card select:focus {
            border-color: #408bfd !important;
            box-shadow: 0 0 0 2px #408bfd !important;
        }

        .container .title {
            font-family: "DM Serif Display", serif;
            font-size: 2rem !important;
            font-weight: 500;
        }

        .custom-input, .custom-select {
            padding: 10px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 15px;
            font-size: 0.95rem;
            box-sizing: border-box;
            width: 100%;
        }

        .headerContent p {
            font-size: 0.875rem;
            color: #737373;
            font-weight: 500;
        }

        .btn-custom-primary {
            background-color: #408bfd;
            color: white;
            border-radius: 15px;
            padding: 10px 24px;
            font-weight: 600;
            border: none;
            transition: all 0.2s;
        }

        .btn-custom-primary:hover {
            background-color: #294f91;
            color: white;
        }

        .btn-custom-secondary {
            background-color: #f1f5f9;
            color: #62748E;
            border-radius: 15px;
            padding: 10px 24px;
            font-weight: 600;
            border: none;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
        }

        .btn-custom-secondary:hover {
            background-color: #e2e8f0;
            color: #1e293b;
        }
    </style>
</head>

<body>
    <!-- <?php include('../../shared/nav.php') ?> -->
    <!-- <?php include '../../shared/sidepar.php'; ?> -->
    <div class="main-content">
        <div class="container mt-4 mb-5" style="max-width: 650px;">
            <div class="row justify-content-center">
                <!-- <?php include('../../shared/alert.php') ?> -->
                
                <div class="headerContent w-100 mb-3">
                    <h1 class="title">Edit Booking</h1>
                    <p>Update reservation details, payment, and status</p>
                </div>

                <div class="col-12">
                    <?php if ($message != ""): ?>
                        <div class="alert alert-danger rounded-4 mb-3"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <?php if ($bookingData): ?>
                        <div class="card p-4">
                            <form method="post" action="edit.php">
                                <input type="hidden" name="id" value="<?php echo $bookingData['id']; ?>">
                                
                                <div class="mb-3">
                                    <label class="form-label mb-2">Booking ID</label>
                                    <input type="text" name="booking_id" class="custom-input" value="<?php echo htmlspecialchars($bookingData['booking_id']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-2">Travel Date</label>
                                    <input type="date" name="travel_date" class="custom-input" value="<?php echo htmlspecialchars($bookingData['travel_date']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-2">Travelers Count</label>
                                    <input type="number" name="travelers" class="custom-input" value="<?php echo htmlspecialchars($bookingData['travelers']); ?>" min="1" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-2">Amount ($)</label>
                                    <input type="number" step="0.01" name="amount" class="custom-input" value="<?php echo htmlspecialchars($bookingData['amount']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-2">Payment Status</label>
                                    <select name="payment_status" class="custom-select">
                                        <option value="Paid" <?php echo $bookingData['payment_status'] == 'Paid' ? 'selected' : ''; ?>>Paid</option>
                                        <option value="Pending" <?php echo $bookingData['payment_status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Failed" <?php echo $bookingData['payment_status'] == 'Failed' ? 'selected' : ''; ?>>Failed</option>
                                        <option value="Refunded" <?php echo $bookingData['payment_status'] == 'Refunded' ? 'selected' : ''; ?>>Refunded</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label mb-2">Booking Status</label>
                                    <select name="status" class="custom-select">
                                        <option value="Confirmed" <?php echo $bookingData['status'] == 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                        <option value="Pending" <?php echo $bookingData['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Cancelled" <?php echo $bookingData['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        <option value="Completed" <?php echo $bookingData['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                    </select>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <a href="index.php" class="btn-custom-secondary">Cancel</a>
                                    <button type="submit" name="update" class="btn-custom-primary">Update Booking</button>
                                </div>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning rounded-4">Booking record not found or no ID specified.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>

</html>