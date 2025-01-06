<?php    
    session_start();
    if(!isset($_SESSION['user']))
        header("location:login.php");
    $_SESSION['fileFlagTeacher'] = false;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <link rel="icon" href="https://cdn.freebiesupply.com/logos/large/2x/keiser-university-logo-png-transparent.png">
        <script src="./js/jquery.js"></script>
        <script>
            $(document).ready(function(){ 
                //alert($('#usType').find(":selected").text());
                $('#checkPass').click(function(){
                    if($('#password').attr('type') == "password")
                        $('#password').attr("type", 'text');
                    else
                        $('#password').attr("type", 'password');
                });   

                $('#saveData').click(function(){
                    if($('#cedula').val() == "" || $('#homeAdd').val() == "" ||
                    $('#name1').val() == "" || $('#lastName1').val() == "" ||
                    $('#email').val() == "" || $('#password').val() == "")
                        alert("Verify the data!");
                    else{
                        $.ajax({
                            url: "validate.php",
                            type: "POST",
                            data:{typeAdd:$('#usType').find(":selected").text(),
                                cedula:$('#cedula').val(),
                                nam1:$('#name1').val(),
                                nam2:$('#name2').val(),
                                lasnam1:$('#lastName1').val(),
                                lasnam2:$('#lastName2').val(),
                                home2:$('#homeAdd').val(),
                                email2:$('#email').val(),
                                pass2:$('#password').val()},
                            success: function(result){
                                if(result == 1){
                                    alert("User added successfully!");
                                    window.open("index.php", "_self");
                                }else
                                    alert("Email or ID is already used!");
                            }
                        });
                    }
                });

                // var typeUser = '<?php //echo $_SESSION['type'];?>';
                // if(typeUser == 'G'){
                //     $('#courses').append('');
                // }
                // $('html').html('');
            });

            function fun(course_id){
                $.ajax({
                    url: "validate.php",
                    type: "POST",
                    data:{course_id:course_id},
                    success: function(result){
                        window.open("course.php", "_self");
                        //alert(result);
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
            
            <!-- Navbar Search-->
            <!-- <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div>
            </form> -->
            <!-- Navbar-->

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
                                    <!-- <a class="nav-link" href="layout-static.html">Static Navigation</a>
                                    <a class="nav-link" href="layout-sidenav-light.html">Light Sidenav</a> -->
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

                <!-- <div class="row">
                    <div class="col-2"></div>
                    <div class="col-3">
                        <div class="card" style="width: 18rem;">
                            <img src="./img/usersAdmin.png" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Users</h5>
                                <p class="card-text">Add, modify, and delete users.</p>
                                <a href="usersAdd.php" class="btn btn-primary">Users Mananger</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="card" style="width: 18rem;">
                            <img src="./img/subjectAdmin.png" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Subjects</h5>
                                <p class="card-text">Add, modify, and delete subjects.</p>
                                <a href="subjectsAdd.php" class="btn btn-primary">Subjects Mananger</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="card" style="width: 18rem;">
                            <img src="./img/courseAdmin.png" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Courses</h5>
                                <p class="card-text">Create a course, setting a teacher.</p>
                                <a href="coursesAdd.php" class="btn btn-primary">Courses Mananger</a>
                            </div>
                        </div>
                    </div>
                </div> -->

                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard</h1>
                        <?php
                            include_once("validate.php"); 
                            echo showAdminOptions();
                        ?>
                        <div class="container-fluid">
                        <!-- <div class="row">
                                <div class="col-4"></div>
                                <div class="col-4">
                                <form>
                                    <br><br><br>
                                    <h5>User Type</h5>
                                    <select class="form-select" aria-label="Default select example" id="usType">
                                        <option value="1">Student</option>
                                        <option value="2">Teacher</option>
                                    </select>
                                <br>
                                <div class="mb-3">
                                    <label for="cedula" class="form-label">User ID</label>
                                    <input type="text" class="form-control" id="cedula">
                                </div>
                                <div class="mb-3">
                                    <div class="col">
                                        <label for="fullName" class="form-label">Name 1</label>
                                        <input type="text" class="form-control" id="name1">
                                    </div>
                                    <div class="col">
                                        <label for="fullName" class="form-label">Name 2</label>
                                        <input type="text" class="form-control" id="name2">
                                    </div>
                                    <div class="col">
                                        <label for="fullName" class="form-label">LastName 1</label>
                                        <input type="text" class="form-control" id="lastname1">
                                    </div>
                                    <div class="col">
                                        <label for="fullName" class="form-label">LastName 2</label>
                                        <input type="text" class="form-control" id="lastname2">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="homeAdd" class="form-label">Home Address</label>
                                    <input type="text" class="form-control" id="homeAdd">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="email" aria-describedby="emailHelp">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password">
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="checkPass">
                                    <label class="form-check-label" for="checkPass">Show/hide</label>
                                </div>
                                <button type="button" class="btn btn-primary" id="saveData">Save</button>                           
                                <br><br><br>
                                </form>
                                </div>
                            </div> -->
                        </div>

                        <?php
                            include_once("validate.php"); 
                            echo hasMyCourses();
                        ?>
                        <div class="row" id="courses">
                            <?php
                                include_once("validate.php"); 
                                echo coursesGraph();
                            ?>
                            <!-- <div class="col-xl-3 col-md-6">
                                <div class="card mb-5" style="width: 18rem;">
                                    <a href="#" class="btn">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRbglsKjO2SxHOSVsa8GOUKfT9CaCR7HSQkRg&usqp=CAU" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <h5 class="card-title mb-0 fw-bold">Card title</h5>
                                        </a>
                                            <hr>
                                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the cards content.</p>
                                        </div>
                                </div>
                            </div> -->
                        </div>
                        <?php
                            include_once("validate.php"); 
                            echo graphAllCoursesStudents();
                        ?>



                        <!-- <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                DataTable Example
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Office</th>
                                            <th>Age</th>
                                            <th>Start date</th>
                                            <th>Salary</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Office</th>
                                            <th>Age</th>
                                            <th>Start date</th>
                                            <th>Salary</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <tr>
                                            <td>Tiger Nixon</td>
                                            <td>System Architect</td>
                                            <td>Edinburgh</td>
                                            <td>61</td>
                                            <td>2011/04/25</td>
                                            <td>$320,800</td>
                                        </tr>
                                        <tr>
                                            <td>Garrett Winters</td>
                                            <td>Accountant</td>
                                            <td>Tokyo</td>
                                            <td>63</td>
                                            <td>2011/07/25</td>
                                            <td>$170,750</td>
                                        </tr>                                        
                                    </tbody>
                                </table>
                            </div>
                        </div> -->
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
