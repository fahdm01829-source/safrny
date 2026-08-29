<?php

include($_SERVER['DOCUMENT_ROOT'] . '/safrny/shared/db.php');

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['change_status'])) {
        $newStatus = $_POST['status'] ?? '';
        $allowedStatuses = ['Open', 'In Progress', 'Resolved', 'Closed'];

        if (in_array($newStatus, $allowedStatuses, true)) {
            $stmt = mysqli_prepare($conn, "UPDATE support_tickets SET status = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "si", $newStatus, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        header("Location: info.php?id=" . $id);
        exit;
    }


    if (isset($_POST['change_priority'])) {
        $newPriority = $_POST['priority'] ?? '';
        $allowedPriorities = ['Low', 'Medium', 'High'];
        if (in_array($newPriority, $allowedPriorities, true)) {
            $stmt = mysqli_prepare($conn, "UPDATE support_tickets SET priority = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "si", $newPriority, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        header("Location: info.php?id=" . $id);
        exit;
    }


    if (isset($_POST['resolve_ticket'])) {
        $stmt = mysqli_prepare($conn, "UPDATE support_tickets SET status = 'Resolved' WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: info.php?id=" . $id);
        exit;
    }


    if (isset($_POST['send_reply'])) {
        $message = trim($_POST['message'] ?? '');
        if ($message !== '') {
            $senderType = 'admin';
            $senderId = 1;
            $stmt = mysqli_prepare($conn, "INSERT INTO support_messages (ticket_id, sender_type, sender_id, message) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "isis", $id, $senderType, $senderId, $message);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $stmt = mysqli_prepare($conn, "UPDATE support_tickets SET updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        header("Location: info.php?id=" . $id);
        exit;
    }
}


$stmt = mysqli_prepare($conn, "SELECT support_tickets.*, users.fullname, users.email, users.phone, users.country FROM support_tickets INNER JOIN users ON support_tickets.user_id = users.id WHERE support_tickets.id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
if (!$result || mysqli_num_rows($result) === 0) {
    mysqli_stmt_close($stmt);
    header("Location: index.php");
    exit;
}
$ticket = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);
$stmt = mysqli_prepare($conn, "SELECT id, ticket_id, sender_type, sender_id, message, created_at FROM support_messages WHERE ticket_id = ? ORDER BY created_at ASC");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$messagesResult = mysqli_stmt_get_result($stmt);

$messages = [];

while ($message = mysqli_fetch_assoc($messagesResult)) {
    $messages[] = $message;
}

mysqli_stmt_close($stmt);

$nameParts = explode(' ', trim($ticket['fullname']));

$initials = strtoupper(
    substr($nameParts[0], 0, 1) .
    substr($nameParts[count($nameParts) - 1], 0, 1)
);

$ticketDate = date(
    'Y-m-d H:i',
    strtotime($ticket['created_at'])
);

$updatedDate = date(
    'Y-m-d H:i',
    strtotime($ticket['updated_at'])
);

$statusClass = '';
if ($ticket['status'] === 'Open') {
    $statusClass = 'ticket-status-open';
} elseif ($ticket['status'] === 'In Progress') {
    $statusClass = 'ticket-status-progress';
} elseif ($ticket['status'] === 'Resolved') {
    $statusClass = 'ticket-status-resolved';
} elseif ($ticket['status'] === 'Closed') {
    $statusClass = 'ticket-status-closed';
}

$priorityClass = '';
if ($ticket['priority'] === 'High') {
    $priorityClass = 'priority-high';
} elseif ($ticket['priority'] === 'Medium') {
    $priorityClass = 'priority-medium';
} elseif ($ticket['priority'] === 'Low') {
    $priorityClass = 'priority-low';
}

$statusOpenChecked = $ticket['status'] === 'Open' ? 'checked' : '';
$statusProgressChecked = $ticket['status'] === 'In Progress' ? 'checked' : '';
$statusResolvedChecked = $ticket['status'] === 'Resolved' ? 'checked' : '';
$statusClosedChecked = $ticket['status'] === 'Closed' ? 'checked' : '';
$priorityHighChecked = $ticket['priority'] === 'High' ? 'checked' : '';
$priorityMediumChecked = $ticket['priority'] === 'Medium' ? 'checked' : '';
$priorityLowChecked = $ticket['priority'] === 'Low' ? 'checked' : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safrny Admin Dashboard</title>
    <link rel="icon" type="image/png" href="/safrny/public/favicon.png">
    <link rel="stylesheet" href="/safrny/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/safrny/assets/css/tickets.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/safrny/assets/css/ticket-info.css">
</head>

<body>
    <div class="main-content">
        <div class="profile-container">
            <div class="profile-header">
                <a href="index.php" class="back-button">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="ticket-title-area">
                    <div class="ticket-meta">
                        <span class="ticket-number">
                            <?php echo htmlspecialchars($ticket['ticket_number']); ?>
                        </span>
                        <span class="<?php echo $statusClass; ?>">
                            <?php echo htmlspecialchars($ticket['status']); ?>
                        </span>
                        <span class="<?php echo $priorityClass; ?>">
                            <?php echo htmlspecialchars($ticket['priority']); ?> Priority
                        </span>
                    </div>
                    <h1>
                        <?php echo htmlspecialchars($ticket['subject']); ?>
                    </h1>
                </div>
            </div>
            <div class="ticket-layout">
                <div class="ticket-main">
                    <?php foreach ($messages as $message): ?>
                        <?php
                        $isAdmin = $message['sender_type'] === 'admin';
                        $messageInitials = $isAdmin ? 'OA' : $initials;
                        $messageName = $isAdmin ? 'Support Agent' : $ticket['fullname'];
                        $messageClass = $isAdmin ? 'message-card admin-message' : 'message-card user-message';
                        $avatarClass = $isAdmin ? 'message-avatar admin-avatar' : 'message-avatar user-message-avatar';
                        $messageDate = date(
                            'Y-m-d H:i',
                            strtotime($message['created_at'])
                        );
                        ?>
                        <div class="<?php echo $messageClass; ?>">
                            <div class="<?php echo $avatarClass; ?>">
                                <?php echo htmlspecialchars($messageInitials); ?>
                            </div>
                            <div class="message-content">
                                <div class="message-header">
                                    <div class="message-sender">
                                        <?php echo htmlspecialchars($messageName); ?>
                                        <?php if ($isAdmin): ?>
                                            <span class="support-agent-badge">Support Agent</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="message-date">
                                        <?php echo $messageDate; ?>
                                    </div>
                                </div>
                                <div class="message-text">
                                    <?php
                                    echo nl2br(
                                        htmlspecialchars($message['message'])
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="profile-card reply-card">
                        <h3>Reply to Customer</h3>
                        <div class="profile-divider"></div>
                        <form method="POST">
                            <textarea name="message" class="reply-textarea" placeholder="Type your reply..."
                                required></textarea>
                            <div class="reply-actions">
                                <button type="submit" name="send_reply" class="send-reply-btn"><i
                                        class="bi bi-send"></i> Send Reply</button>
                                <button type="submit" name="resolve_ticket" class="resolve-ticket-btn"><i
                                        class="bi bi-check2-circle"></i> Resolve</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="ticket-sidebar">
                    <div class="profile-card ticket-details-card">
                        <h3>Ticket Details</h3>
                        <div class="profile-divider"></div>
                        <div class="ticket-detail">
                            <span class="ticket-detail-label">Customer</span>
                            <span
                                class="ticket-detail-value"><?php echo htmlspecialchars($ticket['fullname']); ?></span>
                            <a href="mailto:<?php echo htmlspecialchars($ticket['email']); ?>"
                                class="ticket-email"><?php echo htmlspecialchars($ticket['email']); ?></a>
                        </div>
                        <div class="ticket-detail">
                            <span class="ticket-detail-label">Category</span>
                            <span class="category-badge"><?php echo htmlspecialchars($ticket['category']); ?></span>
                        </div>
                        <div class="ticket-detail">
                            <span class="ticket-detail-label">Related Booking</span>
                            <?php if (!empty($ticket['booking_id'])): ?>
                                <span
                                    class="ticket-link">BK-<?php echo str_pad($ticket['booking_id'], 6, '0', STR_PAD_LEFT); ?></span>
                            <?php else: ?>
                                <span class="ticket-detail-value">
                                    N/A
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="ticket-detail">
                            <span class="ticket-detail-label">Created</span>
                            <span class="ticket-detail-value"><?php echo $ticketDate; ?></span>
                        </div>
                        <div class="ticket-detail">
                            <span class="ticket-detail-label">Last Update</span>
                            <span class="ticket-detail-value"><?php echo $updatedDate; ?></span>
                        </div>
                    </div>
                    <div class="profile-card status-card">
                        <h3>Change Status</h3>
                        <div class="profile-divider"></div>
                        <form method="POST" class="option-form">
                            <label class="option-item status-option-open">
                                <input type="radio" name="status" value="Open" <?php echo $statusOpenChecked; ?>>
                                <span>Open</span>
                            </label>
                            <label class="option-item status-option-progress">
                                <input type="radio" name="status" value="In Progress" <?php echo $statusProgressChecked; ?>>
                                <span>In Progress</span>
                            </label>
                            <label class="option-item status-option-resolved">
                                <input type="radio" name="status" value="Resolved" <?php echo $statusResolvedChecked; ?>>
                                <span>Resolved</span>
                            </label>
                            <label class="option-item status-option-closed">
                                <input type="radio" name="status" value="Closed" <?php echo $statusClosedChecked; ?>>
                                <span>Closed</span>
                            </label>
                            <button type="submit" name="change_status" class="save-option-btn">Save</button>
                        </form>
                    </div>
                    <div class="profile-card priority-card">
                        <h3>Change Priority</h3>
                        <div class="profile-divider"></div>
                        <form method="POST" class="option-form">
                            <label class="option-item priority-option-high">
                                <input type="radio" name="priority" value="High" <?php echo $priorityHighChecked; ?>>
                                <span>High</span>
                            </label>
                            <label class="option-item priority-option-medium">
                                <input type="radio" name="priority" value="Medium" <?php echo $priorityMediumChecked; ?>>
                                <span>Medium</span>
                            </label>
                            <label class="option-item priority-option-low">
                                <input type="radio" name="priority" value="Low" <?php echo $priorityLowChecked; ?>>
                                <span>Low</span>
                            </label>
                            <button type="submit" name="change_priority" class="save-option-btn">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.option-form').forEach(form => {

            const radios = form.querySelectorAll('input[type="radio"]');

            function updateSelected() {
                radios.forEach(radio => {
                    radio.closest('.option-item').classList.toggle(
                        'selected',
                        radio.checked
                    );
                });
            }

            radios.forEach(radio => {
                radio.addEventListener('change', updateSelected);
            });

            updateSelected();
        });
    </script>
</body>

</html>