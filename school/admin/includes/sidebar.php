
  <!-- Sidebar -->
  <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard">
        <div class="sidebar-brand-icon rotate-n-0">
          <?php
            $db = new Database();
            $db->query("SELECT * FROM frontend_tbl");
            if ($db->execute()) {
              if ($db->rowCount() > 0) {
                  $row = $db->single();
                  $logo_img = $row->img_logo;
                echo "<img src='$logo_img' width='50' height='50'>";
              
                }else{

                echo "<img src='../uploads/img/success.png' width='50' height='50'>";
              }
            } 
          ?>
        </div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="dashboard">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Interfaces
      </div>

      <!-- Nav Item - Manage Student Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-user"></i>
          <span>Manage Students</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Manage Students:</h6>
            <a class="collapse-item" href="student-reg"> Register student</a>
            <a class="collapse-item" href="student-view-page">View Student</a>
            <a class="collapse-item" href="health-page"> Health Records</a>
            <a class="collapse-item" href="guardian-page">Guardians/Parents</a>
            <a class="collapse-item" href="view-guardian-page">View Guardians details </a>
          </div>
        </div>
      </li>

      <!-- Nav Item - Manage Staffs Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
          <i class="fas fa-fw fa-user"></i>
          <span>Manage Staffs</span>
        </a>
        <div id="collapseThree" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Manage Staffs:</h6>
            <a class="collapse-item" href="staff-reg-page"> Register staff</a>
          </div>
        </div>
      </li>

      <!-- Nav Item - Result Processing Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
        <i class="fas fa-download fa-sm text-white-50"></i>
          <span>Results Panel</span>
        </a>
        <div id="collapseFour" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Manage result:</h6>
            <a class="collapse-item" href="add-result"> Upload result</a>
            <a class="collapse-item" href="result-view"> Download Result</a>
          </div>
        </div>
      </li>

      <!-- Nav Item - Exporting/Importing as Excel Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSix" aria-expanded="true" aria-controls="collapseFive">
          <i class="fas fa-fw fa-file-excel " aria-hidden="true"></i>
          <span>Spreadsheet </span>
        </a>
        <div id="collapseSix" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Spreadsheet Templates:</h6>
            <a class="collapse-item" href="excel-download"> Download result </a>
            <a class="collapse-item" href="excel-upload"> Upload result </a>
            <a class="collapse-item" href="comment-download"> Download Comment </a>
            <a class="collapse-item" href="comment-upload"> Upload Comment </a>
          </div>
        </div>
      </li>      

      <!-- Nav Item - Result Processing Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
          <i class="fas fa-fw fa-cog"></i>
          <span>Year Session </span>
        </a>
        <div id="collapseFive" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Interface:</h6>
            <a class="collapse-item" href="class-page"> Add class </a>
            <a class="collapse-item" href="subject-page"> Add subject: </a>
            <a class="collapse-item" href="class-subject-page"> Classes subject: </a>
            <a class="collapse-item" href="session-term-page"> Add Session/Term </a>
            <a class="collapse-item" href="principal-comment-page">Principal comment</a>
            <a class="collapse-item" href="class-teacher-comment-page">Class teacher comment</a>
          </div>
        </div>
      </li>

      <!-- FRONTEND -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse6" aria-expanded="true" aria-controls="collapseFive">
          <i class="fas fa-fw fa-cog"></i>
          <span>Frontend </span>
        </a>
        <div id="collapse6" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Interface:</h6>
            <a class="collapse-item" href="frontend.php"> settings </a>
          </div>
        </div>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

</ul>
<!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">