<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "tourism";
$conn = mysqli_connect($host, $user, $password, $db);


$query = "SELECT * FROM users";
$users = mysqli_query($conn, $query);
$userCount = mysqli_num_rows($users);

$successMessage = "";
$errorMessage = "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
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
        }

        .card input::placeholder,
        .card textarea::placeholder {
            color: #90a1b9;
        }

        .card input:focus,
        .card textarea:focus {
            border-color: #408bfd !important;
            box-shadow: 0 0 0 2px #408bfd !important;
        }

        .container .title {
            font-family: "DM Serif Display", serif;
            font-size: 2rem !important;
            font-weight: 500;
        }

        .container .card-body .inputsGroup div {
            width: 48%;
        }

        .search-container {
            position: relative;
            display: flex;
            gap: 14px;
            align-items: center;
        }

        .search-container .bi-search {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #90a1b9;
        }

        .search-container #tableSearchInput {
            padding: 8px 12px;
            padding-left: 40px;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            font-size: 1rem;
            flex: 1;
            height: 52px;
            box-sizing: border-box;
        }

        .search-container #tableSearchInput::placeholder {
            color: #90a1b9;
        }

        .search-container #tableSearchInput:focus {
            outline: none !important;
            border-color: #408bfd !important;
            box-shadow: 0 0 0 2px #408bfd !important;
        }

        .search-container select {
            width: 125px;
            height: 52px;
            padding: 0 14px;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            background-color: white;
            font-size: 1rem;
        }

        .filterable-table {
            width: 100%;
            border-collapse: collapse;
            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                "Helvetica Neue",
                Arial,
                sans-serif;
        }

        .filterable-table thead th {
            padding: 12px;
            background-color: #fafafa;
            border-top: solid 1px #f1f5f9;
            border-bottom: solid 1px #f1f5f9;
            text-align: left;
            font-weight: 600;
            color: #737373;
            font-size: 0.75rem;
        }

        .filterable-table tbody td {
            padding: 12px;
            font-size: 0.875rem;
            color: #737373;
        }

        .filterable-table tbody .name {
            color: #1e293b;
            font-weight: 600;
        }

        .filterable-table tbody .status {
            color: #00c950;
            font-weight: 600;
            background-color: #dbfce7;
            height: 25px;
            width: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 20px;
            font-size: 0.75rem;
            border: 1px solid #00c950;
        }

        .filterable-table tbody .statusInactive {
            color: #737373;
            font-weight: 600;
            height: 25px;
            width: 60px;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 20px;
            font-size: 0.75rem;
            border: 1px solid #e5e5e5;
        }

        .filterable-table tbody .statusSuspended {
            color: #dc2626;
            font-weight: 600;
            height: 25px;
            width: 80px;
            background-color: #fee2e2;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 20px;
            font-size: 0.75rem;
            border: 1px solid #dc2626;
        }

        .container tbody td .bi-pencil,
        .container tbody td .bi-trash,
        .container tbody td .bi-eye{
            color: #62748E;
            width: 28px;
            height: 28px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 7px;
        }

        .container tbody td .bi-pencil:hover {
            background-color: #fef3c6;
            color: #e17100;

        }

        .container tbody td .bi-trash:hover {
            background-color: #ffe2e2;
            color: #eb232c;
        }

        .container tbody td .bi-eye:hover {
            background-color: #eef4fb;
            color: #244278;
        }

        .container tbody td a {
            text-decoration: none;
            background-color: transparent !important;
            border: none !important;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: #294f91;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.9rem;
            font-weight: 600;
            flex-shrink: 0;
        }

        .user-name {
            color: #1e293b;
            font-weight: 600;
        }

        .user-id {
            color: #94a3b8;
            font-size: 0.75rem;
            margin-top: 2px;
        }

        .headerContent p {
            font-size: 0.875rem;
            color: #737373;
            font-weight: 500;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="../bootstrap.min.css">
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
</head>

<body>
    <!-- <?php include('../../shared/nav.php') ?> -->
    <!-- <?php include '../../shared/sidepar.php'; ?> -->
    <div class="main-content">
        <div class="container mt-4">
            <div class="row justify-content-center">
                <!-- <?php include('../../shared/alert.php') ?> -->
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
                                            <th scope="col" class="ps-4">name</th>
                                            <th scope="col">destination</th>
                                            <th scope="col">category</th>
                                            <th scope="col">duration</th>
                                            <th scope="col">price</th>
                                          
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
                                                <td>
                                                    0
                                                </td>
                                                <td>
                                                    0
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
                                                    <div class="d-flex justify-content-center">
                                                        <a href="./info.php?id=<?php echo $user['id'] ?>"
                                                            class="fs-6 me-3"><i class="bi bi-eye"></i></a>
                                                        <a href="./edit.php?edit=<?php echo $user['id'] ?>"
                                                            class="fs-6 me-3"><i class="bi bi-pencil"></i></a>
                                                        <a href="./index.php?delete=<?php echo $user['id'] ?>"
                                                            class="fs-6"><i class="bi bi-trash"></i></a>
                                                    </div>
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
        function enableTableFilter(inputEl, tableEl) {
            inputEl.addEventListener('keyup', () => {
                const query = inputEl.value.toLowerCase();
                const rows = tableEl.getElementsByTagName('tr');

                for (let i = 1; i < rows.length; i++) {
                    const row = rows[i];
                    const rowText = row.textContent || row.innerText;

                    if (rowText.toLowerCase().indexOf(query) > -1) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                }
            });
        }

        const searchInput = document.getElementById('tableSearchInput');
        const dataTable = document.getElementById('myFilterableTable');
        if (searchInput && dataTable) {
            enableTableFilter(searchInput, dataTable);
        }

    </script>
    <script src="../../js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        sidebarToggle.addEventListener('click', function () {
            if (window.innerWidth <= 767.98) {
                document.body.classList.toggle('sidebar-open');
            } else {
                document.body.classList.toggle('sidebar-closed');
            }
        });
        document.addEventListener('click', function (event) {
            if (window.innerWidth > 767.98) {
                return;
            }
            if (!document.body.classList.contains('sidebar-open')) {
                return;
            }
            const sidebar = document.getElementById('sidebar');
            if (
                !sidebar.contains(event.target) &&
                !sidebarToggle.contains(event.target)
            ) {
                document.body.classList.remove('sidebar-open');
            }
        });
        window.addEventListener('resize', function () {

            if (window.innerWidth > 767.98) {
                document.body.classList.remove('sidebar-open');
            }

        });
    </script>
</body>

</html>