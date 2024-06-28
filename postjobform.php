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

  <div class="Ta1">
    <h2>Posting jobs form</h2>
    <!-- Form for posting a job -->
    <form action="postjobprocess.php" method="POST">
      <label for="position_id">Position ID:</label>
      <input type="text" id="position_id" name="position_id"><br><br>

      <label for="title">Title:</label>
      <input type="text" id="title" name="title"><br><br>

      <label for="description">Description:</label>
      <textarea id="description" name="description" rows="4" cols="50" maxlength="250"></textarea><br><br>

      <label for="closing_date">Closing Date:</label>
      <input type="text" id="closing_date" name="closing_date" placeholder="dd/mm/yy" value="<?php $currentDate = date('d/m/y'); echo $currentDate; ?>"><br><br>

      <label for="position">Position:</label>
      <input type="radio" id="full_time" name="position" value="Full Time">
      <label for="full_time">Full Time</label>
      <input type="radio" id="part_time" name="position" value="Part Time">
      <label for="part_time">Part Time</label><br><br>

      <label for="contract">Contract:</label>
      <input type="radio" id="ongoing" name="contract" value="On-going">
      <label for="ongoing">On-going</label>
      <input type="radio" id="fixed_term" name="contract" value="Fixed term">
      <label for="fixed_term">Fixed term</label><br><br>

      <label>Application by:</label>
      <input type="checkbox" id="post" name="accept_application[]" value="Post">
      <label for="post">Post</label>
      <input type="checkbox" id="email" name="accept_application[]" value="Email">
      <label for="email">Email</label><br><br>

      <label for="location">Location:</label>
      <select id="location" name="location">
          <option value="">---</option>
          <option value="ACT">ACT</option>
          <option value="NSW">NSW</option>
          <option value="NT">NT</option>
          <option value="QLD">QLD</option>
          <option value="SA">SA</option>
          <option value="TAS">TAS</option>
          <option value="VIC">VIC</option>
          <option value="WA">WA</option>
      </select><br><br>

      <input type="submit" value="Submit">
      <input type="reset" value="Reset">
    </form>

    <p><strong>ALL FIELDS ARE REQUIRED.</strong></p>
    <!-- Link to return to Home page -->
    <p><a href="index.php">Return to Home Page</a></p>
        
  </div>

    

  
</body>

</html>