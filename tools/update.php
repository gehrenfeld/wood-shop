<?php
// Include config file
require_once "../config.php";

// Define variables and initialize with empty values
$toolName = $serNum = $model = $make = $manual = "";
$toolName_err = $serNum_err = $model_err = $make_err = $manual_err = "";

// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];

    // Validate name
    $input_name = trim($_POST["toolName"]);
    if (empty($input_name)) {
        $toolName_err = "Please enter a tool name.";
    } else {
        $toolName = $input_name;
    }

    // Validate serNum serNum
    $input_serNum = trim($_POST["serNum"]);
    if (empty($input_serNum)) {
        $serNum_err = "Please enter an Serial Number.";
    } else {
        $serNum = $input_serNum;
    }

    // Validate model
    $input_model = trim($_POST["model"]);
    if (empty($input_model)) {
        $model_err = "Please enter the Model.";
    } else {
        $model = $input_model;
    }
    // Validate make
    $input_make = trim($_POST["make"]);
    if (empty($input_make)) {
        $make_err = "Please enter the Make.";
    } else {
        $make = $input_make;
    }
    // Validate model
    $input_manual = trim($_POST["manual"]);
    if (empty($input_manual)) {
        $manual_err = "Please enter the manual.";
    } else {
        $manual = $input_manual;
    }


    // Check input errors before inserting in database
    if (empty($toolName_err) && empty($serNum_err) && empty($model_err) && empty($make_err) && empty($manual_err)) {
        // Prepare an update statement
        $sql = "UPDATE tools SET toolName=?, serNum=?, model=?, make=?, manual=? WHERE id=?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssi", $param_toolName, $param_serNum, $param_model, $param_make, $param_manual, $param_id);

            // Set parameters
            $param_toolName = $toolName;
            $param_serNum = $serNum;
            $param_model = $model;
            $param_make = $make;
            $param_manual = $manual;
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
        $sql = "SELECT * FROM tools WHERE id = ?";
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
                    $toolName = $row["toolName"];
                    $serNum = $row["serNum"];
                    $model = $row["model"];
                    $make = $row["make"];
                    $manual = $row["manual"];
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
                <p>Please edit the input values and submit to update the Tool record.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                        <label>Tool</label>
                        <input type="text" name="toolName"
                               class="form-control <?php echo (!empty($toolName_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $toolName; ?>">
                        <span class="invalid-feedback"><?php echo $toolName_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Serial Number</label>
                        <input type="text" name="serNum"
                               class="form-control <?php echo (!empty($serNum_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $serNum; ?>">
                        <span class="invalid-feedback"><?php echo $serNum_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Model</label>
                        <input type="text" name="model"
                               class="form-control <?php echo (!empty($model_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $model; ?>">
                        <span class="invalid-feedback"><?php echo $model_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Make</label>
                        <input type="text" name="make"
                               class="form-control <?php echo (!empty($make_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $make; ?>">
                        <span class="invalid-feedback"><?php echo $make_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Manual</label>
                        <input type="text" name="manual"
                               class="form-control <?php echo (!empty($manual_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $manual; ?>">
                        <span class="invalid-feedback"><?php echo $manual_err; ?></span>
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