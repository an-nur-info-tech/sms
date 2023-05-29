<?php 
if (isset($_POST['test_btn']))
{
    $test = $_POST['test'];
    $total = 10;

    $arr = array("name"=>"Bello Ibrahim", "age"=>35, "sex"=>"Male");

    if ($total == 34)
    {
        echo "No".PHP_EOL;
        var_dump($arr);
    }else if ($total == 10)
    {
        echo "Yes".PHP_EOL;
        var_dump($arr);
    }
    var_dump($arr);
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
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="text" name="test">
        <button name="test_btn" type="submi">Click</button>
    </form>
</body>
</html>