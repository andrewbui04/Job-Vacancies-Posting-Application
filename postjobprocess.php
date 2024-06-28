<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>COS30020 - Assignment 1</title>
  <link rel="stylesheet" type="text/css" href="style/style.css">
</head>

<body>
  <header>
    <div class="container">
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li class="active"><a href="postjobform.php">Post Jobs</a></li>
                <li><a href="searchjobform.php">Search Jobs</a></li>
                <li><a href="about.php">About this assignment</a></li>
            </ul>
        </nav>
       
    </div>
  </header>

  <h1 class="header">Job Vacancy Posting System</h1>

  <?php
  umask(0007);
  $dir = '../../data'; 
  $filename = "$dir/jobs.txt";
  if (!file_exists($dir)) {
      mkdir($dir, 02770, true);
  }

  //Validate inputs with type = "text" such as: title, closing date
  function validate_text($inputName, $inputValue, $pattern, $errorMessage)
  {
    if (empty($inputValue)){
      echo "<p>Please enter $inputName.</p>";
    }else if (!preg_match($pattern, $inputValue)){
      echo "<p>$errorMessage</p>";
    }else{
      return $inputValue;
    }
    return null;
  }

  //Validate "Description" field
  function validate_description($text)
  {
    if (empty($text)){
      echo "<p>Please give a description.</p>";
    }else if (strlen($text) >= 260){ 
      echo "<p>The description can only contain a maximum of 250 characters.</p>";
    }else{
      return $text;
    }
    return null;
  }

  //Return an error if there is no submission of inputs with type = "radio" such as:position type, contract type
  function validate_radio($radioOption)
  {
    echo "<p>Please choose one of these options: $radioOption</p>";
  }

  //Validate input with type = "checkbox" such as: Application type
  function validate_checkbox($checkboxOption, $checkboxValue)
  {
    if (empty($checkboxValue)) {
      echo "<p>Please choose an $checkboxOption</p>";
    } else {
      $checkboxValue = is_array($checkboxValue) ? $checkboxValue : array($checkboxValue);
      return $checkboxValue;
    }
    return null;
  }


  //Validate "Location" option field
  function validate_location($locationName,$locationValue)
  {
    if (empty($locationValue)){
      echo "<p>Please choose a $locationName</p>";
    }else{
      return $locationValue;
    }
    return null;
  }

  //Check whether the Position ID is unique to avoid duplicating
  function check_whether_posID_unique($filename, $positionID)
  {
    if (file_exists($filename)){
      $handle = fopen($filename,'r');
      //check if the file is successfully opened
      if ($handle){
        //Read each line from the file
        while (!feof($handle)){
          $record = fgets($handle);
          $line = explode("\t", $record);
          if (($line[0]) === $positionID){
            fclose($handle);
            return false; //The position id is not unique
          }
        }
        fclose($handle);
      }else{
        echo "<p>Cannot open and read the file</p>";
      }
    }
    return true; //The ID is unique
  }

  //Process the form when submitting
  if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $positionID = validate_text('Position ID', $_POST['position_id'], '/^PID\d{4}$/', 'Please enter a unique 7-character code starting with 3 uppercase letter PID followed by 4 digits');
    $title = validate_text('Job title', $_POST['title'], '/^[a-zA-Z0-9\s.,!]{1,20}$/', 'Please enter a title containing maximum of 20 alphanumeric characters without any special ones except spaces, comma, period and exclamation point');
    $description = validate_description($_POST['description']);
    $description = $description ? addslashes($description) : null;
    $closingDate = validate_text('Closing date', $_POST['closing_date'], '/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/\d{2}$/', 'Please enter a date with dd/mm/yy format');
    $positionType = isset($_POST['position']) ? $_POST['position'] : validate_radio('Full Time or Part Time');
    $contract = isset($_POST['contract']) ? $_POST['contract'] : validate_radio('On-going or Fixed term');
    $application = validate_checkbox('application type', isset($_POST['accept_application']) ? $_POST['accept_application'] : array());
    $location = validate_location('location in the list', isset($_POST['location']) ? $_POST['location'] : null);

    // Ensure $application is always an array
    $application = is_array($application) ? $application : array($application);
    
    //Check if all the fields are valid
    if ($positionID && $title && $description && $closingDate && $positionType && $contract && $application && $location){
      //check whether the position ID is unique
      if (check_whether_posID_unique($filename, $positionID) === false){
          echo "<p>The position ID already exists. Please enter a unique one!</p>";
      }else{
          $applicationString = implode(",", $application);
          $jobData = "$positionID\t$title\t$description\t$closingDate\t$positionType\t$contract\t$applicationString\t$location\n";
          $handle = fopen($filename,'a');
          if ($handle){ 
              fwrite($handle, $jobData);
              fclose($handle);
              echo "<p>Successfully posting a job!</p>";
          }else{
              echo "<p>Cannot open the file</p>";
          }
      }
    }else{
      echo "<p style='color: red;'>You validated the input unsucessfully!</p>";
    }
  }else{
    echo "<p>Please fill out the form. All fields cannot be empty.</p>";
  }
  ?>

  <div class="directing">
    <p><a href="postjobform.php">Return to Job Posting Page</a></p>
    <p><a href="index.php">Return to Home Page</a></p>
  </div>
</body>

</html>