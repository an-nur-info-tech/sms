<?php 
//include('../database/Database.php');

?>
<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    
    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
    <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
      <div class="topbar-divider d-none d-sm-block"></div>
      <!-- Nav Item - User Information -->
      <!-- Displaying user image and name -->
          <?php 
            $db = new Database();
            $id = $_SESSION['staff_id'];

            //Checking database connection  
            if(!$db->isConnected()){
              echo $db->getError().PHP_EOL;
            }else{

              $db->query("SELECT * FROM staff_tbl WHERE staff_id = :id;");
              $db->bind(':id', $id);
              $db->execute();
              $value = $db->single();
              
          ?>          
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="mr-2  d-lg-inline text-grey-600 large"><?php echo $_SESSION['name']; ?></span>
		  <img class="img-profile rounded-circle" src="<?php if(($value->passport == null) || (empty($value->passport))){echo "../uploads/default.png"; }else{ echo $value->passport;}   ?>">
        </a>    
          <?php
            } 
            $db->Disconect();
          ?>
        <!-- Dropdown - User Information -->
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
          <a class="dropdown-item" href="#">
            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
            Profile
          </a>
          <a class="dropdown-item" href="#">
            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
            Settings
          </a>
          <a class="dropdown-item" href="#">
            <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
            Activity Log
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
            Logout 
          </a>
        </div>
      </li>
    </ul>

</nav>
<!-- End of Topbar -->