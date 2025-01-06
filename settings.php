<?php    
    include_once("validate.php");

    session_start();
    if(!isset($_SESSION['user']))
        header("location:login.php");
    if($_SESSION['type'] == "G")
        header("location:index.php");

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Settings</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <link rel="icon" href="https://cdn.freebiesupply.com/logos/large/2x/keiser-university-logo-png-transparent.png">
        <script src="./js/jquery.js"></script>
        

        <!-- Required library for webcam -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.24/webcam.js"></script>
        <!-- Bootstrap theme -->
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->


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

                $('#checkPass').click(function(){
                    if($('#password').attr('type') == "password")
                        $('#password').attr("type", 'text');
                    else
                        $('#password').attr("type", 'password');
                });

                $('#saveData').click(function(){
                    if($('#homeAdd').val() == "" || $('#email').val() == "" ||
                        $('#password').val() == "")
                        alert("Verify the data!");
                    else{
                        $.ajax({
                            url: "validate.php",
                            type: "POST",
                            data:{home:$('#homeAdd').val(),
                                email:$('#email').val(),
                                pass:$('#password').val()},
                            success: function(result){
                                if(result == 1){
                                    alert("Data saved successfully!");
                                    window.open("settings.php", "_self");
                                }else
                                    alert("Email is already used!");
                            }
                        });
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

            function funAddAssign(){
                window.open("addAssign.php", "_self");
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
                                    <img src="./img/dataBanner.jpg"
                                    alt="banner" class="img-responsive" width="70%" height="120">
                                    <hr class="py-1 mb-2">
                                </center>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid px-4 py-2">
                        <h1 class="mt-4 text-center fw-bold">Personal Information</h1>
                        <br><br><br>
                    </div>
                    <div class="container-fluid">	
                        <div class="row">
                            <div class="col-lg-6" align="center">
                                <label><h3>Capture live photo</h3></label>
                                <div id="my_camera" class="pre_capture_frame" ></div>
                                <input type="hidden" name="captured_image_data" id="captured_image_data">
                                <br>
                                <input type="button" class="btn btn-warning btn-round btn-file" value="Take Snapshot" onClick="take_snapshot()">	
                            </div>
                            <div class="col-lg-6" align="center">
                                <label><h3>Result</h3></label>
                                <div id="results" >
                                    <img style="width: 350px;" class="after_capture_frame" src="./img/usersImages/<?php echo $_SESSION['pic'];?>" />
                                </div>
                                <br>
                                <button type="button" class="btn btn-success" onclick="saveSnap()" id="saveBtn" disabled>Save Picture</button>
                            </div>	
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-4"></div>
                            <div class="col-4">
                            <form>
                                <br><br><br>
                            <div class="mb-3">
                                <label for="cedula" class="form-label">User ID</label>
                                <input type="text" class="form-control" id="cedula" value="<?php echo $_SESSION['id'];?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" value="<?php echo $_SESSION['user'];?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="homeAdd" class="form-label">Home Address</label>
                                <input type="text" class="form-control" id="homeAdd" value="<?php echo $_SESSION['address'];?>">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" aria-describedby="emailHelp" value="<?php echo $_SESSION['email'];?>">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" value="<?php echo $_SESSION['password'];?>">
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="checkPass">
                                <label class="form-check-label" for="checkPass">Show/hide</label>
                            </div>
                            <button type="button" class="btn btn-primary" id="saveData">Submit</button>
                            <br><br><br>
                            </form>
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
    <script language="JavaScript">
        // Configure a few settings and attach camera 250x187
        Webcam.set({
        width: 350,
        height: 287,
        image_format: 'jpeg',
        jpeg_quality: 90
        });	 
        Webcam.attach( '#my_camera' );
        
        function take_snapshot() {
            // take snapshot and get image data
            Webcam.snap( function(data_uri) {
            // display results in page
            document.getElementById('results').innerHTML = 
            '<img class="after_capture_frame" src="'+data_uri+'"/>';
            $("#captured_image_data").val(data_uri);
            });
            document.getElementById("saveBtn").disabled = false;            
        }

        function saveSnap(){
        var base64data = $("#captured_image_data").val();
        $.ajax({
                type: "POST",
                dataType: "json",
                url: "validate.php",
                data: {image: base64data},
                success: function(data) { 
                    alert(data);
                    window.open("settings.php", "_self");
                }
            });
        }
    </script>
</html>