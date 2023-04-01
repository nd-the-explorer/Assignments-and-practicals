<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['new_test'])) {
    $test_name = $_POST['test_name'];
    $test_subject = $_POST['subject_name'];
    $test_date = $_POST['test_date'];
    $total_questions = $_POST['total_questions'];
    $test_status = $_POST['test_status'];
    $test_class = $_POST['test_class'];
    $status_id = $class_id = -1;
  
    
    $status_sql = "SELECT id from status where name LIKE '%$test_status%'";
    $status = mysqli_query($conn,$status_sql);
    if(mysqli_num_rows($status) > 0) {
        $status_row = mysqli_fetch_assoc($status);
        $status_id = $status_row["id"];
      } else {
        // Set a default value for $status_id if the query returns no rows
        $status_id = 1;
      }
    //getting class id
    $class_sql = "SELECT id from classes where name LIKE '%$test_class%'";
    $class_result = mysqli_query($conn,$class_sql);
    if(mysqli_num_rows($class_result) > 0) {
      $class_row = mysqli_fetch_assoc($class_result);
      $class_id = $class_row["id"];
    }
  
    function generateRandomString($length = 8) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
    }
  
    $teacher_id = $_SESSION["user_id"];
    //creating new test
    $sql = "INSERT INTO tests(teacher_id, name, date, status_id, subject, total_questions,class_id) VALUES('$teacher_id','$test_name','$test_date','$status_id','$test_subject','$total_questions','$class_id')";
    $result = mysqli_query($conn,$sql);
    $test_id = mysqli_insert_id($conn);
    if($result) {
      //creating student entry in students table for the test
      $sql1 = "select id from student_data where class_id = '$class_id'";
      $result1 = mysqli_query($conn,$sql1);
      $temp = 8 - strlen($test_id);
      while($row1 = mysqli_fetch_assoc($result1)) {
        $rollno = $row1["id"];
        $random = generateRandomString($temp);
        $random = $random . $test_id;
        $sql2 = "INSERT INTO students(test_id,rollno,password,score,status) VALUES ('$test_id','$rollno','$random',0,0)";
        $result2 = mysqli_query($conn,$sql2);
        if($result2) {
          header("Location:dashboard.php");
        }
      }
    }
  }
 
?>



<!DOCTYPE html>
<html lang="en">

<head>
  
    <title>Evalify : Add Quiz </title>

    <link href="../assets/css/lib/calendar2/pignose.calendar.min.css" rel="stylesheet">
    <link href="../assets/css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="../assets/css/lib/themify-icons.css" rel="stylesheet">
    <link href="../assets/css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="../assets/css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/lib/unix.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>

<?php include_once('includes/sidebar.php');?>
   
    <?php include_once('includes/header.php');?>
    
    <div class="content-wrap">
        <div class="main">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Add Quiz</h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="dashboard.php">Dashboard</a></li>
                                    <li class="active">Add Quiz</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
                <div id="main-content">
                    <div class="card alert">
                        <div class="card-body">
                            <form name="" method="post" action="" enctype="multipart/form-data">
                            <div class="card-header m-b-20">
                                <h4>Add Quiz</h4>
                                <div class="card-header-right-icon">
                                    <ul>
                                        <li class="card-close" data-dismiss="alert"><i class="ti-close"></i></li>
                                        <li class="card-option drop-menu"><i class="ti-settings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" role="link"></i>
                                            <ul class="card-option-dropdown dropdown-menu">
                                                <li><a href="#"><i class="ti-loop"></i> Update data</a></li>
                                                <li><a href="#"><i class="ti-menu-alt"></i> Detail log</a></li>
                                                <li><a href="#"><i class="ti-pulse"></i> Statistics</a></li>
                                                <li><a href="#"><i class="ti-power-off"></i> Clear ist</a></li>
                                            </ul>
                                        </li>
                                        <li class="doc-link"><a href="#"><i class="ti-link"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                            <div class="content" style="min-height: auto;">
        <div class="row">
          <div class="col-md-2"></div>  
          <div class="col-md-8">
            <div class="card">
              <div class="card-header">
                <h5 class="title">Create New Test</h5>
              </div>
              <div class="card-body">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                  <input type="hidden" name="new_test">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Test name (title)</label>
                          <input type="text" class="form-control" name="test_name" placeholder="Test name" required/>
                      </div>
                      <div class="form-group">
                        <label>Subject name</label>
                          <input type="text" class="form-control" name="subject_name" placeholder="Subject name" required/>
                      </div>
                      <div class="form-group">
                        <label>Test date</label>
                          <input type="date" class="form-control" name="test_date" placeholder="Test Date" required/>
                      </div>
                      <div class="form-group">
                        <label>Total Questions count</label>
                          <input type="number" class="form-control" name="total_questions" placeholder="Total Questions count" required/>
                      </div>
                      <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <select id="options" name="test_status" class="btn-round" required style="width:100%;">
                                  <option selected="true" value="" disabled="disabled">Select test status</option>
                                     <?php
                                      $sql = "select * from status where id IN(1,2)";
                                      $result = mysqli_query($conn,$sql);
                                      while($row = mysqli_fetch_assoc($result)) {
                                      ?>

                                        <option value="<?= $row["name"];?>"><?= $row["name"];?></option>

                                        <?php
                                      }
                                      ?>                                 
                                </select>
                            </div>
                            
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row center-element">
                    <div class="col-md-8">
                      <div class="form-group">
                        <button class="btn btn-primary btn-block btn-round">CREATE TEST</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-2"></div>
        </div>
      </div>
                               
        
    
    <!-- jquery vendor -->
    <script src="../assets/js/lib/jquery.min.js"></script>
    <script src="../assets/js/lib/jquery.nanoscroller.min.js"></script>
    <!-- nano scroller -->
    <script src="../assets/js/lib/menubar/sidebar.js"></script>
    <script src="../assets/js/lib/preloader/pace.min.js"></script>
    <!-- sidebar -->
    <script src="../assets/js/lib/bootstrap.min.js"></script>
    <!-- bootstrap -->


    <script src="../assets/js/lib/calendar-2/moment.latest.min.js"></script>
    <!-- scripit init-->
    <script src="../assets/js/lib/calendar-2/semantic.ui.min.js"></script>
    <!-- scripit init-->
    <script src="../assets/js/lib/calendar-2/prism.min.js"></script>
    <!-- scripit init-->
    <script src="../assets/js/lib/calendar-2/pignose.calendar.min.js"></script>
    <!-- scripit init-->
    <script src="../assets/js/lib/calendar-2/pignose.init.js"></script>
    <!-- scripit init-->

    <script src="../assets/js/scripts.js"></script>
</body>

</html><?php   ?>