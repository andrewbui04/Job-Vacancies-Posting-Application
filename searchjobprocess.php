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
                <li><a href="postjobform.php">Post Jobs</a></li>
                <li class="active"><a href="searchjobform.php">Search Jobs</a></li>
                <li><a href="about.php">About this assignment</a></li>
            </ul>
        </nav>
       
    </div>
  </header>

  <h1 class="header">Job Vacancies List</h1>

  <?php
  function sanitise_input($input)
  {
    $input = trim($input);
    $input = stripslashes($input);
    return htmlspecialchars($input);
  }

  function convert_date_standard_format($date)
  {
      $dateArray = explode('/', $date);
      return  strtotime("20" . $dateArray[2] . '/' . $dateArray[1] . '/' . $dateArray[0]);
  }

  function compare_closing_dates($a, $b) 
  {
    $closingDateA = convert_date_standard_format($a[3]); 
    $closingDateB = convert_date_standard_format($b[3]); 
    // Compare the closing dates
    if ($closingDateA == $closingDateB) {
        return 0;
    }
    return ($closingDateA < $closingDateB) ? 1 : -1;
  }

  $filename = '../../data/jobs.txt';
 
  $currentDate = date_create(); // Get the current date as a DateTime object
  $formattedCurrentDate = $currentDate->format('d/m/y'); // Format the current date as 'd/m/y'

  //Check if the job title is not empty
  if (isset($_GET['title']) && !empty($_GET['title'])) {
    $title = $_GET['title'];
    $position = isset($_GET["position"]) ? $_GET["position"] : null;
    $contract = isset($_GET["contract"]) ? $_GET["contract"] : null;
    $application = isset($_GET["accept_application"]) ? (array)$_GET["accept_application"] : array();

    $location = isset($_GET["location"]) ? $_GET["location"] : null;

    if (!file_exists($filename)) {
      echo "<p class='process'>File does not exist.</p>";
      exit;
    } else {
      $handle = fopen($filename, 'r');
      $jobVacancy = array(); 
      // check if the file is opened successfully
      if ($handle) {
        while ( ($jobResult = fgets($handle)) !== false) {
          $line = explode("\t",$jobResult);
          $titleRecord = isset($line[1]) ? $line[1] : null;
          $descriptionRecord = isset($line[2]) ? stripslashes($line[2]) : null;
          $closingDateRecord = isset($line[3]) ? date_create_from_format('d/m/y', $line[3]) : null;
          $positionRecord = isset($line[4]) ? $line[4] : null;
          $contractRecord = isset($line[5]) ? $line[5] : null;
          $applicationRecord = isset($line[6]) ? explode(",", $line[6]) : array();
          $locationRecord = isset($line[7]) ? $line[7] : null;
          
          if (
            (!empty($title) && strpos(strtolower($titleRecord), strtolower(sanitise_input($title))) !== false) &&
            (!$position || strtolower($position) === strtolower(sanitise_input($positionRecord))) &&
            (!$contract || strtolower($contract) === strtolower(sanitise_input($contractRecord))) &&
            (!$application || ( ($applicationRecord === $application) || is_array($applicationRecord) && (is_array($application)) && ($intersection = array_intersect(array_map('strtolower', $application), array_map('strtolower', $applicationRecord))) && print_r($intersection, true))) &&
            (!$location || strtolower($location) === strtolower(sanitise_input($locationRecord))) &&
            ($closingDateRecord >= $currentDate)  // Only display jobs which are not closed
          ) {
            // add the job vacancy to the array
            $jobVacancy[] = $line;
          }
        }
        if (empty($jobVacancy)) {
          echo "<p>No job vacancy found. Or maybe the job you searched has closed!</p>";
        } else {
          // sort the job vacancies by closing date in descending order
          usort($jobVacancy, 'compare_closing_dates');
          foreach ($jobVacancy as $job) {
            // display the job vacancy information
            echo "<div class = 'Ta1'>";
            echo "<p><strong>Job Title: </strong>" . $job[1] . "</p>";
            echo "<p><strong>Description: </strong>" . $job[2] . "</p>";
            echo "<p><strong>Closing Date: </strong>" . $job[3] . "</p>";
            echo "<p><strong>Position: </strong>" . $job[4] . "</p>";
            echo "<p><strong>Contract: </strong>" . $job[5]. "</p>";
            echo "<p><strong>Application by: </strong>" . $job[6] . "</p>";
            echo "<p><strong>Location: </strong>" . $job[7] . "</p>";
            echo "</div>";
          }
        }
        fclose($handle); // close the file
      } else {
        echo "<p>Cannot to open the file.</p>";
      }
    }
  } else {
    echo '<p>Job title cannot be empty.</p>';
  }
  ?>

  <div class="directing">
    <p><a href="searchjobform.php">Search for another job vacancy</a></p>
    <p><a href="index.php">Return to Home Page</a></p>
  </div>
        


  
</body>

</html>