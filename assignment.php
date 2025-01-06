<?php    
    session_start();
    if(!isset($_SESSION['user']))
        header("location:login.php");
    if($_SESSION['type'] == "G" || $_SESSION['type'] == "A")
        header("location:course.php");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title><?php echo $_SESSION['name_assign'];?></title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="./css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <link rel="icon" href="https://cdn.freebiesupply.com/logos/large/2x/keiser-university-logo-png-transparent.png">
        <script src="./js/jquery.js"></script>
        <script>
            $(document).ready(function(){
                $('.edit').click(function(){
                    row = $(this).closest("tr");
                    row.find("td:eq(2)").find("input").prop('disabled', false);
                    
                    
                    //alert("Grade: " + grade + " ID: " + id);
                });

                $('.save').click(function(){                    
                    row = $(this).closest("tr");

                    grade = row.find("td:eq(2)").find("input").val();
                    id = row.find("td:eq(3)").find("button").attr('id');

                    if(!row.find("td:eq(2)").find("input").prop('disabled')){
                        if(row.find("td:eq(2)").find("input").val() == "")
                            alert("Verify the grade!");
                        else{
                            row.find("td:eq(2)").find("input").prop('disabled', true);
                            $.ajax({
                                url: "validate.php",
                                type: "POST",
                                data:{grade_std:grade, id_std_gr:id},
                                success: function(result){
                                    alert(result);
                                }
                            });
                        }
                    }
                });
            });

            function fun(course_id){
                $.ajax({
                    url: "validate.php",
                    type: "POST",
                    data:{course_id:course_id},
                    success: function(result){
                        window.open("course.php", "_self");
                    }
                });
            }

            function uploadAssign(idAssign){
                $.ajax({
                    url: "validate.php",
                    type: "POST",
                    data:{idAssign:idAssign},
                    success: function(result){
                        window.open("upload.php", "_self");
                    }
                });
            }
        </script>
    </head>
    <body class="sb-nav-fixed">
        
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-0" href="index.php">
                <center>
                    <img src="./img/platform.png" 
                    alt="platform" class="img-responsive" width="55" height="55"> PLATFORM
                </center>
            </a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            
            <ul class="navbar-nav ms-auto ms-md-12 me-3 me-lg-4">
                <li class="pt-3 text-white">
                    <?php echo $_SESSION['user'];?>
                </li>
                <li class="nav-item dropdown">
                    <!-- <i class="fas fa-user fa-fw"></i> -->
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                            include_once("validate.php"); 
                            echo graphUserImage();
                        ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">My Courses</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                My Courses
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <?php
                                        include_once("validate.php"); 
                                        echo coursesGraphNavbar();
                                    ?>
                                </nav>
                            </div>
                            
                            <div class="sb-sidenav-menu-heading">Grades</div>
                            <a class="nav-link" href="grades.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Grades
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-0">
                        <div class="row">
                            <div class="col pt-4">
                                <center>
                                    <hr class="py-1 mb-3">
                                    <h1 class="mt-4 text-center fw-bold"><?php echo $_SESSION['subName'];?></h1>
                                    <img src="./img/assignBanner.gif"
                                    alt="banner" class="img-responsive" width="70%" height="150">
                                    <hr class="py-1 mb-2">
                                    <!-- ASSIGNMENT TITLE -->
                                    <h3 class="mt-4 text-center fw-bold"><?php echo $_SESSION['name_assign'];?></h3>
                                </center>
                            </div>                            
                        </div>
                        <br><br>

                        <div class="row">
                            <div class="col">
                                <div class="row">
                                    <d class="col px-lg-5">
                                        <h5 class="mt-4 text-justify"><span class="fw-bold">Date: </span><?php echo $_SESSION['date_assign'];?></h5>
                                    </d
                                    This dialog will automatically close in 5 seconds.iv>
                                </div>
                                <div class="row">
                                    <div class="col px-lg-5">
                                        <!-- ASSIGMENT DESCRIPTION -->
                                        <h5 class="mt-4 text-justify"><?php echo $_SESSION['descri_assign'];?></h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col px-lg-5 pt-3">
                                        <!-- attached file -->
                                        <h5 class="fw-bold">
                                            <?php include_once("validate.php");
                                                echo fileAttached($_SESSION['file_assign'], true);?>
                                        </h5>
                                    </div>
                                </div>
                                <br><br>

                                <?php
                                    include_once("validate.php");
                                    if($_SESSION['type'] == 'T')
                                        echo graphStudentsListForGrade();
                                ?>
                                <?php
                                    include_once("validate.php");
                                    echo submiStatusAddAssign();
                                ?>

                                <!-- <div class="row">
                                    <div class="col px-lg-5">
                                        <h3 class="mt-4 text-justify fw-bold">Submission status</h3>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-3"></div>
                                    <div class="col-6">
                                        <table class="table table-bordered table-responsive table-striped">
                                            <tbody>
                                                <tr>
                                                    <th class="col-3">Grade</th>
                                                    <td>
                                                        <?php //include_once("validate.php");
                                                        //echo gradedStatus();?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Time remaining</th> 
                                                    <td>
                                                        <?php //include_once("validate.php");
                                                        //echo remainingTime();?>
                                                    </td>
                                                <tr>
                                                    <th>File submissions</th>
                                                    <td>                
                                                        <p class="fw-bold">
                                                            <?php //include_once("validate.php");
                                                            //echo submiFile();?>
                                                        </p>                                        
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div> -->

                                <br><br>
                                <div class="row text-center pb-5">
                                    <div class="col">
                                        <td>
                                            <?php include_once("validate.php");
                                            echo addSubmissionAssign();?>
                                        </td>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Joan Salán 2023</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>