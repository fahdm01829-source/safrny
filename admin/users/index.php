<?php
include($_SERVER['DOCUMENT_ROOT'] . '/safrny/shared/db.php');
$query = "SELECT * FROM users";
$users = mysqli_query($conn, $query);
$userCount = mysqli_num_rows($users);
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
</head>

<body>
    <!-- <?php include('../../shared/nav.php') ?> -->
    <!-- <?php include '../../shared/sidepar.php'; ?> -->
    <div class="main-content">
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="headerContent w-100">
                    <h1 class="title">Users</h1>
                    <p><span><?php echo $userCount ?></span> registered users · $<span>298,000</span> total spend</p>
                </div>
                <div class="col-12 py-3">
                    <div class="card border-0 search-card mb-4">
                        <div class="card-body p-3">
                            <div class="search-container">
                                <i class="bi bi-search"></i>
                                <input type="text" id="tableSearchInput"
                                    placeholder="Search by name, email, country, or ID...">
                                <select id="statusFilter">
                                    <option value="all">All</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Suspended">Suspended</option>
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
                                            <th scope="col" class="ps-4">USER</th>
                                            <th scope="col">EMAIL</th>
                                            <th scope="col">PHONE</th>
                                            <th scope="col">COUNTRY</th>
                                            <th scope="col">REGISTRATION DATE</th>
                                            <th scope="col" class="text-center">TOTAL BOOKINGS</th>
                                            <th scope="col" class="text-center">TOTAL SPEND</th>
                                            <th scope="col">STATUS</th>
                                            <th class="text-center pe-4" scope="col">ACTIONS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $user) { ?>
                                            <?php
                                            $nameParts = explode(' ', trim($user['fullname']));
                                            $initials = strtoupper(
                                                substr($nameParts[0], 0, 1) .
                                                substr($nameParts[count($nameParts) - 1], 0, 1)
                                            );
                                            $userCode = 'USR-' . str_pad($user['id'], 3, '0', STR_PAD_LEFT);
                                            ?>
                                            <tr>
                                                <td class="name ps-4">
                                                    <div class="d-flex gap-3">
                                                        <div class="user-avatar">
                                                            <?php echo $initials; ?>
                                                        </div>

                                                        <div class="d-flex flex-column">
                                                            <div class="user-name">
                                                                <?php echo $user['fullname']; ?>
                                                            </div>

                                                            <div class="user-id">
                                                                <?php echo $userCode; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php echo $user['email'] ?>
                                                </td>
                                                <td>
                                                    <?php echo $user['phone'] ?>
                                                </td>
                                                <td>
                                                    <?php echo $user['country'] ?>
                                                </td>
                                                <td>
                                                    <?php echo $user['created_at'] ?>
                                                </td>
                                                <td class="text-center">
                                                    0
                                                </td>
                                                <td class="text-center">
                                                    $0
                                                </td>
                                                <td>
                                                    <div>
                                                        <span
                                                            class="<?php echo $user['status'] == 'Active' ? 'status' : ($user['status'] == 'Inactive' ? 'statusInactive' : 'statusSuspended') ?>">
                                                            <?php echo $user['status'] == 'Active' ? 'Active' : ($user['status'] == 'Inactive' ? 'Inactive' : 'Suspended'); ?>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center pe-4">
                                                        <a href="./info.php?id=<?php echo $user['id'] ?>" class="fs-6"><i
                                                                class="bi bi-eye"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <tr class="no-results">
                                            <td colspan="9">
                                                <div class="empty-state">
                                                    <i class="bi bi-people"></i>
                                                    <span>No users found</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="table-footer">
                                    Showing <span id="visibleUsersCount"><?php echo $userCount; ?></span> of
                                    <?php echo $userCount; ?> users
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function enableTableFilter(inputEl, selectEl, tableEl) {
            function filterTable() {
                const query = inputEl.value.toLowerCase();
                const status = selectEl.value;
                const rows = tableEl.querySelectorAll('tbody tr:not(.no-results)');
                let visibleCount = 0;
                const noResultsRow = tableEl.querySelector('.no-results');
                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const rowText = row.textContent.toLowerCase();

                    const matchesSearch = rowText.includes(query);

                    const rowStatus = row.querySelector('.status, .statusInactive, .statusSuspended');

                    const matchesStatus =
                        status === 'all' ||
                        (rowStatus && rowStatus.textContent.trim() === status);
                    if (matchesSearch && matchesStatus) {
                        visibleCount++;
                    }
                    row.style.display = matchesSearch && matchesStatus ? '' : 'none';
                }
                document.getElementById('visibleUsersCount').textContent = visibleCount;
                noResultsRow.style.display = visibleCount === 0 ? 'table-row' : 'none';
            }

            inputEl.addEventListener('keyup', filterTable);
            selectEl.addEventListener('change', filterTable);
            filterTable();
        }
        const searchInput = document.getElementById('tableSearchInput');
        const statusFilter = document.getElementById('statusFilter');
        const dataTable = document.getElementById('myFilterableTable');
        if (searchInput && statusFilter && dataTable) {
            enableTableFilter(searchInput, statusFilter, dataTable);
        }

    </script>
    <script src="/safrny/assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>