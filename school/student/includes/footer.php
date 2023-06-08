        </div>
        <!-- End of Main Content -->
            

<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
        <span>
            <?php
                $db = new Database();
                $db->query("SELECT * FROM frontend_tbl");
                if ($db->execute()) {
                if ($db->rowCount() > 0) {
                    $row = $db->single();
                    $footer = $row->footer;
                    echo $footer;
                
                    }else{

                    echo "No footer from the database";
                }
                } 
            ?>
        </span>
        </div>
    </div>
</footer>
<!-- End of Footer -->
     
 
 
 