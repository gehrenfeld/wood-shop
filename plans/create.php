<?php
// Include config file
require_once "../config.php";

// Define variables and initialize with empty values
$planName = $category = $filename = "";
$plan_err = $category_err = $filename_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["planName"]);
    if(empty($input_name)){
        $plan_err = "Please enter a plan name.";
   } else{
        $planName = $input_name;
    }

    // Validate category
    $input_category = trim($_POST["category"]);
    if(empty($input_category)){
        $category_err = "Please enter an category.";
    } else{
        $category = $input_category;
    }

    // Validate filename
    $input_filename = trim($_POST["filename"]);
    if(empty($input_filename)){
        $filename_err = "Please enter the filename amount.";
    } else{
        $filename = $input_filename;
    }

    // Check input errors before inserting in database
    if(empty($plan_err) && empty($category_err) && empty($filename_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO plans (planName, category, filename) VALUES (?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_planName, $param_category, $param_filename);

            // Set parameters
            $param_planName = $planName;
            $param_category = $category;
            $param_filename = $filename;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
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
                <h2 class="mt-5">Create Record</h2>
                <p>Please fill this form and submit to add plan record to the database.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Plan Name</label>
                        <input type="text" name="planName" class="form-control <?php echo (!empty($plan_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $planName; ?>">
                        <span class="invalid-feedback"><?php echo $plan_err;?></span>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" name="category" class="form-control <?php echo (!empty($category_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $category; ?>">
                        <span class="invalid-feedback"><?php echo $category_err;?></span>
                    </div>
                    <div class="form-group">
                        <label>Filename</label>
                        <input type="text" name="filename" class="form-control <?php echo (!empty($filename_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $filename; ?>">
                        <span class="invalid-feedback"><?php echo $filename_err;?></span>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>