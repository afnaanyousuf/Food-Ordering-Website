<?php 
    //Include Constants Page
    include('../config/constants.php');

    //echo "Delete Food Page";

    if(isset($_GET['id']) && isset($_GET['image_name'])) // Either use '&&' or 'AND'
    {
      //process to delete
      //echo "Process to Delete";

      //1.get id and image_name
      $id= $_GET['id'];
      $image_name = $_GET['image_name'];

      //2.remove image if available
      //check image is available or not
      if($image_name != "")
      {
          //it has image & need to remove from folder
          //get image path
          $path = "../images/food/".$image_name;

          //Remove image file from folder
          $remove = unlink($path);

          //check whether image is removed or not
          if($remove==false)
          {
              //Failed to remove image
              $_SESSION['upload']= "<div class ='error'>Failed to remove image file.</div>";    
              //redirect to manage food
              header('location:'.SITEURL.'admin/manage-food.php'); 
              //stop process of deleting food 
              die();  
          } 
       
        }

      //3.delete food from database
      $sql = "DELETE FROM tbl_food WHERE id=$id ";
      //execute query
      $res = mysqli_query($conn, $sql);

      //check query executed or not and set the session message respectively
      //4.Redirect to Manage Food with session message
      if($res==true)
      {
          //food deleted
          $_SESSION['delete'] = "<div class='success'>Food Deleted Successfully.</div>";
          header('location:'.SITEURL.'admin/manage-food.php');
      }
      else
      {
          //failed to delete food
          $_SESSION['delete'] = "<div class='error'>Failed to Delete Food.</div>";
          header('location:'.SITEURL.'admin/manage-food.php');
      }

      

    }
    else
    {
       //redirect to Manage Food Page
       //echo "redirect";
       $_SESSION['unauthorize'] = "<div class='error'>Unauthorized Access.</div>";
       header('location:'.SITEURL.'admin/manage-food.php');
    }

?>