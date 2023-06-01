<?php 
function img($f){
    $target_file = "";
    list($width, $height) = getimagesize($f);
      //var_dump($_FILES["profilePicture"]["type"]);
      $newWidth = 500;
      $newHeight = 500;
      // $directory = "views/img/users/" . $_POST["name"];

      if ($_FILES['f_Image']['type'] == "image/jpeg") {
        // $ra = mt_rand(100, 999);
        // $root = "views/img/users/" . $_POST["name"] . "/" . $ra . ".jpeg";
        $target_file = "hello".".jpeg";

        $source = imagecreatefromjpeg($f);
        $destination = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresized($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagejpeg($destination, $target_file);
      }
      if ($_FILES['f_Image']['type'] == "image/png") {
          // $ra = mt_rand(100, 999);
          // $root = "views/img/users/" . $_POST["name"] . "/" . $ra . ".png";
          $target_file = "hello".".png";

          $source = imagecreatefromjpeg($f);
          $destination = imagecreatetruecolor($newWidth, $newHeight);
          imagecopyresized($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
          imagejpeg($destination, $target_file);
      }
      if ($_FILES['f_Image']['type'] == "image/jpg") {
          // $ra = mt_rand(100, 999);
          // $root = "views/img/users/" . $_POST["name"] . "/" . $ra . ".png";
          $target_file = "hello".".jpg";

          $source = imagecreatefromjpeg($f);
          $destination = imagecreatetruecolor($newWidth, $newHeight);
          imagecopyresized($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
          imagejpeg($destination, $target_file);
      }

    return $target_file;
}
if (isset($_POST['test_btn']))
{
    $test = $_POST['test'];
    $f_Image = $_FILES["f_Image"]["name"];

    $result = img($_FILES["f_Image"]["tmp_name"]);
    // $total = 10;

    // $arr = array("name"=>"Bello Ibrahim", "age"=>35, "sex"=>"Male");

    // echo $f_Image;
    var_dump($result);
    // {
    //     list($width, $height) = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    //   //var_dump($_FILES["profilePicture"]["type"]);
    //   $newWidth = 500;
    //   $newHeight = 500;
    //   // $directory = "views/img/users/" . $_POST["name"];

    //   if ($_FILES['fileToUpload']['type'] == "image/jpeg") {
    //     // $ra = mt_rand(100, 999);
    //     // $root = "views/img/users/" . $_POST["name"] . "/" . $ra . ".jpeg";

    //     $source = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
    //     $destination = imagecreatetruecolor($newWidth, $newHeight);
    //     imagecopyresized($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    //     imagejpeg($destination, $target_file);
    //   }
    // }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
        <input type="text" name="test">
        <input type="file" name="f_Image" >
        <button name="test_btn" type="submi">Click</button>
    </form>
</body>
</html>