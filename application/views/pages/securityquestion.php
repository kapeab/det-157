<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url("images/favicon.ico"); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
    <link href='https://fonts.googleapis.com/css?family=Cabin' rel='stylesheet'>
    <script src="<?php echo base_url("application/third_party/jQuery/jquery-3.3.1.min.js"); ?>"></script>
    <link rel="stylesheet" href="<?php echo base_url("application/third_party/bootstrap-4.3.1/css/bootstrap.min.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("application/third_party/bootstrap-4.3.1/css/bootstrap-grid.min.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("application/third_party/bootstrap-4.3.1/css/bootstrap-reboot.min.css"); ?>">
    <script type="text/javascript" src="<?php echo base_url("application/third_party/bootstrap-4.3.1/js/bootstrap.min.js"); ?>"></script>

	<title>Security Question</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <style>
    /* Styles for mobile */
    @media (max-width: 500px) 
    {
        .card
        {
            width: 100%;
        }
        body
        {
            min-width: 400px;
        }
    }
    @media (min-width: 600px) 
    {
        .card
        {
            width: 40%;
        }
    }
    </style>
</head>
    
<body>
<div class="jumbotron container-fluid">
  <div class="container">
    <h1 class="display-4">Security Question</h1>
    <div style="width:90%;display:block;margin-left:auto;margin-right:auto;" class="card" id="Edit Groups">
      <div class="card-body">
        
      <?php echo form_open('cadet/saveanswer'); ?>
      
          <h4>Security Question:</h4>
          <select name="question">
            <option value="What was your childhood nickname?">What was your childhood nickname?</option>
            <option value="What is the name of your favorite childhood friend?">What is the name of your favorite childhood friend?</option>
            <option value="In what city or town did your mother and father meet?">In what city or town did your mother and father meet?</option>
            <option value="What is your favorite team?">What is your favorite team?</option>
            <option value="What is your favorite movie?">What is your favorite movie?</option>
            <option value="What was your favorite sport in high school?">What was your favorite sport in high school?</option>
            <option value="What was your favorite food as a child?">What was your favorite food as a child?</option>
            <option value="What was the make and model of your first car?">What was the make and model of your first car?</option>
            <option value="Who is your childhood sports hero?">Who is your childhood sports hero?</option>
            <option value="In what town was your first job?">In what town was your first job?</option>
            <option value="What was the name of the company where you had your first job?">What was the name of the company where you had your first job?</option>
          </select><br><br>
          <h4 class="card-text">Response:</h4>
          <input type="text" style="width:100%;" name="answer" id="answer"><br><br>
          <button class="btn btn-sm btn-primary" type="submit" name="submit">Save Answer</button>
        </form><br>
    </div>
</div>

</body>
