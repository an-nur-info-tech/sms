<?php
require_once('security.php');
require_once('../database/Database.php');
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
  <meta name="description" content="">
  <meta name="author" content="">

  <?php
  $db = new Database();
  $db->query("SELECT * FROM frontend_tbl");
  if ($db->execute()) {
    if ($db->rowCount() > 0) {
      $row = $db->single();
      $title = $row->project_name;
      $logo_img = $row->img_logo;
  ?>
      <title><?php echo $title; ?></title>
      <link rel="icon" href="<?php echo $logo_img; ?>" type="image/png" />

    <?php
    }else {
    ?>
      <title>School Mangement System</title>
      <!-- <link rel="icon" href="../uploads/img/dns-server.jpg" type="image/png" /> -->

  <?php
    }
  } else {
    die($db->getError());
  }
  $db->Disconect();
  ?>

  <!-- Custom fonts for this template-->
  <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <!-- Sweet alert scripts -->
  <script src="../assets/sweetalert2/sweetalert.all.min.js"></script>


</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">
    <?php
    require_once('sidebar.php');
    require_once('navbar.php');
    ?>