<?php include('partials/menu.php') ;?>

<?php
   //check id is set or not
   if(isset($_GET['id']))
   {
       //get all details
       $id = $_GET['id'];

       //sql query to get selected food
       $sql2 = "SELECT * FROM tbl_food WHERE id=$id";
       //execute query
       $res2 = mysqli_query($conn, $sql2);

       //get value based on query executed
       $row2 = mysqli_fetch_assoc($res2);

       //get individual values of selected food
               $title = $row2['title'];
               $description = $row2['description'];
               $price = $row2['price'];
               $current_image = $row2['image_name'];
               $current_category = $row2['category_id'];
               $featured = $row2['featured'];
               $active = $row2['active'];

    }
   else
   {
       //redirect to manage food
       header('location:'.SITEURL.'admin/manage-food.php');
   }
?>

<div class="main-content">
    <div class="wrapper">
       <h1>Update Food</h1>
       <br><br>

       <form action="" method="POST" enctype="multipart/form-data">

            <table class="tbl-30">

               <tr>
                 <td>Title:</td>
                  <td>
                     <input type="text" name="title" value="<?php echo $title; ?>">
                 </td>
               </tr>

              <tr>
                  <td>Description:</td>
                  <td>
                     <textarea name="description" cols="30" rows="5"><?php echo $description; ?></textarea>
                  </td>
              </tr>

              <tr>
                 <td>Price:</td>
                 <td>
                     <input type="number" name="price" value="<?php echo $price; ?>">
                 </td>
              </tr>

              <tr>
                  <td>Current Image:</td>
                  <td>
                     <?php
                       if($current_image == "")
                       {
                           //image not avaialble
                           echo "<div class='error'>Image Not Available.</div>";
                       }
                       else
                       {
                           //image available
                           ?>
                           <img src="<?php echo SITEURL; ?>images/food/<?php echo $current_image; ?>" width="150px">
                           <?php
                       }

                     ?>
                  </td>
              </tr>

              <tr>
                 <td>Select New Image:</td>
                 <td>
                    <input type="file" name="image">
                 </td>
              </tr>

             <tr>
                 <td>Category:</td>
                 <td>
                     <select name="category">

                        <?php
                            //Query to get active categories
                            $sql = "SELECT * FROM tbl_category WHERE active= 'Yes'";
                            //execute query
                            $res = mysqli_query($conn, $sql);
                            //count rows
                            $count = mysqli_num_rows($res);

                            //check categories available or not
                            if($count>0)
                            {
                                //categories available
                                while($row=mysqli_fetch_assoc($res))
                                {
                                    $category_title = $row['title'];
                                    $category_id = $row['id'];
                                   
                                    //echo "<option value='$category_id'>$category_title</option>";
                                    ?>
                                    <option <?php if($current_category==$category_id){echo "Selected";} ?> value="<?php echo $category_id; ?>"><?php echo $category_title; ?></option>
                                    <?php
                                }
                            }
                            else
                            {
                                //categories not available
                                echo "<option value='0'>Category Not Available</option>";
                            }

                        ?>

                     </select>
                 </td>
               </tr>

              <tr>
                 <td>Featured:</td>
                 <td>
                   <input <?php if($featured=="Yes") {echo "checked";} ?> type="radio" name="featured" value="Yes">Yes
                   <input <?php if($featured=="No") {echo "checked";} ?> type="radio" name="featured" value="No">No
                 </td>
              </tr>
 
              <tr>
                 <td>Active:</td>
                 <td>
                   <input <?php if($active=="Yes") {echo "checked";} ?> type="radio" name="active" value="Yes">Yes
                   <input <?php if($active=="No") {echo "checked";} ?> type="radio" name="active" value="No">No
                 </td>
               </tr>

               <tr>
                   <td>
                      <input type="hidden" name="id" value="<?php echo $id; ?>">
                      <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">

                      <input type="submit" name="submit" value="Update Food" class="btn-secondary">
                  </td>
              </tr>

            </table>

       </form>

        <?php

         if(isset($_POST['submit']))
         {
             //echo "Button Clicked";

             //1.Get all details from form
             $id = $_POST['id'];
             $title = $_POST['title'];
             $description = $_POST['description'];
             $price = $_POST['price'];
             $current_image = $_POST['current_image'];
             $category = $_POST['category'];

             $featured = $_POST['featured'];
             $active = $_POST['active'];

             //2.upload the image if selected

             //check whether upload button is clicked or not
             if(isset($_FILES['image']['name']))
             {
                     //upload button clicked
                     $image_name = $_FILES['image']['name']; //New Image Name

                     //check whether the file is available or not
                      if($image_name !="")
                        {
                          //image is available
                          //A.Uploading Image

                          //rename the image
                          $ext = end(explode('.', $image_name)); //gets the extension of image

                          $image_name = "Food-Name-".rand(0000, 9999).'.'.$ext; //This will be renamed image

                          //get source path and destination path
                          $src_path = $_FILES['image']['tmp_name']; //source path
                          $dest_path = "../images/food/".$image_name; //destination path

                          //upload image
                          $upload = move_uploaded_file($src_path, $dest_path);

                             //check whether image is uploaded or not
                             if($upload==false)
                                {
                                 //failed to upload
                                 $_SESSION['upload'] = "<div class='error'>Failed to upload New Image</div>";
                                 //redirect to manage food
                                 header('location:'.SITEURL.'admin/manage-food.php');
                                 //stop process
                                 die();
                              }
                            //3.Remove image if new image is uploaded and current image exists
                           //B. Remove Current Image if Available
                           if($current_image!="")
                           {
                              //current image is available
                              //remove image
                              $remove_path = "../images/food/".$current_image;

                              $remove = unlink($remove_path);

                              //check whether image is removed or not
                               if($remove==false)
                               {
                                  //failed to remove current image
                                  $_SESSION['remove-failed'] ="<div class='error'>Failed to remove Current Image.</div>";
                                  //Redirect to manage food
                                  header('location:'.SITEURL.'admin/manage-food.php');
                                  //stop process
                                  die();
                               }
                            }
                        }
                        else
                        {
                         $image_name = $current_image;//Default image when image is not selected
                        }
             }   
             else
             {
                 $image_name = $current_image; //Default Image when button is not clicked
             }
             
             //4.update food in database
             $sql3 = "UPDATE tbl_food SET
                  title = '$title',
                  description = '$description',
                  price = $price,
                  image_name = '$image_name',
                  category_id = '$category',
                  featured = '$featured',
                  active = '$active'
                  WHERE id=$id 
                ";

                //execute sql query
                $res3 = mysqli_query($conn, $sql3);

                //check whether the query is executed or not
                if($res3==true)
                {
                    //Query Executed and Food Updated
                    $_SESSION['update'] = "<div class='success'>Food Updated Successfully.</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');               
                }
                else
                {
                    //failed to update food
                    $_SESSION['update'] = "<div class='error'>Failed to Update Food.</div>";
                    header('location:'.SITEURL.'admin/manage-food.php');
                }

             
            }

        ?>

    </div>
</div>

<?php include('partials/footer.php') ;?>