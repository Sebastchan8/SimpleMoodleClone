<?php    
    session_start();
    if(!isset($_SESSION['user']))
        header("location:login.php");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title><?php echo $_SESSION['subName'];?></title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <link rel="icon" href="https://cdn.freebiesupply.com/logos/large/2x/keiser-university-logo-png-transparent.png">
        <script src="./js/jquery.js"></script>
        <script>

            $(document).ready(function () {
                var isEnroll = '<?php echo $_SESSION['type'].$_SESSION['isEnrolled'];?>';
                //alert(isEnroll);
                if(isEnroll == "S0")
                    $('#modalEnrolling').modal('show');
                
                $('#close').click(function(){
                    window.open("index.php", "_self");
                });

                //var msg = '<?php //echo $_SESSION['variable'];?>';
                $('#enroll').click(function(){
                    // alert(msg);
                    $.ajax({
                        url: "validate.php",
                        type: "POST",
                        data:{enroll:" "},
                        success: function(result){
                            if(result == 1){
                                alert("Enrolled successfully!");
                                window.open("index.php", "_self");
                            }else
                                alert("Something went wrong. Not Enrolled!");
                        }
                    });
                    
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

            function funAssign(assign_id){
                $.ajax({
                    url: "validate.php",
                    type: "POST",
                    data:{assign_id:assign_id},
                    success: function(result){
                        window.open("assignment.php", "_self");
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
                                    <img src="./img/gradesPicture.jpg"
                                    alt="banner" class="img-responsive" width="70%" height="200">
                                    <hr class="py-1 mb-2">
                                </center>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid px-4 py-2">
                        <h1 class="mt-4 text-center fw-bold pb-5"><?php echo $_SESSION['subName'];?></h1>

                        <div class="row" id="assig">
                            <?php
                                include_once("validate.php");
                                echo graphAssignments2(dataAssignment());
                            ?>                            
                        </div>

                        <?php
                            include_once("validate.php");
                            if($_SESSION['type'] == 'T')
                                echo graphStudentsList();
                        ?>
                        <!-- <center>
                            <div class="card mb-4" style="display: block;overflow-x: auto;white-space: nowrap; width: 1300px;">
                                <div class="card-header">
                                    <i class="fas fa-table me-1"></i>
                                    Students List
                                </div>
                                <div class="card-body">
                                    <table id="datatablesSimple">
                                        <thead>
                                            <tr>
                                                <th>Student</th>
                                                <th>Task 1</th>
                                                <th>Task 2</th>
                                                <th>Average</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>JOAN SEBASTIAN SALAN TAMAMI</td>
                                                <td>-</td>
                                                <td>10</td>
                                                <td>5</td>
                                            </tr>
                                            <tr>
                                                <td>JOSE LUIS ACOSTA REYES</td>
                                                <td>10</td>
                                                <td>9</td>
                                                <td>10</td>
                                            </tr>                                         
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </center> -->
                        
                        <div class="row">
                            <div class="col-3"></div>
                            <div class="col-6">                                
                                <?php
                                    if($_SESSION['type'] == "S")
                                        echo '<hr class="py-1 mb-3">';
                                ?>
                            </div>
                        </div>

                        <div class="row" id="assig" >                            
                            <div class="col-6"></div>
                            <div class="col-2 text-start text-success">                                
                                <h4 class="fw-bold">
                                    <?php
                                        if($_SESSION['type'] == "S")
                                            echo 'Final Grade: ';
                                    ?>
                                </h4>
                            </div>
                            <div class="col-1">
                                <h3 class="fw-bold text-primary">
                                    <?php
                                        include_once("validate.php");
                                        if($_SESSION['type'] == "S")
                                            echo gradeAverage($_SESSION['id']);
                                    ?>
                                </h3>
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