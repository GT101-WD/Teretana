<?php

   require_once 'config.php';
   
  if(!isset($_SESSION['admin_id'])) {
    header('location: index.php');
    exit();
  } 

  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admine Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
  </head>
  <body>
    <?php if(isset($_SESSION['success_message'])) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
         
          <?php 
              echo $_SESSION['success_message']; 
              unset($_SESSION['success_message']);
          ?>
          
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
     
    <?php } ?>


      <div class="container">

            <div class="row">

                <div class="col-md-12">

                    <h2>Member List 2026</h2>

                    <table class="table table-striped">
                        <thead>    
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Trainer</th>
                                <th>Photo</th>
                                <th>Training Plan</th>
                                <th>Access Card</th>
                                <th>Created At</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            
                            $sql = "SELECT * FROM members";

                            $run = $conn->query($sql);
                            
                            $results = $run->fetch_all(MYSQLI_ASSOC);

                            foreach($results as $result) : ?>

                              <tr>
                                    <td><?php echo $result['first_name']; ?></td>
                                    <td><?php echo $result['last_name']; ?></td>
                                    <td><?php echo $result['email']; ?></td>
                                    <td><?php echo $result['phone_number']; ?></td>
                                    <td><?php echo $result['trainer_id']; ?></td>
                                    <td><img style="width: 60px;" src="<?php echo $result['photo_path']; ?>"></td>
                                    <td><?php 
                                        
                                        $plan_id = $result['training_plan_id'];

                                        $sql = "SELECT * FROM training_plans WHERE plan_id = ?";
                                        $run = $conn->prepare($sql);
                                        $run->bind_param('i', $plan_id);
                                        $run->execute();
                                        
                                        $results = $run->get_result();
                                        $results = $results->fetch_assoc();

                                        if($results) {
                                            echo $results['name'];
                                        } else {
                                            echo "Nema plana";
                                        }
                                        
                                        ?></td>
                                    <td><a target="_blank" href="<?php echo $result['access_card_pdf_path']; ?>">Access Card</td>
                                    <td><?php 
                                        
                                        $created_at = strtotime($result['created_at']);
                                        $new_date = date("F, jS Y", $created_at);
                                        echo $new_date;
                                        
                                        ?></td>
                                  
                                    <td><button>DELETE</button></td>

                                    
                              </tr>  

                            <?php endforeach; ?> 

                        </tbody>
                    </table>

                </div>

            </div>



        <div class="row mb-5">
            <div class="col-md-6">
                <h2>Register Member</h2>
                <form action="register_member.php" method="post" enctype="multipart/form-data">
                    First Name: <input class="form-control" type="text" name="first_name"><br>
                    Last Name: <input class="form-control" type="text" name="last_name"><br>
                    Email: <input class="form-control" type="email" name="email"><br>
                    Phone Number: <input class="form-control" type="text" name="phone_number"><br>
                    Training Plan: 
                    <select class="form-control" name="training_plan_id">
                        <option value="" disabled selected>Training Plan</option>
                        
                        <?php 
                          $sql = "SELECT * FROM training_plans";
                          $run = $conn->query($sql);
                          $results = $run->fetch_all(MYSQLI_ASSOC);
                          
                          foreach($results as $result) {
                            
                            echo "<option value='" . $result['plan_id'] . "'>" . $result['name'] . "</option>";

                          }
                        ?>
                    </select><br>
                    <input type="hidden" name="photo_path" id="photoPathInput">
                    <div id="dropzone-upload" class="dropzone"></div>

                    <input class="btn btn-prymary mt-3" type="submit" value="Register Member">
                </form>
            </div>
        </div>
    </div>

    <?php $conn->close(); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script>
        Dropzone.options.dropzoneUpload = {
            url: "upload_photo.php",
            paramName: "photo",
            maxFilesize: 20, // MB
            acceptedFiles: "image/*",
            init: function() {
                      this.on("success", function (file, response) {
                          // Parse the JSON response
                          const jsonResponse = JSON.parse(response); 
                          // Check if the file was uploaded successfully
                          if (jsonResponse.success) {
                              // Set the hidden input's value to the uploaded file's path
                              document.getElementById('photoPathInput').value = jsonResponse.photo_path;
                          } else {
                              console.error(jsonResponse.error);
                          }
                      });
                  }
        };

    </script>
  </body>
  </html>