<?php
    session_start();
    if(isset($_SESSION['user']))
        header("location:index.php");  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="icon" href="https://cdn.freebiesupply.com/logos/large/2x/keiser-university-logo-png-transparent.png">
    <script src="./js/jquery.js"></script>
    <script>
      $(document).ready(function(){
        $('#login').click(function(){
          $.post("validate.php",
          {
            u:$('#user').val(),
            p:$('#password').val()
          },
          function(data, status){
            // alert("Value: "+data+"\nStatus: "+status);
            if(data == 1)
              window.open("index.php", "_self");
            else
              alert("Invalid Email or Password!");
          });
        });

        $('#guest').click(function(){
          $.post("validate.php",
          {
            g:'GUEST'
          },
          function(data, status){
            window.open("index.php", "_self");
          })
        });
        
      });
    </script>
  </head>
<body>
  <section class="vh-100" style="background-color: #2E294E;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
          <div class="card" style="border-radius: 1rem;">
            <div class="row g-0">
              <div class="col-md-6 col-lg-6">
                <img src="https://i.pinimg.com/originals/19/42/08/194208d903f4cd91acc7cb4b818bddf1.png"
                  alt="Image" class="img-fluid" style="border-radius: 1rem 0 0 1rem;"/>
              </div>
              <div class="col-md-6 col-lg-6 d-flex align-items-center">
                <div class="card-body p-4 p-lg-5 text-black">

                  <form>

                    <div class="d-flex align-items-center mb-3 pb-1">
                      <i class="fas fa-cubes fa-2x me-5" style="color: #ff6219;"></i>
                      <span class="h1 fw-bold mb-0">Welcome back!</span>
                    </div>

                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign into your account</h5>

                    <div class="form-outline mb-4">
                      <label class="form-label" for="form2Example17">User</label>
                      <input type="email" id="user" class="form-control form-control-lg" placeholder="Email address"/>
                    </div>

                    <div class="form-outline mb-4">
                      <label class="form-label" for="form2Example27">Password</label>
                      <input type="password" id="password" class="form-control form-control-lg" placeholder="Password"/>
                    </div>

                    <div class="pt-1 mb-4 d-grid gap-2 col-3 mx-auto">
                      <button class="btn btn-dark btn-lg" id="login" type="button">Login</button>
                    </div>

                    <div class="d-grid gap-2 col-3 mx-auto">
                      <button class="btn btn-info btn-sm" id="guest" type="button">Login as Guest</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>
<script>
</script>
</html>