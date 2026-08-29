<?php
$local = "localhost";
$user = "root";
$pass = "";
$db = "tourism";
$conn = mysqli_connect($local, $user, $pass, $db);

if (isset($_POST["publish"]) || isset($_POST["draft"])) {
    $name = $_POST["name"];
    $destination = $_POST["destination"];
    $category = $_POST["category"];
    $price = $_POST["price"];
    $duration = $_POST["duration"];
    $max_travelers = $_POST["max_travelers"];
    $image_url = $_POST["image_url"];
    $text = $_POST["text"];
    $status = isset($_POST["publish"]) ? 'published' : 'draft';

    if (empty($name)) {
        echo "Package name is required";
    } elseif (empty($destination)) {
        echo "Destination is required";
    } elseif (empty($price)) {
        echo "Price is required";
    } elseif (preg_match('/[0-9]/', $name)) {
        echo "This is not a valid name";
    } elseif (preg_match('/[a-zA-Z]/', $price)) {
        echo "This is not a valid price";
    } elseif (preg_match('/[0-9]/', $destination)) {
        echo "This is not a valid destination";
    } else {
        $query = "INSERT INTO tours_package(name, category, location, price, duration, max_travelers, image_url, status, text) 
                  VALUES('$name', '$category', '$destination', '$price', '$duration', '$max_travelers', '$image_url', '$status', '$text')";
        
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "added";
        } else {
            echo "can not added";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Package</title>
    <link rel="stylesheet" href="package.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

    <div class="container">
        <div class="d-flex align-items-center mb-4">
            <button class="btn btn-light rounded-circle me-3 border"><i class="bi bi-arrow-left"></i></button>
            <div>
                <h2 class="fw-bold mb-0">Create Package</h2>
                <small class="text-muted">Fill in the details below and publish or save as draft</small>
            </div>
        </div>

        <form method="post" action="">
            <div class="row g-4">
                
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm p-4 mb-4 rounded-4">
                        <h5 class="fw-bold mb-4">Basic Information</h5>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Package Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-lg rounded-3" placeholder="e.g. Maldives Luxury Escape">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted">Destination <span class="text-danger">*</span></label>
                                <input type="text" name="destination" class="form-control form-control-lg rounded-3" placeholder="e.g. Maldives">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Category</label>
                                <select name="category" class="form-select form-select-lg rounded-3">
                                    <option value="Luxury">Luxury</option>
                                    <option value="Family">Family</option>
                                    <option value="Group">Group</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted">Duration</label>
                                <select name="duration" class="form-select form-select-lg rounded-3">
                                    <option value="7 Nights">7 Nights</option>
                                    <option value="5 Nights">5 Nights</option>
                                    <option value="3 Nights">3 Nights</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Max Travelers</label>
                                <input type="number" name="max_travelers" class="form-control form-control-lg rounded-3" placeholder="e.g. 16">
                            </div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label text-muted">Price Per Person (USD) <span class="text-danger">*</span></label>
                            <input type="text" name="price" class="form-control form-control-lg rounded-3" placeholder="$ 0">
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm p-4 rounded-4">
                        <h5 class="fw-bold mb-4">Descriptions</h5>
                        <div class="mb-3">
                            <textarea name="text" class="form-control rounded-3" rows="4" placeholder="Enter package details..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm p-4 mb-4 rounded-4">
                        <h5 class="fw-bold mb-4">Publish Settings</h5>
                        <button type="submit" name="publish" class="btn btn-dark btn-lg w-100 mb-3 rounded-3" style="background-color: #0b1a30;">Publish Package</button>
                        <button type="submit" name="draft" class="btn btn-light btn-lg w-100 mb-3 rounded-3 border">Save as Draft</button>
                        <div class="text-center">
                            <a href="#" class="text-decoration-none text-muted">Cancel</a>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm p-4 rounded-4">
                        <h5 class="fw-bold mb-4">Featured Image</h5>
                        <div class="mb-3">
                            <label class="form-label text-muted">Image URL</label>
                            <input type="text" name="image_url" class="form-control rounded-3" placeholder="https://images.unsplash.com/photo-..">
                        </div>
                        <div class="border border-2 border-dashed rounded-4 p-5 text-center text-muted bg-light">
                            <i class="bi bi-cloud-upload fs-2 d-block mb-2"></i>
                            <small>Paste image URL above</small>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>