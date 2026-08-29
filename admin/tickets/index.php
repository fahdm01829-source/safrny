<?php
include($_SERVER['DOCUMENT_ROOT'] . '/safrny/shared/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resolve_ticket'])) {

    $ticketId = (int) $_POST['resolve_ticket'];

    $query = "
        UPDATE support_tickets
        SET status = 'Resolved'
        WHERE id = ?
        AND status IN ('Open', 'In Progress')
    ";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $ticketId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$query = "SELECT * FROM support_tickets";
$tickets = mysqli_query($conn, $query);
$query = "SELECT support_tickets.*, users.fullname, users.email, users.phone, users.country FROM support_tickets INNER JOIN users ON support_tickets.user_id = users.id ORDER BY support_tickets.created_at DESC";
$tickets = mysqli_query($conn, $query);
$ticketCount = mysqli_num_rows($tickets);

$openQuery = "SELECT COUNT(*) AS total FROM support_tickets WHERE status = 'Open'";
$openResult = mysqli_query($conn, $openQuery);
$openCount = mysqli_fetch_assoc($openResult)['total'];

$inProgressQuery = "SELECT COUNT(*) AS total FROM support_tickets WHERE status = 'In Progress'";
$inProgressResult = mysqli_query($conn, $inProgressQuery);
$inProgressCount = mysqli_fetch_assoc($inProgressResult)['total'];

$resolvedQuery = "SELECT COUNT(*) AS total FROM support_tickets WHERE status = 'Resolved'";
$resolvedResult = mysqli_query($conn, $resolvedQuery);
$resolvedCount = mysqli_fetch_assoc($resolvedResult)['total'];

$closedQuery = "SELECT COUNT(*) AS total FROM support_tickets WHERE status = 'Closed'";
$closedResult = mysqli_query($conn, $closedQuery);
$closedCount = mysqli_fetch_assoc($closedResult)['total'];
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
</head>

<body>
    <!-- <?php include('../../shared/nav.php') ?> -->
    <!-- <?php include '../../shared/sidepar.php'; ?> -->
    <div class="main-content">
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="headerContent w-100">
                    <h1 class="title">Support Tickets</h1>
                    <p><span><?php echo $ticketCount ?></span> total · <?php echo $openCount; ?> open</p>
                </div>
                <div class="ticket-stats">
                    <div class="ticket-stat open-stat">
                        <div class="ticket-stat-number">
                            <?php echo $openCount; ?>
                        </div>
                        <div class="ticket-stat-label">Open</div>
                    </div>
                    <div class="ticket-stat progress-stat">
                        <div class="ticket-stat-number">
                            <?php echo $inProgressCount; ?>
                        </div>
                        <div class="ticket-stat-label">In Progress</div>
                    </div>
                    <div class="ticket-stat resolved-stat">
                        <div class="ticket-stat-number">
                            <?php echo $resolvedCount; ?>
                        </div>
                        <div class="ticket-stat-label">Resolved</div>
                    </div>
                    <div class="ticket-stat closed-stat">
                        <div class="ticket-stat-number">
                            <?php echo $closedCount; ?>
                        </div>
                        <div class="ticket-stat-label">Closed</div>
                    </div>
                </div>
                <div class="col-12 py-3">
                    <div class="card border-0 search-card mb-4">
                        <div class="card-body p-3">
                            <div class="search-container">
                                <i class="bi bi-search"></i>
                                <input type="text" id="tableSearchInput" placeholder="Search tickets...">
                                <select id="statusFilter">
                                    <option value="all">All</option>
                                    <option value="Open">Open</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Resolved">Resolved</option>
                                    <option value="Closed">Closed</option>
                                </select>
                                <select id="priorityFilter">
                                    <option value="all">All</option>
                                    <option value="High">High</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Low">Low</option>
                                </select>
                                <select id="categoryFilter">
                                    <option value="all">All</option>
                                    <option value="Booking">Booking</option>
                                    <option value="Refund">Refund</option>
                                    <option value="General">General</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card border-0">
                        <div class="card-body px-0 py-0">
                            <div class="table-responsive">
                                <table class="filterable-table table-responsive" id="myFilterableTable">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="ps-4">TICKET ID</th>
                                            <th scope="col">CUSTOMER</th>
                                            <th scope="col">SUBJECT</th>
                                            <th scope="col">CATEGORY</th>
                                            <th scope="col">RELATED BOOKING</th>
                                            <th scope="col">CREATED</th>
                                            <th scope="col">LAST UPDATE</th>
                                            <th scope="col">PRIORITY</th>
                                            <th scope="col">STATUS</th>
                                            <th class="text-center pe-4" scope="col">ACTIONS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($tickets as $ticket) { ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <span class="ticket-id">
                                                        <?php echo htmlspecialchars($ticket['ticket_number']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <div class="ticket-name">
                                                            <?php echo htmlspecialchars($ticket['fullname']); ?>
                                                        </div>
                                                        <div class="ticket-email">
                                                            <?php echo htmlspecialchars($ticket['email']); ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="ticket-subject">
                                                        <?php echo htmlspecialchars($ticket['subject']); ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="category-badge">
                                                        <?php echo htmlspecialchars($ticket['category']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($ticket['booking_id'])) { ?>
                                                        <a href="../bookings/info.php?id=<?php echo $ticket['booking_id']; ?>"
                                                            class="booking-link">
                                                            BK-<?php echo str_pad($ticket['booking_id'], 6, '0', STR_PAD_LEFT); ?>
                                                        </a>
                                                    <?php } else { ?>
                                                        <span class="no-booking">-</span>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?php echo date('Y-m-d H:i', strtotime($ticket['created_at'])); ?>
                                                </td>
                                                <td>
                                                    <?php echo date('Y-m-d H:i', strtotime($ticket['updated_at'])); ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $priorityClass = '';
                                                    if ($ticket['priority'] === 'High') {
                                                        $priorityClass = 'priority-high';
                                                    } elseif ($ticket['priority'] === 'Medium') {
                                                        $priorityClass = 'priority-medium';
                                                    } elseif ($ticket['priority'] === 'Low') {
                                                        $priorityClass = 'priority-low';
                                                    }
                                                    ?>
                                                    <span class="priority-badge <?php echo $priorityClass; ?>">
                                                        <span class="priority-dot"></span>
                                                        <?php echo htmlspecialchars($ticket['priority']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php
                                                    $statusClass = '';
                                                    if ($ticket['status'] === 'Open') {
                                                        $statusClass = 'status-open';
                                                    } elseif ($ticket['status'] === 'In Progress') {
                                                        $statusClass = 'status-progress';
                                                    } elseif ($ticket['status'] === 'Resolved') {
                                                        $statusClass = 'status-resolved';
                                                    } elseif ($ticket['status'] === 'Closed') {
                                                        $statusClass = 'status-closed';
                                                    }
                                                    ?>
                                                    <span class="ticket-status <?php echo $statusClass; ?>">
                                                        <?php echo htmlspecialchars($ticket['status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="ticket-actions">
                                                        <a href="./info.php?id=<?php echo $ticket['id']; ?>"
                                                            class="ticket-action-view" title="View Ticket">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <?php if (
                                                            $ticket['status'] === 'Open' ||
                                                            $ticket['status'] === 'In Progress'
                                                        ) { ?>
                                                            <form method="POST" class="ticket-resolve-form">
                                                                <input type="hidden" name="resolve_ticket"
                                                                    value="<?php echo $ticket['id']; ?>">

                                                                <button type="submit" class="ticket-action-resolve"
                                                                    title="Resolve Ticket">
                                                                    <i class="bi bi-check-circle"></i>
                                                                </button>
                                                            </form>
                                                        <?php } ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <tr class="no-results">
                                            <td colspan="10">
                                                <div class="empty-state">
                                                    <i class="bi bi-ticket-perforated"></i>
                                                    <span>No tickets found</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="table-footer">
                                    Showing <span id="visibleTicketsCount"><?php echo $ticketCount; ?></span> of
                                    <?php echo $ticketCount; ?> tickets
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

        function enableTableFilter(inputEl, statusEl, priorityEl, categoryEl, tableEl) {
            function filterTable() {
                const query = inputEl.value.toLowerCase().trim();
                const status = statusEl.value;
                const priority = priorityEl.value;
                const category = categoryEl.value;
                const rows = tableEl.querySelectorAll(
                    'tbody tr:not(.no-results)'
                );
                const noResultsRow = tableEl.querySelector('.no-results');
                let visibleCount = 0;
                rows.forEach(function (row) {
                    const rowText = row.textContent.toLowerCase();
                    const matchesSearch = rowText.includes(query);
                    const rowStatus = row.querySelector('.ticket-status');
                    const rowPriority = row.querySelector('.priority-badge');
                    const rowCategory = row.querySelector('.category-badge');
                    const matchesStatus =
                        status === 'all' ||
                        (
                            rowStatus && rowStatus.textContent.trim() === status
                        );
                    const matchesPriority =
                        priority === 'all' ||
                        (
                            rowPriority && rowPriority.textContent.trim().includes(priority)
                        );

                    const matchesCategory =
                        category === 'all' ||
                        (
                            rowCategory && rowCategory.textContent.trim() === category
                        );
                    const visible = matchesSearch && matchesStatus && matchesPriority && matchesCategory;
                    row.style.display = visible ? '' : 'none';
                    if (visible) {
                        visibleCount++;
                    }
                });
                noResultsRow.style.display = visibleCount === 0 ? 'table-row' : 'none';
                document.getElementById('visibleTicketsCount').textContent = visibleCount;
            }
            inputEl.addEventListener('input', filterTable);
            statusEl.addEventListener('change', filterTable);
            priorityEl.addEventListener('change', filterTable);
            categoryEl.addEventListener('change', filterTable);
            filterTable();
        }
        const searchInput = document.getElementById('tableSearchInput');
        const statusFilter = document.getElementById('statusFilter');
        const priorityFilter = document.getElementById('priorityFilter');
        const categoryFilter = document.getElementById('categoryFilter');
        const dataTable = document.getElementById('myFilterableTable');
        if (searchInput && statusFilter && priorityFilter && categoryFilter && dataTable) {
            enableTableFilter(searchInput, statusFilter, priorityFilter, categoryFilter, dataTable);
        }
    </script>
    <script src="/safrny/assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>