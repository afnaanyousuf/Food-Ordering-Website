<?php include('partials/menu.php'); ?>

<div class="main-content"> 
    <div class="wrapper">
      <h1>Add Category</h1>

      <br><br>

      <?php

      if(isset($_SESSION['add']))
      {
          echo $_SESSION['add'];
          unset($_SESSION['add']);
      }

      if(isset($_SESSION['upload']))
      {
          echo $_SESSION['upload'];
          unset($_SESSION['upload']);
      }

      ?>

      <br><br>

      <!-- Add Category Form Starts -->
      <form action ="" method="POST" enctype="multipart/form-data" >

          <table class="tbl-30">
           <tr>
               <td>Title:</td>
               <td>
                   <input type="text" name="title" placeholder="Category Title">
               </td>
           </tr>

           <tr></tr>
           <td>Select Image</td>
           <td>
               <input type="file" name="image">
           </td>
         
           <tr>
               <td>Featured:</td>
               <td>
                 <input type="radio" name="featured" value="Yes"> Yes
                 <input type="radio" name="featured" value="No"> No
               </td>
           </tr>

           <tr>
               <td>Active:</td>
               <td>
                  <input type="radio" name="active" value="Yes"> Yes
                  <input type="radio" name="active" value="No"> No
               </td>
           </tr>

           <tr>
               <td colspan="2">
                  <input type="submit" name="submit" value="Add Category" class="btn-secondary"> 
               </td>
           </tr>
           
        </table>  
         
      </form>
      <!-- Add Category Form Ends -->

      <?php
        
        //check whether the submit button is clicked or not
        if(isset($_POST['submit']))
        {
            ///echo"Clicked";

            //1.Get the Value from Category Form
            $title =$_POST['title'];

            //For Radio input,we need to check whether the button is clicked or not
            if(isset($_POST['featured']))
            {
               //Get the value from form
               $featured = $_POST['featured']; 
            }
            else
            {
                // Set the Default Value
                $featured = "No";
            }
            //active button

            if(isset($_POST['active']))
            {
                $active = $_POST['active'];
            }
            else
            {
                $active = "No"; 
            }

            //Check whether the image is selected or not and set the value for image name accordingly
            //print_r($_FILES['image']);

            //die();//Break the code here

            if(isset($_FILES['image']['name']))
            {
               //upload the Image
               //upload the Image we need image name,source path and destination path
               $image_name =$_FILES['image']['name'];
                
               //upload the image only if image is selected
             if($image_name !="")
                {

             
                 //Auto Rename our image
                 //Get the extension of our image(jpg, png, gif, etc) e.g "specialfood1.jpg"
                 $ext = end(explode('.',$image_name));

                  //Rename the Image
                  $image_name ="Food_Category_".rand(000, 999).'.'.$ext; //e.g. Food_Category_834.jpg
                 

                 $source_path =$_FILES['image']['tmp_name'];

                 $destination_path ="../images/category/".$image_name;

                 //Finally upload the image
                 $upload =move_uploaded_file($source_path, $destination_path);

                 //Cheeck whether the image is uploaded or not
                 // And if the image is uploaded then we will stop the process & redirect with error message
                 if($upload==false)
                   {
                     //Set Message
                     $_SESSION['upload'] ="<div class='error'>Failed to upload Image.</div>";
                     //Redirect to Add Category Page
                     header('location:'.SITEURL.'admin/add-category.php');
                     //Stop the Process
                     die();
                    }
                
                }
            }
            else
            {
                // Don't upload the Image and set the image_name value or not
                $image_name="";
            }

            //2.Create Sql query to insert Category into Database
            $sql = "INSERT INTO tbl_category SET
                title='$title',
                image_name='$image_name',
                featured='$featured',
                active='$active'
            ";
              
            //3.Execute the Query and Save in Database
            $res = mysqli_query($conn, $sql);

            //4.Check whether the query executed or not and data added or not
            if($res==true)
            {
                //query executed and category added
                  $_SESSION['add'] ="<div class='success'>Category Added Successfully.</div>";
                //redirect to manage category page
                 header('location:'.SITEURL.'admin/manage-category.php');
            }
            else
            {
                //Failed to Add Category
                $_SESSION['add'] ="<div class='error'>Failed to add category.</div>";
                //redirect to manage category page
                 header('location:'.SITEURL.'admin/add-category.php');

            }
        }

        ?>



    </div>
</div>


<?php include('partials/footer.php'); ?>