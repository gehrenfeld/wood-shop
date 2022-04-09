<?php
// Include config file
require_once "../config.php";

// Define variables and initialize with empty values
$manualName = $filename = "";
$manualName_err = $filename_err = "";

// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];

    // Validate name
    $input_name = trim($_POST["manualName"]);
    if (empty($input_name)) {
        $manualName_err = "Please enter a manual name.";
    } else {
        $manualName = $input_name;
    }

    // Validate
    $input_filename = trim($_POST["filename"]);
    if (empty($input_filename)) {
        $filename_err = "Please enter a filename.";
    } else {
        $filename = $input_filename;
    }


    // Check input errors before inserting in database
    if (empty($manualName_err) && empty($filename_err)) {
        // Prepare an update statement
        $sql = "UPDATE manuals SET manualName=?, fileName=? WHERE id=?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssi", $param_manualName, $param_filename, $param_id);

            // Set parameters
            $param_manualName = $manualName;
            $param_filename = $filename;
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
        $sql = "SELECT * FROM manuals WHERE id = ?";
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
                    $manualName = $row["manualName"];
                    $filename = $row["fileName"];
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
                <p>Please edit the input values and submit to update the Manual record.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                        <label>Manual Name</label>
                        <input type="text" name="manualName"
                               class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $manualName; ?>">
                        <span class="invalid-feedback"><?php echo $manualName_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Filename</label>
                        <input type="text" name="filename"
                               class="form-control <?php echo (!empty($filename_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $filename; ?>">
                        <span class="invalid-feedback"><?php echo $filename_err; ?></span>
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