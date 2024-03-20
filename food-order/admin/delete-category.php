<?php
   //Include Constants File
   include('../config/constants.php');

   //echo "Delete Page";
   //Check whether the id and image_name value is set or not
   if(isset($_GET['id']) AND isset($_GET['image_name']))
   {
       //Get the value and delete
       //echo "Get the Value and Delete";
       $id = $_GET['id'];
       $image_name = $_GET['image_name'];

       //Remove the physical image file is avaialable
       if($image_name != "")
       {
           //Image is available.So remove it
           $path = "../images/category/".$image_name;
           // Remove the image
           $remove =unlink($path);

          //If failed to remove image then add an error message and stop the process
           if($remove==false)
           {
               //Set Session Message
               $_SESSION['remove'] = "<div class='error'>Fail to Remove Category image.</div>";
               //redirect message category page
               header('location:'.SITEURL.'admin/manage-category.php');
               //stop process
               die();
           }
       }

       //Delete Data from database
       //sql query to delete data from database
       $sql ="DELETE FROM tbl_category WHERE id=$id";

       //execute the query
       $res = mysqli_query($conn, $sql);

       //check whether the data is delete from database or not
       if($res==true)
       {
           //Set success message and redirect
           $_SESSION['delete'] ="<div class='success'>Category Deleted Successfully.</div>";
           //Redirect to manage category page
           header('location:'.SITEURL.'admin/manage-category.php');
       }   
       else
       {
           //set fail message and redirect
           $_SESSION['delete'] ="<div class='error'>Failed to delete Category.</div>";
           //Redirect to manage category page
           header('location:'.SITEURL.'admin/manage-category.php');

       }

      

   } 
   else
   {
       //Redirect to manage Category Page
      header('location:'.SITEURL.'admin/manage-category.php');
   }
?>