<?php

include($_SERVER['DOCUMENT_ROOT'] . '/safrny/shared/db.php');
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$query = "SELECT * FROM users WHERE id = $id";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) === 0) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['change_status'])) {

        $newStatus = $_POST['status'];

        $allowedStatuses = ['Active', 'Inactive', 'Suspended'];

        if (in_array($newStatus, $allowedStatuses)) {

            $updateQuery = "UPDATE users 
                            SET status = '$newStatus' 
                            WHERE id = $id";

            mysqli_query($conn, $updateQuery);
        }

        header("Location: info.php?id=$id");
        exit;
    }


    if (isset($_POST['delete_account'])) {

        $deleteQuery = "DELETE FROM users WHERE id = $id";

        mysqli_query($conn, $deleteQuery);

        header("Location: index.php");
        exit;
    }
}

$user = mysqli_fetch_assoc($result);
$nameParts = explode(' ', trim($user['fullname']));
$initials = strtoupper(
    substr($nameParts[0], 0, 1) .
    substr($nameParts[count($nameParts) - 1], 0, 1)
);
$userCode = 'USR-' . str_pad($user['id'], 3, '0', STR_PAD_LEFT);
$memberSince = date('Y-m-d', strtotime($user['created_at']));
$lastLogin = !empty($user['last_login'])
    ? date('Y-m-d', strtotime($user['last_login']))
    : 'Never';
$statusClass =
    $user['status'] === 'Active'
    ? 'status'
    : ($user['status'] === 'Inactive'
        ? 'statusInactive'
        : 'statusSuspended');

$activeChecked = $user['status'] === 'Active' ? 'checked' : '';
$inactiveChecked = $user['status'] === 'Inactive' ? 'checked' : '';
$suspendedChecked = $user['status'] === 'Suspended' ? 'checked' : '';

$bookingCount = 0;
$supportCount = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safrny Admin Dashboard</title>
    <link rel="icon" type="image/png" href="/safrny/public/favicon.png">
    <link rel="stylesheet" href="/safrny/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/safrny/assets/css/users.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/safrny/assets/css/user-info.css">
</head>

<body>

    <div class="main-content">
        <div class="profile-container">
            <div class="profile-header">
                <a href="index.php" class="back-button">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1>User Profile</h1>
            </div>
            <div class="user-summary">
                <div class="user-summary-info">
                    <div class="profile-avatar">
                        <?php echo $initials; ?>
                    </div>
                    <div class="profile-main-info">
                        <div class="profile-name-row">
                            <h2>
                                <?php echo htmlspecialchars($user['fullname']); ?>
                            </h2>
                            <span class="<?php echo $statusClass; ?>">
                                <?php echo htmlspecialchars($user['status']); ?>
                            </span>
                        </div>
                        <div class="profile-email">
                            <?php echo htmlspecialchars($user['email']); ?>
                            <span>·</span>
                            <?php echo htmlspecialchars($user['country']); ?>
                        </div>
                        <div class="profile-meta">
                            <?php echo $userCode; ?>
                            <span>·</span>
                            Member since <?php echo $memberSince; ?>
                        </div>
                    </div>
                </div>
                <div class="profile-stats">
                    <div class="profile-stat">
                        <div class="profile-stat-number">
                            0
                        </div>
                        <div class="profile-stat-label">
                            Bookings
                        </div>
                    </div>
                    <div class="profile-stat">
                        <div class="profile-stat-number">
                            $0
                        </div>
                        <div class="profile-stat-label">
                            Total Spend
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-tabs">
                <button type="button" class="profile-tab active" data-tab="profile">
                    Profile
                </button>
                <button type="button" class="profile-tab" data-tab="bookings">
                    Bookings
                    <span class="tab-count">0</span>
                </button>
                <button type="button" class="profile-tab" data-tab="support">
                    Support
                    <span class="tab-count">0</span>
                </button>
            </div>
            <div class="profile-content">
                <div class="tab-content active" id="profileTab">
                    <div class="profile-card">
                        <h3>Contact Information</h3>
                        <div class="profile-divider"></div>
                        <div class="info-row">
                            <span class="info-label">
                                Full Name
                            </span>
                            <span class="info-value">
                                <?php echo htmlspecialchars($user['fullname']); ?>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">
                                Email
                            </span>
                            <span class="info-value email-value">
                                <?php echo htmlspecialchars($user['email']); ?>
                            </span>

                        </div>
                        <div class="info-row">
                            <span class="info-label">
                                Phone
                            </span>
                            <span class="info-value">
                                <?php echo htmlspecialchars($user['phone']); ?>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">
                                Country
                            </span>
                            <span class="info-value">
                                <?php echo htmlspecialchars($user['country']); ?>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">
                                Nationality
                            </span>
                            <span class="info-value">
                                <?php echo htmlspecialchars($user['nationality']); ?>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">
                                Address
                            </span>
                            <span class="info-value">
                                <?php echo htmlspecialchars($user['address']); ?>
                            </span>
                        </div>
                    </div>
                    <div class="profile-card">
                        <h3>Account Details</h3>
                        <div class="profile-divider"></div>
                        <div class="info-row">
                            <span class="info-label">
                                User ID
                            </span>
                            <span class="info-value">
                                <?php echo $userCode; ?>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">
                                Member Since
                            </span>
                            <span class="info-value">
                                <?php echo $memberSince; ?>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Last Login</span>
                            <span class="info-value">
                                <?php echo $lastLogin; ?>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Account Status</span>
                            <span class="info-value">
                                <span class="<?php echo $statusClass; ?>">
                                    <?php echo htmlspecialchars($user['status']); ?>
                                </span>
                            </span>
                        </div>
                        <div class="account-actions">
                            <form method="POST">
                                <button type="button" class="change-status-btn" id="changeStatusBtn">
                                    Change Status
                                </button>
                                <div class="status-editor" id="statusEditor">
                                    <label>
                                        <input type="radio" name="status" value="Active" <?php echo $activeChecked; ?>>
                                        <span>Set to Active</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="status" value="Inactive" <?php echo $inactiveChecked; ?>>
                                        <span>Set to Inactive</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="status" value="Suspended" <?php echo $suspendedChecked; ?>>
                                        <span>Set to Suspended</span>
                                    </label>
                                    <button type="submit" name="change_status" class="save-status-btn">
                                        Save
                                    </button>
                                </div>
                            </form>
                            <form method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this account?');">
                                <button type="submit" name="delete_account" class="delete-account-btn">
                                    Delete Account
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-content" id="bookingsTab">
                    <div class="empty-state-card">
                        <div class="empty-state-icon"><i class="bi bi-calendar-x"></i></div>
                        <h3>No bookings yet</h3>
                        <p>This user hasn't made any bookings yet.</p>
                    </div>
                </div>
                <div class="tab-content" id="supportTab">
                    <div class="empty-state-card">
                        <div class="empty-state-icon"><i class="bi bi-headset"></i></div>
                        <h3>No support tickets</h3>
                        <p>This user hasn't contacted support yet.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const changeStatusBtn = document.getElementById('changeStatusBtn');
        const statusEditor = document.getElementById('statusEditor');

        changeStatusBtn.addEventListener('click', function () {
            statusEditor.classList.add('show');
            changeStatusBtn.style.display = 'none';
        });
    </script>
    <script>

        const profileTabs = document.querySelectorAll('.profile-tab');

        const tabContents = document.querySelectorAll('.tab-content');


        profileTabs.forEach(tab => {

            tab.addEventListener('click', function () {

                const targetTab = this.dataset.tab;


                profileTabs.forEach(item => {
                    item.classList.remove('active');
                });


                tabContents.forEach(content => {
                    content.classList.remove('active');
                });


                this.classList.add('active');


                document
                    .getElementById(targetTab + 'Tab')
                    .classList.add('active');

            });

        });

    </script>
</body>

</html>