<?php
// Include config file
require_once "../config.php";

// Define variables and initialize with empty values
$toolName = $serNum = $model = $make = $manual = "";
$tool_err = $serNum_err = $model_err = $make_err = $manual_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["toolName"]);
    if(empty($input_name)){
        $tool_err = "Please enter a tool name.";
   } else{
        $toolName = $input_name;
    }

    // Validate serNum
    $input_serNum = trim($_POST["serNum"]);
    if(empty($input_serNum)){
        $serNum_err = "Please enter an Serial Number.";
    } else{
        $serNum = $input_serNum;
    }

    // Validate model
    $input_model = trim($_POST["model"]);
    if(empty($input_model)){
        $model_err = "Please enter the model.";
    } else{
        $model = $input_model;
    }

    // Validate make
    $input_make = trim($_POST["make"]);
    if(empty($input_model)){
        $model_err = "Please enter the make.";
    } else{
        $make = $input_make;
    }
    // Validate manual
    $input_manual = trim($_POST["manual"]);
    if(empty($input_model)){
        $model_err = "Please enter the manual filename.";
    } else{
        $manual = $input_manual;
    }

    // Check input errors before inserting in database
    if(empty($tool_err) && empty($serNum_err) && empty($model_err) && empty($make_err) && empty($manual_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO tools (toolName, serNum, model, make, manual) VALUES (?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_toolName, $param_serNum, $param_model, $param_make, $param_manual);

            // Set parameters
            $param_toolName = $toolName;
            $param_serNum = $serNum;
            $param_model = $model;
            $param_make = $make;
            $param_manual = $manual;

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
                <p>Please fill this form and submit to add a tool record to the database.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Tool Name</label>
                        <input type="text" name="toolName" class="form-control <?php echo (!empty($tool_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $toolName; ?>">
                        <span class="invalid-feedback"><?php echo $tool_err;?></span>
                    </div>
                    <div class="form-group">
                        <label>serNum</label>
                        <input type="text" name="serNum" class="form-control <?php echo (!empty($serNum_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $serNum; ?>">
                        <span class="invalid-feedback"><?php echo $serNum_err;?></span>
                    </div>
                    <div class="form-group">
                        <label>model</label>
                        <input type="text" name="model" class="form-control <?php echo (!empty($model_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $model; ?>">
                        <span class="invalid-feedback"><?php echo $model_err;?></span>
                    </div>
                    <div class="form-group">
                        <label>Make</label>
                        <input type="text" name="make" class="form-control <?php echo (!empty($make_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $make; ?>">
                        <span class="invalid-feedback"><?php echo $make_err;?></span>
                    </div>
                    <div class="form-group">
                        <label>Manual</label>
                        <input type="text" name="manual" class="form-control <?php echo (!empty($manual_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $manual; ?>">
                        <span class="invalid-feedback"><?php echo $manual_err;?></span>
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