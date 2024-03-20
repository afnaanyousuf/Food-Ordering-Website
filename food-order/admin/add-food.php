<?php include('partials/menu.php') ;?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Food</h1>

        <br><br>
        
        <?php
          if(isset($_SESSION['upload']))
          {
             echo $_SESSION['upload'];
             unset($_SESSION['upload']);
          }
       ?>
    
        <form action="" method="POST" enctype="multipart/form-data">

           <table class="tbl-30">

           <tr>
               <td>Title:</td>
               <td>
                   <input type="text" name="title" placeholder="Title of the food">
               </td>
           </tr>

           <tr>
               <td>Description:</td>
               <td>
                   <textarea name="description" id="" cols="30" rows="5" placeholder="Description of the Food"></textarea>
               </td>
           </tr>

           <tr>
               <td>Price:</td>
               <td>
                   <input type="number" name="price" >
               </td>
           </tr>

              <tr>
                  <td>Select Image:</td>
                  <td>
                      <input type="file" name="image">
                  </td>
              </tr>

              <tr>
                  <td>Category:</td>
                  <td>
                        <select name="category">

                           <?php
                              //create PHP Code to display categories from database
                              //1.create sql query to get all active categories from database
                              $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                                 
                              //executing query
                              $res = mysqli_query($conn, $sql); 
                              
                              //count rows to check whether we have categories or not
                              $count = mysqli_num_rows($res);

                              //if count is greater than zero, we have categories else we don't have categories
                              if($count>0)
                              {
                                  //we have categories
                                  while($row=mysqli_fetch_assoc($res))
                                  {
                                      //get the details of categories
                                      $id = $row['id'];
                                      $title = $row['title'];
                                      
                                      ?>

                                      <option value="<?php echo $id; ?>"><?php echo $title; ?></option>

                                      <?php
                                   }
                              }
                              else
                              {
                                  //we don't have categories
                                  ?>
                                  <option value="0">No Category Found</option>
                                  <?php
                              }

                              
                              //2.display on dropdown 
                           ?>

 
                      </select>
                  </td>
              </tr>

              <tr>
                  <td>Featured:</td>
                  <td>
                     <input type="radio" name="featured" value="Yes">Yes
                     <input type="radio" name="featured" value="No">No
                  </td>
              </tr>

              <tr>
                  <td>Active:</td>
                  <td>
                     <input type="radio" name="active" value="Yes">Yes
                     <input type="radio" name="active" value="No">No
                  </td>
              </tr>

              <tr>
                  <td colspan="2">
                      <input type="submit" name="submit" value="Add Food" class="btn-secondary">
                  </td>
              </tr>

            </table>

        </form>

        
        <?php

           //check whether button is clicked or not
           if(isset($_POST['submit']))
           {
              //add food in database
              //echo"Clicked"; 

              //1.get data from form
              $title =$_POST['title'];
              $description =$_POST['description'];
              $price =$_POST['price'];
              $category =$_POST['category'];

              //check whether radio button for featured and active are checked or not
               if(isset($_POST['featured']))
               {
                   $featured = $_POST['featured'];
               }
               else
               {
                  $featured = "No";//setting the default value
               }

               if(isset($_POST['active']))
               {
                   $active = $_POST['active'];
               }
               else
               {
                  $active = "No";//setting the default value
               }

               //2.upload image if selected
               //check select image is clicked or not and upload the image only if the image is selected
               if(isset($_FILES['image']['name']))
               {
                 //get the details of the selected image
                 $image_name = $_FILES['image']['name'];

                 //check image is selected or not and upload image if selected
                 if($image_name!="")
                 {
                     //image is selected
                     //A.rename the image
                     //get the extension of selected image (jpg, png, gif etc..) "afnaan-yousuf.jpg" afnaan-yousuf jpg
                     $ext = end(explode('.', $image_name));

                     //create new name for Image
                     $image_name = "Food-Name-".rand(0000,9999).".".$ext;//New Image Name may be "Food-Name-657.jpg"

                     //B. Upload Image
                     //get source path and destination path

                     //source path is current location of image
                     $src= $_FILES['image']['tmp_name'];

                     //destination path for image to be uploaded
                     $dst = "../images/food/".$image_name;

                     //Finally Upload food image
                     $upload = move_uploaded_file($src, $dst);

                     //check image uploaded or not
                     if($upload==false)
                     {
                         //failed to upload the image
                         //redirect to add food page with error message
                         $_SESSION['upload'] = "<div class='error'>Failed to Upload Image.</div>";
                         header('location:'.SITEURL.'admin/add-food.php');
                         //stop process
                         die();
                     }
                    
                    } 
              
                }
               else
               {
                  $image_name = "";//setting default value as blank
               }

               //3.Insert Into Database

                //create sql query to save or add food
                //For numerical we don't need to pass value inside quotes '' But for string value it is compulsory to add quotes ''
                $sql2 = "INSERT INTO tbl_food SET
                     title = '$title',
                     description = '$description',
                     price = $price,
                     image_name = '$image_name',
                     category_id = $category,
                     featured ='$featured',
                     active = '$active'
                ";

                //execute query
                $res2 = mysqli_query($conn, $sql2);

                //check data inserted or not
                //4.redirect with message to manage food page
                if($res2 == true)
                {
                    //data inserted successfully
                    $_SESSION['add'] = "<div class='success'>Food Added Successfully.</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }
                else
                {
                    //Failed to insert data
                    $_SESSION['add'] = "<div class='error'>Failed to Add Food.</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');

                }

            }

        ?>


    </div>  
</div> 

<?php include('partials/footer.php') ;?>