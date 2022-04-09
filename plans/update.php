<?php
// Include config file
require_once "../config.php";

// Define variables and initialize with empty values
$planName = $category = $fileName = "";
$planName_err = $category_err = $fileName_err = "";

// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];

    // Validate name
    $input_name = trim($_POST["planName"]);
    if (empty($input_name)) {
        $planName_err = "Please enter a plan name.";
    } else {
        $planName = $input_name;
    }

    // Validate category category
    $input_category = trim($_POST["category"]);
    if (empty($input_category)) {
        $category_err = "Please enter an category.";
    } else {
        $category = $input_category;
    }

    // Validate fileName
    $input_fileName = trim($_POST["fileName"]);
    if (empty($input_fileName)) {
        $fileName_err = "Please enter the fileName amount.";
    } else {
        $fileName = $input_fileName;
    }

    // Check input errors before inserting in database
    if (empty($planname_err) && empty($category_err) && empty($fileName_err)) {
        // Prepare an update statement
        $sql = "UPDATE plans SET planName=?, category=?, fileName=? WHERE id=?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_planName, $param_category, $param_fileName, $param_id);

            // Set parameters
            $param_planName = $planName;
            $param_category = $category;
            $param_fileName = $fileName;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id = trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM plans WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $planName = $row["planName"];
                    $category = $row["category"];
                    $fileName = $row["fileName"];
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-5">Update Record</h2>
                <p>Please edit the input values and submit to update the Plan record.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                        <label>Plan</label>
                        <input type="text" name="planName"
                               class="form-control <?php echo (!empty($planName_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $planName; ?>">
                        <span class="invalid-feedback"><?php echo $planName_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" name="category"
                               class="form-control <?php echo (!empty($category_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $category; ?>">
                        <span class="invalid-feedback"><?php echo $category_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Filename</label>
                        <input type="text" name="fileName"
                               class="form-control <?php echo (!empty($fileName_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $fileName; ?>">
                        <span class="invalid-feedback"><?php echo $fileName_err; ?></span>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>