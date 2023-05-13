<?php
include('../database/Database.php');
require('./includes/fpdf8/fpdf.php');

if (isset($_POST['single_view_btn'])) {
    $admNo = $_POST['admNo'];
    $session_id = $_POST['session_id'];
    $term_id = $_POST['term_id'];

    
    
        $db = new Database();
        $db->query(
            "SELECT * FROM result_tbl AS rt
            JOIN class_tbl ON class_tbl.class_id = rt.class_id 
            JOIN students_tbl ON students_tbl.admNo = rt.admNo
            JOIN session_tbl ON session_tbl.session_id = rt.session_id
            JOIN term_tbl ON term_tbl.term_id = rt.term_id
            WHERE rt.admNo = :admNo AND rt.session_id = :session_id AND rt.term_id = :term_id;
        ");
        $db->bind(':admNo', $admNo);
        $db->bind(':session_id', $session_id);
        $db->bind(':term_id', $term_id);
        
        if($db->execute())
        {
            if ($db->rowCount() > 0) {
                $result = $db->single();
                $class_id = $result->class_id;
                $class_name = $result->class_name;
                $sname = $result->sname;
                $lname = $result->lname;
                $oname = $result->oname;
                $gender = $result->gender;
                $admNo = $result->admNo;
                $religion = $result->religion;
                $passport = $result->passport;
                $session_name = $result->session_name;
                $term_name = $result->term_name;
    
                //Checking if term is 1 2 and 3 Term TODO
                if ($term_name == "FIRST TERM") {
                    class PDF extends FPDF
                    {
                        // Page footer
                        function Footer()
                        {
                            // Position at 1.5 cm from bottom
                            $this->SetY(-20);
                            // Arial italic 8
                            $this->SetFont('Arial', 'I', 8);
                            // Page number
                            $this->Cell(0, 5, 'Page ' . $this->PageNo() . '/{nb}', 0, 1, 'C');
                            $this->SetFont('Arial', 'I', 8);
                            // Page number
                            $this->Cell(0, 5, '(Date printed: ' . date('d/M/Y') . ')', 0, 1, 'R');
                        }
                    }
                    //Instanciation of inherited class
                    $pdf = new PDF('P', 'mm', 'A4');
                    $pdf->AliasNbPages();
                    $pdf->AddPage();
                    
                    //Getting class population
                    $db->query("SELECT * FROM students_tbl WHERE class_name = :class_name;");
                    $db->bind(':class_name', $class_name);
                    $db->execute();
                    $rst = $db->single();
                    $class_num = $db->rowCount(); // Getting total number of students in a class
                    
                        
                        //Add image logo
                        //$pdf ->Image('img/logoPdf.png', 7,7,33,34);
                        $pdf->SetFont('Arial', 'B', 25);
                        $pdf->Cell(190, 10, 'SUCCESS SCHOOLS SOKOTO', 0, 0, 'C');
                        $pdf->ln(7);
                        $pdf->SetFont(
                            'Times',
                            'I',
                            12
                        );
                        $pdf->Cell(180, 10, 'Nursery, Primary and Secondary', 0, 0, 'C');
                        $pdf->ln(7);
                        $pdf->SetFont(
                            'Times',
                            'B',
                            14
                        );
                        $pdf->Cell(180, 10, "Off Western Bypass Sokoto, Sokoto State.", 0, 0, 'C');
                        $pdf->ln(10);
                        $pdf->SetFont(
                            'Times',
                            'I',
                            12
                        );
                        $pdf->Cell(180, 10, "Tel: 08036505717, 08060860664", 0, 0, 'C');
                        $pdf->ln(5);
                        $pdf->SetFont(
                            'Times',
                            'I',
                            12
                        );
                        $pdf->Cell(180, 10, "Email: successschoolsnigeria@gmail.com", 0, 0, 'C');
                        $pdf->ln(20);
                        //Adding another image for the next record
                        $pdf->Image('../uploads/img/logoPdf.png', 7, 7, 33, 34);
                        $pdf->ln();
                        //Student Information goes here
                        $pdf->SetFont(
                            'Arial',
                            'B',
                            15
                        );
                        $pdf->Cell(190, 10, "$term_name REPORT SHEET $session_name SESSION ", 0, 1, 'C');
                        $pdf->ln(-3);
                        //Add Student image
                        //Controlling image
                        if (!empty($passport)) {
                            $pdf->Image(
                                $passport,
                                170,
                                30,
                                30,
                                30
                            );
                        }
                        //$pdf ->Image($passport, 170,30,30,30);
                        $pdf->ln(5);
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'ADMISSION NO.', 1, 0, 'L');
                        $pdf->Cell(
                            40,
                            5,
                            $admNo,
                            1,
                            0,
                            'L'
                        );
                        $pdf->Cell(40, 5, 'NAME', 1, 0, 'L');
                        $pdf->Cell(70, 5, $sname . " " . $lname . " " . $oname, 1, 1, 'L');
                        $pdf->Cell(40, 5, 'CLASS', 1, 0, 'L');
                        $pdf->Cell(40, 5, $class_name, 1, 0, 'L');
                        $pdf->Cell(40, 5, "CLASS SIZE", 1, 0, 'L');
                        $pdf->Cell(70, 5, $class_num, 1, 1, 'L');
                        $pdf->Cell(40, 5, 'GENDER', 1, 0, 'L');
                        $pdf->Cell(
                            40,
                            5,
                            $gender,
                            1,
                            0,
                            'L'
                        );
                        $pdf->Cell(40, 5, 'RELIGION', 1, 0, 'L');
                        $pdf->Cell(70, 5, $religion, 1, 1, 'L');
                        $pdf->ln(10);
                        //SUBJECTS  header
                        $pdf->SetFont(
                            'Times',
                            'B',
                            10
                        );
                        $pdf->Cell(75, 5, 'SUBJECTS', 1, 0, 'L');
                        $pdf->Cell(15, 5, 'CA', 1, 0, 'C');
                        $pdf->Cell(20, 5, 'EXAM', 1, 0, 'C');
                        $pdf->Cell(20, 5, 'TOTAL', 1, 0, 'C');
                        $pdf->Cell(30, 5, 'GRADE', 1, 0, 'C');
                        $pdf->Cell(30, 5, 'REMARKS', 1, 1, 'C');
                        //Second Row
                        $pdf->ln(0);
                        $pdf->SetFont('Times', 'BI', 10);
                        $pdf->Cell(75, 5, 'Maximum Mark Obtainable', 1, 0, 'L');
                        $pdf->Cell(15, 5, '40', 1, 0, 'C');
                        $pdf->Cell(20, 5, '60', 1, 0, 'C');
                        $pdf->Cell(20, 5, '100', 1, 0, 'C');
                        $pdf->Cell(30, 5, '', 1, 0, 'C');
                        $pdf->Cell(30, 5, '', 1, 1, 'C');
                        $pdf->ln(0);
                        //Fetching result
                        $db->query(
                            "SELECT subject_name, ca, exam, total, grade, remark FROM result_tbl AS rs 
                            JOIN subject_tbl ON subject_tbl.subject_id = rs.subject_id
                            WHERE rs.admNo = :admNo AND rs.session_id = :session_id AND rs.term_id = :term_id;
                            ");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        
                        if($db->execute())
                        {
                            if ($db->rowCount() > 0) 
                            {
                                $row = $db->resultset();
                                foreach($row as $results) 
                                {
                                    $subjects = $results->subject_name;
                                    //Row SUBJECTS
                                    $pdf->SetFont('Times', '', 10);
                                    $pdf->Cell(75, 5, $subjects, 1, 0, 'L');
        
                                    $ca = $results->ca;
                                    $exam = $results->exam;
                                    $total = $results->total;
                                    $grade = $results->grade;
                                    $remark = $results->remark;
        
                                    $pdf->Cell(15, 5, $ca, 1, 0, 'C');
                                    $pdf->Cell(20, 5, $exam, 1, 0, 'C');
                                    $pdf->Cell(20, 5, $total, 1, 0, 'C');
                                    $pdf->Cell(30, 5, $grade, 1, 0, 'C');
                                    $pdf->Cell(30, 5, $remark, 1, 1, 'C');
                                    $pdf->ln(0);
                                }
                            } 
                            else 
                            {
                                $error = "No Result found for this student";
                                $pdf->Cell(
                                    190,
                                    5,
                                    $error,
                                    1,
                                    0,
                                    'C'
                                );
                            }
                        }
                        else
                        {
                            die($db->getError());
                            exit();
                        }
                        //CREATE A SINGLE SPACE AFTER LISTS OF SUBJECTS
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(75, 5, "", 1, 0, 'C');
                        $pdf->Cell(15, 5, "", 1, 0, 'C');
                        $pdf->Cell(20, 5, "", 1, 0, 'C');
                        $pdf->Cell(20, 5, "", 1, 0, 'C');
                        $pdf->Cell(30, 5, "", 1, 0, 'C');
                        $pdf->Cell(30, 5, "", 1, 0, 'C');
                        $pdf->ln();
    
                        //Getting total 
                        $db->query("SELECT SUM(total) AS total_sum FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        $db->execute();
                        $sql_fetch = $db->single();
                        $total = $sql_fetch->total_sum;
                        //Getting Average
                        $db->query("SELECT AVG(total) AS average FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        $db->execute();
                        $sql_fetch = $db->single();
                        $average = $sql_fetch->average;
                        //Row MATHS
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(110, 5, 'TOTAL = ', 1, 0, 'R');
                        //$pdf ->Cell(15,5,'',1,0,'C');
                        //$pdf ->Cell(20,5,'',1,0,'C');
                        $pdf->Cell(20, 5, $total, 1, 0, 'C');
                        //TERM AVERAGE 
                        $pdf->Cell(60, 5, 'AVERAGE = ' .round(($average), 2, PHP_ROUND_HALF_UP), 1, 1, 'L');
                        $pdf->Cell(190, 5, '', 1, 1, 'C');
                        $pdf->Cell(190, 5, '', 1, 1, 'C');
                        $pdf->Cell(190, 5, 'GRADES:  A1[80-100] B2[75-79] B3[70-74]  C4[65-69]  C5[60-64]  C6[50-59] D7[45-49] E8[40-44]  F9[0-39]', 1, 1, 'C');
                        $pdf->ln(10);
    
                        //CLASS TEACHERS COMMENT HEAD
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(25, 5, 'BEHAVIOUR', 1, 0, 'C');
                        $pdf->Cell(13, 5, 'RATE', 1, 0, 'C');
                        $pdf->Cell(25, 5, 'BEHAVIOUR', 1, 0, 'C');
                        $pdf->Cell(13, 5, 'RATE', 1, 0, 'C');
                        $pdf->Cell(25, 5, 'BEHAVIOUR', 1, 0, 'C');
                        $pdf->Cell(13, 5, 'RATE', 1, 0, 'C');
                        $pdf->Cell(25, 5, 'BEHAVIOUR', 1, 0, 'C');
                        $pdf->Cell(13, 5, 'RATE', 1, 0, 'C');
                        $pdf->Cell(25, 5, 'BEHAVIOUR', 1, 0, 'C');
                        $pdf->Cell(13, 5, 'RATE', 1, 1, 'C');
    
                        //CLASS TEACHERS COMMENT BODY
                        $db->query("SELECT * FROM comments_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);

                        //PRINCIPAL AUTO COMMENT TODO
                        if($average < 50)
                        {
                            $p_c = "This is a below average result";
                        } else if($average >= 50 )
                        {
                            $p_c = "This is an average result, you can do more better";
                        } else if($average >= 60)
                        {
                            $p_c = "Good, you can do more better";
                        } else if($average > 60)
                        {
                            $p_c = "Execellent, Keep it up";
                        }
                        if($db->execute())
                        {
                            if ($db->rowCount() > 0) {
                                $row = $db->resultset();
                                foreach($row as $results)
                                {
                                    $attendance = $results->attendance;
                                    $honesty = $results->honesty;
                                    $neatness = $results->neatness;
                                    $obedience = $results->obedience;
                                    $punctuality = $results->punctuality;
                                    $tolerance = $results->tolerance;
                                    $creativity = $results->creativity;
                                    $dexterity = $results->dexterity;
                                    $fluency = $results->fluency;
                                    $handwriting = $results->handwriting;
                                    $teacher_comment = $results->teacher_comment;
                                    $principal_comment = $results->principal_comment;
        
                                    $pdf->SetFont('Times', '', 8);
                                    $pdf->Cell(25, 5, 'NEATNESS', 1, 0, 'C');
                                    $pdf->Cell(13, 5, $neatness, 1, 0, 'C');
                                    $pdf->Cell(25, 5, 'PUNCTUALITY', 1, 0, 'C');
                                    $pdf->Cell(13, 5, $punctuality, 1, 0, 'C');
                                    $pdf->Cell(25, 5, 'FLUENCY', 1, 0, 'C');
                                    $pdf->Cell(13, 5, $fluency, 1, 0, 'C');
                                    $pdf->Cell(25, 5, 'TOLERANCE', 1, 0, 'C');
                                    $pdf->Cell(13, 5, $tolerance, 1, 0, 'C');
                                    $pdf->Cell(25, 5, 'OBEDIENCE', 1, 0, 'C');
                                    $pdf->Cell(13, 5, $obedience, 1, 1, 'C');
        
                                    //CLASS TEACHERS COMMENT BODY
                                    $pdf->SetFont('Times', '', 8);
                                    $pdf->Cell(25, 5, 'ATTENDANCE', 1, 0, 'C');
                                    $pdf->Cell(13, 5, $attendance, 1, 0, 'C');
                                    $pdf->Cell(25, 5, 'HONESTY', 1, 0, 'C');
                                    $pdf->Cell(13, 5, $honesty, 1, 0, 'C');
                                    $pdf->Cell(25, 5, 'CREATIVITY', 1, 0, 'C');
                                    $pdf->Cell(13, 5, $creativity, 1, 0, 'C');
                                    $pdf->Cell(25, 5, 'HANDWRITING', 1, 0, 'C');
                                    $pdf->Cell(13, 5, $handwriting, 1, 0, 'C');
                                    $pdf->Cell(25, 5, 'DEXTERITY', 1, 0, 'C');
                                    $pdf->Cell(13, 5, $dexterity, 1, 1, 'C');
                                    $pdf->SetFont('Times', 'B', 8);
                                    $pdf->Cell(190, 5, 'KEY RATING:       A-EXCELLENT     B-VERY GOOD     C-SATISFACTORY      D-POOR      E-VERY POOR', 1, 1, 'C');
                                    $pdf->ln(10);
                                    
                                    $pdf->SetFont('Times', 'B', 9);
                                    $pdf ->Cell(53,5, "CLASS TEACHER'S NAME",1,0,'L');
                                    //Getting class teacher name
                                    $db->query(
                                        "SELECT * FROM class_tbl AS tn
                                        JOIN staff_tbl ON staff_tbl.staff_id = tn.instructor_id
                                        WHERE tn.class_id = :class_id;"
                                    );
                                    $db->bind(':class_id', $class_id);
                                    if($db->execute())
                                    {
                                        if($db->rowCount() > 0) {
                                            $result = $db->resultset();
                                            foreach($result as $teacher)
                                            {
                                                $pdf->SetFont('Times', 'B', 9);
                                                $pdf ->Cell(80,5, $teacher->fname." ".$teacher->sname." ".$teacher->oname,1,1,'L');
                                            }
                                        }
                                        else {
                                            $pdf->SetFont('Times', 'B', 9);
                                            //$pdf ->Cell(80,5, "Teacher's not found",1,1,'L');
                                            $pdf ->Cell(80,5, "Teacher not found",1,1,'L');
                                        }
                                    }else{
                                        die($db->getError());
                                        exit();
                                    }
                                    //CLASS TEACHER AND PRINCIPAL COMMENTS
                                    $pdf->SetFont('Times', 'B', 9);
                                    $pdf->Cell(53, 5, "CLASS TEACHER'S COMMENT", 1, 0, 'L');
                                    $pdf->SetFont('Times', '', 11);
                                    $pdf->Cell(80, 5, $teacher_comment, 1, 1, 'L');
                                    $pdf->SetFont('Times', 'B', 9);
                                    $pdf->Cell(53, 5, 'PRINCIPAL COMMENT', 1, 0, 'L');
                                    $pdf->SetFont('Times', '', 11);
                                    //$pdf->Cell(80, 5, $principal_comment, 1, 1, 'L');
                                    $pdf->Cell(80, 5, $p_c, 1, 1, 'L');
                                    $pdf->SetFont('Times', 'B', 9);
                                    $pdf->Cell(53, 5, 'NEXT TERM BEGIN', 1, 0, 'L');

                                    //Getting Next Term begins from the database
                                    $db->query("SELECT next_term FROM next_term_tbl ORDER BY next_term_id DESC;");
                                    if ($db->execute()) {
                                        if ($db->rowCount() > 0) {
                                            $nxt_term = $db->single();
                                            $pdf->SetFont('Times', '', 11);
                                            $pdf->Cell(80, 5, $nxt_term->next_term, 1, 1, 'L');
                                        } else {
                                            $error = "Resumption date not scheduled";
                                            $pdf->SetFont('Times', '', 11);
                                            $pdf->Cell(80, 5, $error, 1, 1, 'L');
                                        }
                                    } else {
                                        die("Query failed".$db->getError());
                                        exit();
                                        //$pdf->Cell(80, 5, $error, 1, 1, 'L');
                                    }
                                }
                            } else {
                                $error = "No Teacher's comment";
                                $pdf->Cell(190, 5, $error, 1, 0, 'C');
                            }
                        }
                        else 
                        {
                            die($db->getError());
                            exit();
                        }
                        //SCHOOL STAMP
                        //$pdf ->Image('img/signature.jpeg', 145,245,55,28);
                        $pdf->ln(30);
                    
                    //Outputting the pdf file
                    $pdf->SetTitle($admNo.' (' .$session_name. ' - ' .$term_name . ')');
                    //making it downloadable
                    //$pdf->Output($class_name.' ('.$session_name.' - '.$term_name.')', 'D');
                    $pdf->Output();
                }
                else
                {
                    echo "Result not available for this term";
                }
            } 
            else 
            {
                echo "No result found";
            }
        }
        else
        {
            die($db->getError());
            exit();
        }
        /* if($term_name == "FIRST TERM")
        {
            $sql_run = mysqli_query($con, "SELECT * FROM result_tbl WHERE admNo = '$admNo' AND session_name='$session_name' AND term_name='$term_name';");
            if(!$sql_run)
            {
                die("Query failed ".mysqli_error($con));
            }
            else
            {
                if(mysqli_num_rows($sql_run) > 0)
                {
                    require_once('includes/fpdf8/fpdf.php');
                    class PDF extends FPDF
                    {
                        // Page footer
                        function Footer()
                        {
                            // Position at 1.5 cm from bottom
                            $this->SetY(-20);
                            // Arial italic 8
                            $this->SetFont('Arial','I',8);
                            // Page number
                            $this->Cell(0,5,'Page '.$this->PageNo().'/{nb}',0,1,'C');
                            $this->SetFont('Arial','I',8);
                            // Page number
                            $this->Cell(0,5,'(Date printed: '.date('d/M/Y').')',0,1,'R');
                        }
                        
                    }
                    //Instanciation of inherited class
                    $pdf = new PDF('P', 'mm', 'A4' );
                    $pdf->AliasNbPages();
                    $pdf->AddPage();
                    //fetching the results
                    $class_query = mysqli_fetch_assoc($sql_run);
                    $class_id = $class_query['class_id'];
                    $admNo = $class_query['admNo'];
                    $class_q = mysqli_query($con, "SELECT * FROM class_tbl WHERE class_id = '$class_id';");
                    $class_num = mysqli_num_rows($class_q);
                    $class_fetch = mysqli_fetch_assoc($class_q);
                    $class_name = $class_fetch['class_name'];
                    $teacher_id = $class_fetch['instructor_id'];
                    //Getting class population
                    $class_population = mysqli_query($con, "SELECT * FROM students_tbl WHERE class_name='$class_name';");
                    $class_num = mysqli_num_rows($class_population);
                    $get_student = mysqli_query($con, "SELECT * FROM students_tbl WHERE class_name='$class_name' AND admNo = '$admNo' LIMIT 1;");
                    if(!$get_student)
                    {
                        die("Query failed ".mysqli_error($con));
                    }
                    else
                    {
                        //$class_num = mysqli_num_rows($get_student);
                        if($get_student)
                        {
                            foreach($get_student as $row)
                            {
                                $class_name = $row['class_name'];
                                $sname = $row['sname'];
                                $lname = $row['lname'];
                                $oname = $row['oname'];
                                $gender = $row['gender'];
                                $admNo = $row['admNo'];
                                $religion = $row['religion'];
                                $passport = $row['passport'];
                                //Add image logo
                                //$pdf ->Image('img/logoPdf.png', 7,7,33,34);
                                $pdf ->SetFont('Arial', 'B', 25);
                                $pdf ->Cell(190,10, 'SUCCESS SCHOOLS SOKOTO', 0, 0,'C');
                                $pdf ->ln(7);
                                $pdf ->SetFont('Times','I', 12);
                                $pdf ->Cell(180,10,'Nursery, Primary and Secondary',0,0,'C');
                                $pdf ->ln(7);
                                $pdf ->SetFont('Times','B', 14);
                                $pdf ->Cell(180,10,"Off Western Bypass Sokoto, Sokoto State.",0,0,'C');
                                $pdf ->ln(10);
                                $pdf ->SetFont('Times','I', 12);
                                $pdf ->Cell(180,10,"Tel: 08036505717, 08060860664",0,0,'C');
                                $pdf ->ln(5);
                                $pdf ->SetFont('Times','I', 12);
                                $pdf ->Cell(180,10,"Email: successschoolsnigeria@gmail.com",0,0,'C');
                                $pdf ->ln(20);
                                //Adding another image for the next record
                                $pdf ->Image('img/logoPdf.png', 7,7,33,34);
                                $pdf ->ln();
                                //Student Information goes here
                                $pdf ->SetFont('Arial','B', 15);
                                $pdf ->Cell(190,10,"$term_name REPORT SHEET $session_name SESSION ",0,1,'C');
                                $pdf ->ln(-3);
                                //Add Student image
                                //Controlling image
                                if(!empty($passport)){
                                    $pdf ->Image($passport, 170,30,30,30);    
                                }
                                //$pdf ->Image($passport, 170,30,30,30);
                                $pdf ->ln(5);
                                $pdf -> SetFont('Times','B', 10);
                                $pdf -> Cell(40,5,'ADMISSION NO.',1,0,'L');
                                $pdf -> Cell(40,5, $admNo, 1,0,'L');
                                $pdf -> Cell(40,5,'NAME',1,0,'L');
                                $pdf -> Cell(70,5, $sname." ".$lname." ".$oname, 1,1,'L');
                                $pdf -> Cell(40,5,'CLASS',1,0,'L');
                                $pdf -> Cell(40,5, $class_name, 1,0,'L');
                                $pdf -> Cell(40,5, "CLASS SIZE",1,0,'L');
                                $pdf -> Cell(70,5, $class_num, 1,1,'L');
                                $pdf -> Cell(40,5,'GENDER',1,0,'L');
                                $pdf -> Cell(40,5, $gender, 1,0,'L');
                                $pdf -> Cell(40,5,'RELIGION',1,0,'L');
                                $pdf -> Cell(70,5, $religion, 1,1,'L');
                                $pdf -> ln(10);
                                //SUBJECTS  header
                                $pdf ->SetFont('Times','B', 10);
                                $pdf ->Cell(75,5,'SUBJECTS',1,0,'L');
                                $pdf ->Cell(15,5,'CA',1,0,'C');
                                $pdf ->Cell(20,5,'EXAM',1,0,'C');
                                $pdf ->Cell(20,5,'TOTAL',1,0,'C');
                                $pdf ->Cell(30,5,'GRADE',1,0,'C');
                                $pdf ->Cell(30,5,'REMARKS',1,1,'C');
                                //Second Row
                                $pdf ->ln(0);
                                $pdf ->SetFont('Times','BI', 10);
                                $pdf ->Cell(75,5,'Maximum Mark Obtainable',1,0,'L');
                                $pdf ->Cell(15,5,'40',1,0,'C');
                                $pdf ->Cell(20,5,'60',1,0,'C');
                                $pdf ->Cell(20,5,'100',1,0,'C');
                                $pdf ->Cell(30,5,'',1,0,'C');
                                $pdf ->Cell(30,5,'',1,1,'C');
                                $pdf ->ln(0);
                                //Fetching result
                                $res = mysqli_query($con, "SELECT * FROM result_tbl WHERE admNo='$admNo' AND session_name='$session_name' AND term_name='$term_name';");
                                if(mysqli_num_rows($res) > 0)
                                {
                                    $subject_count = mysqli_num_rows($res);
                                    while($results = mysqli_fetch_assoc($res))
                                    {
                                        $subject = $results['subject_id'];
                                        //Converting subject id to subject name
                                        $subject_query = mysqli_query($con, "SELECT * FROM subject_tbl WHERE subject_id = '$subject';");
                                        if($subject_query){
                                            foreach($subject_query as $subject_fetch)
                                            {
                                                $subject = $subject_fetch['subject_name'];
            
                                                //Row ENGLISH LANGUAGE
                                                $pdf ->SetFont('Times','', 10);
                                                $pdf ->Cell(75,5, $subject, 1,0,'L');
                                            }
                                        }
                                        $ca = $results['ca'];
                                        $exam = $results['exam'];
                                        $total = $results['total'];
                                        $grade = $results['grade'];
                                        $remark = $results['remark'];
                                        $pdf ->Cell(15,5, $ca,1,0,'C');
                                        $pdf ->Cell(20,5, $exam,1,0,'C');
                                        $pdf ->Cell(20,5, $total,1,0,'C');
                                        $pdf ->Cell(30,5, $grade,1,0,'C');
                                        $pdf ->Cell(30,5, $remark,1,1,'C');
                                        $pdf ->ln(0);                            
                                    }    
                                    }
                                    else
                                    {
                                        $error = " No Result uploaded for this student";
                                        $pdf ->Cell(190,5, $error,1,0,'C');                        
                                    }
                                    //CREATE A SINGLE SPACE AFTER LISTS OF SUBJECTS
                                    $pdf ->SetFont('Times','', 10);
                                    $pdf ->Cell(75,5, "",1,0,'C');
                                    $pdf ->Cell(15,5, "",1,0,'C');
                                    $pdf ->Cell(20,5, "",1,0,'C');
                                    $pdf ->Cell(20,5, "",1,0,'C');
                                    $pdf ->Cell(30,5, "",1,0,'C');
                                    $pdf ->Cell(30,5, "",1,0,'C');
                                    $pdf ->ln();

                                    //Getting total and average
                                    $sql_sum = mysqli_query($con, " SELECT SUM(total) AS total_sum FROM result_tbl WHERE admNo = '$admNo' AND session_name='$session_name' AND term_name='$term_name';");
                                    $sql_fetch = mysqli_fetch_assoc($sql_sum);
                                    $total = $sql_fetch['total_sum'];
                                    //Row MATHS
                                    $pdf ->SetFont('Times','B', 10);
                                    $pdf ->Cell(110,5,'TOTAL = ',1,0,'R');
                                    //$pdf ->Cell(15,5,'',1,0,'C');
                                    //$pdf ->Cell(20,5,'',1,0,'C');
                                    $pdf ->Cell(20,5,$total, 1,0,'C');
                                    //TERM AVERAGE
                                    $pdf ->Cell(60,5,'AVERAGE = '.round(($total/$subject_count), 2, PHP_ROUND_HALF_UP),1,1,'L');
                                    $pdf ->Cell(190,5,'',1,1,'C');
                                    $pdf ->Cell(190,5,'',1,1,'C');
                                    $pdf ->Cell(190,5,'GRADES:  A1[80-100] B2[75-79] B3[70-74]  C4[65-69]  C5[60-64]  C6[50-59] D7[45-49] E8[40-44]  F9[0-39]',1,1,'C');
                                    $pdf ->ln(10);
                                
                                    //CLASS TEACHERS COMMENT HEAD
                                    $pdf ->SetFont('Times','B', 10);
                                    $pdf ->Cell(25,5,'BEHAVIOUR',1,0,'C');
                                    $pdf ->Cell(13,5,'RATE',1,0,'C');
                                    $pdf ->Cell(25,5,'BEHAVIOUR',1,0,'C');
                                    $pdf ->Cell(13,5,'RATE',1,0,'C');
                                    $pdf ->Cell(25,5,'BEHAVIOUR',1,0,'C');
                                    $pdf ->Cell(13,5,'RATE',1,0,'C');
                                    $pdf ->Cell(25,5,'BEHAVIOUR',1,0,'C');
                                    $pdf ->Cell(13,5,'RATE',1,0,'C');
                                    $pdf ->Cell(25,5,'BEHAVIOUR',1,0,'C');
                                    $pdf ->Cell(13,5,'RATE',1,1,'C');
                                    
                                    //CLASS TEACHERS COMMENT BODY
                                    $com = mysqli_query($con, "SELECT * FROM comments_tbl WHERE admNo='$admNo' AND session_name='$session_name' AND term_name='$term_name';");
                                    if(mysqli_num_rows($com) > 0)
                                    {
                                        while($results = mysqli_fetch_assoc($com))
                                        {
                                            $attendance = $results['attendance'];
                                            $honesty = $results['honesty'];
                                            $neatness = $results['neatness'];
                                            $obedience = $results['obedience'];
                                            $punctuality = $results['punctuality'];
                                            $tolerance = $results['tolerance'];
                                            $creativity = $results['creativity'];
                                            $dexterity = $results['dexterity'];
                                            $fluency = $results['fluency'];
                                            $handwriting = $results['handwriting'];
                                            $teacher_comment = $results['teacher_comment'];
                                            $principal_comment = $results['principal_comment'];
                                            $pdf ->SetFont('Times','', 8);
                                            $pdf ->Cell(25,5,'NEATNESS',1,0,'C');
                                            $pdf ->Cell(13,5, $neatness,1,0,'C');
                                            $pdf ->Cell(25,5,'PUNCTUALITY',1,0,'C');
                                            $pdf ->Cell(13,5,$punctuality,1,0,'C');
                                            $pdf ->Cell(25,5,'FLUENCY',1,0,'C');
                                            $pdf ->Cell(13,5, $fluency, 1,0,'C');
                                            $pdf ->Cell(25,5,'TOLERANCE',1,0,'C');
                                            $pdf ->Cell(13,5, $tolerance,1,0,'C');
                                            $pdf ->Cell(25,5,'OBEDIENCE',1,0,'C');
                                            $pdf ->Cell(13,5, $obedience,1,1,'C');
        
                                            //CLASS TEACHERS COMMENT BODY
                                            $pdf ->SetFont('Times','', 8);
                                            $pdf ->Cell(25,5,'ATTENDANCE',1,0,'C');
                                            $pdf ->Cell(13,5,$attendance,1,0,'C');
                                            $pdf ->Cell(25,5,'HONESTY',1,0,'C');
                                            $pdf ->Cell(13,5,$honesty,1,0,'C');
                                            $pdf ->Cell(25,5,'CREATIVITY',1,0,'C');
                                            $pdf ->Cell(13,5,$creativity,1,0,'C');
                                            $pdf ->Cell(25,5,'HANDWRITING',1,0,'C');
                                            $pdf ->Cell(13,5, $handwriting,1,0,'C');
                                            $pdf ->Cell(25,5,'DEXTERITY',1,0,'C');
                                            $pdf ->Cell(13,5, $dexterity,1,1,'C');
                                            $pdf ->SetFont('Times','B', 8);
                                            $pdf ->Cell(190,5,'KEY RATING:       A-EXCELLENT     B-VERY GOOD     C-SATISFACTORY      D-POOR      E-VERY POOR',1,1,'C');
                                            $pdf -> ln(10);
                                            //CLASS TEACHER AND PRINCIPAL COMMENTS
                                            $pdf ->SetFont('Times','B', 9);
                                            $teacher_query = mysqli_query($con, "SELECT * FROM staff_tbl WHERE staff_id='$teacher_id';");
                                            $teacher_fetch = mysqli_fetch_assoc($teacher_query);
                                            $t_fname = $teacher_fetch['fname'];
                                            $t_sname = $teacher_fetch['sname'];
                                            $t_oname = $teacher_fetch['oname'];
                                            $pdf ->Cell(53,5, "CLASS TEACHER'S NAME",1,0,'L');
                                            $pdf ->Cell(80,5, $t_fname." ".$t_sname." ".$t_oname,1,1,'L');
                                            $pdf ->Cell(53,5,"CLASS TEACHER'S COMMENT",1,0,'L');
                                            $pdf ->Cell(80,5, $teacher_comment,1,1,'L');
                                            $pdf ->Cell(53,5,'PRINCIPAL COMMENT',1,0,'L');
                                            $pdf ->Cell(80,5, $principal_comment,1,1,'L');
                                            $pdf ->Cell(53,5,'NEXT TERM BEGIN',1,0,'L');
                                            //Getting Next Term begins from the database
                                            $nxt_term_query = mysqli_query($con, "SELECT * FROM next_term_tbl;");
                                            if($nxt_term_query)
                                            {
                                                if(mysqli_num_rows($nxt_term_query) > 0)
                                                {
                                                    foreach($nxt_term_query as $next_term)
                                                    {
                                                        $next_term = ['next_term'];
                                                        $pdf ->Cell(80,5, $next_term,1,1,'L');
                                                    }
                                                }else{
                                                    $error = "Not Schedule";
                                                    $pdf ->Cell(80,5, $error,1,1,'L');
                                                }
                                            }
                                            else
                                            {
                                                $error = die("Query failed ".mysqli_error($con));
                                                $pdf ->Cell(80,5, $error,1,1,'L');    
                                            }
                                        }
                                    }
                                    else
                                    {
                                            $error = " No Teacher/Principal comments";
                                            $pdf ->Cell(190,5, $error,1,0,'C');                        
                                        }
                                            //SCHOOL STAMP
                                            //$pdf ->Image('img/signature.jpeg', 145,245,55,28);
                                            $pdf ->ln(30); 
                            }                       
                                //Outputting the pdf file
                                $pdf ->SetTitle($admNo.' ('.$session_name.' - '.$term_name.')');
                                //making it downloadable
                                //$pdf->Output($class_name.' ('.$session_name.' - '.$term_name.')', 'D');
                                $pdf->Output();
                                
                        }  
                    }
                }
                else
                {
                    die("No Result found for this class session/term ".mysqli_error($con));
                }

            }     
        } */

        /* if($term_name == "SECOND TERM")
        {
            $sql_run = mysqli_query($con, "SELECT * FROM result_tbl WHERE admNo = '$admNo' AND session_name='$session_name' AND term_name='$term_name' LIMIT 1;");
            if(!$sql_run)
            {
                die("Query failed ".mysqli_error($con));
            }
            else
            {
                if(mysqli_num_rows($sql_run) > 0)
                {
                    require_once('includes/fpdf8/fpdf.php');
                    class PDF extends FPDF
                    {
                        // Page footer
                        function Footer()
                        {
                            // Position at 1.5 cm from bottom
                            $this->SetY(-20);
                            // Arial italic 8
                            $this->SetFont('Arial','I',8);
                            // Page number
                            $this->Cell(0,5,'Page '.$this->PageNo().'/{nb}',0,1,'C');
                            $this->SetFont('Arial','I',8);
                            // Page number
                            $this->Cell(0,5,'(Date printed: '.date('d/M/Y').')',0,1,'R');
                        }
                    }
                    //Instanciation of inherited class
                    $pdf = new PDF('P', 'mm', 'A4' );
                    $pdf->AliasNbPages();
                    $pdf->AddPage();
                    
                    //fetching the results
                    $class_query = mysqli_fetch_assoc($sql_run);
                    $class_id = $class_query['class_id'];
                    $class_q = mysqli_query($con, "SELECT * FROM class_tbl WHERE class_id = '$class_id';");
                    $class_num = mysqli_num_rows($class_q);
                    $class_fetch = mysqli_fetch_assoc($class_q);
                    $class_name = $class_fetch['class_name'];
                    $teacher_id = $class_fetch['instructor_id'];
                    //Getting class population
                    $class_population = mysqli_query($con, "SELECT * FROM students_tbl WHERE class_name='$class_name';");
                    $class_num = mysqli_num_rows($class_population);
                    $get_student = mysqli_query($con, "SELECT * FROM students_tbl WHERE class_name='$class_name' LIMIT 1;");
                    if(!$get_student)
                    {
                        die("Query failed ".mysqli_error($con));
                    }
                    else
                    {
                        //$class_num = mysqli_num_rows($get_student);
                        if($get_student)
                        {
                            foreach($get_student as $row)
                            {
                                $class_name = $row['class_name'];
                                $sname = $row['sname'];
                                $lname = $row['lname'];
                                $oname = $row['oname'];
                                $gender = $row['gender'];
                                $admNo = $row['admNo'];
                                $religion = $row['religion'];
                                $passport = $row['passport'];
                                //Add image logo
                                $pdf ->Image('img/logoPdf.png', 7,7,33,34);
                                $pdf ->SetFont('Arial', 'B', 25);
                                $pdf ->Cell(190,10, 'SUCCESS SCHOOLS SOKOTO', 0, 0,'C');
                                $pdf ->ln(7);
                                $pdf ->SetFont('Times','I', 12);
                                $pdf ->Cell(180,10,'Nursery, Primary and Secondary',0,0,'C');
                                $pdf ->ln(7);
                                $pdf ->SetFont('Times','B', 14);
                                $pdf ->Cell(180,10,"Off Western Bypass Sokoto, Sokoto State.",0,0,'C');
                                $pdf ->ln(10);
                                $pdf ->SetFont('Times','I', 12);
                                $pdf ->Cell(180,10,"Tel: 08036505717, 08060860664",0,0,'C');
                                $pdf ->ln(5);
                                $pdf ->SetFont('Times','I', 12);
                                $pdf ->Cell(180,10,"Email: successschoolsnigeria@gmail.com",0,0,'C');
                                $pdf ->ln(20);
                                //Adding another image for the next record
                                $pdf ->Image('img/logoPdf.png', 7,7,33,34);
                                //Student Information goes here
                                $pdf ->SetFont('Arial','B', 15);
                                $pdf ->Cell(190,10,"$term_name REPORT SHEET $session_name SESSION ",0,1,'C');
                                $pdf ->ln(-3);
                                //Add Student image
                                //Controlling image
                                if(!empty($passport)){
                                    $pdf ->Image($passport, 170,30,30,30);    
                                }
                                //$pdf ->Image($passport, 170,30,30,30);
                                $pdf ->ln(5);
                                $pdf -> SetFont('Times','B', 10);
                                $pdf -> Cell(40,5,'ADMISSION NO.',1,0,'L');
                                $pdf -> Cell(40,5, $admNo, 1,0,'L');
                                $pdf -> Cell(40,5,'NAME',1,0,'L');
                                $pdf -> Cell(70,5, $sname." ".$lname." ".$oname, 1,1,'L');
                                $pdf -> Cell(40,5,'CLASS',1,0,'L');
                                $pdf -> Cell(40,5, $class_name, 1,0,'L');
                                $pdf -> Cell(40,5, "CLASS SIZE",1,0,'L');
                                $pdf -> Cell(70,5, $class_num, 1,1,'L');
                                $pdf -> Cell(40,5,'GENDER',1,0,'L');
                                $pdf -> Cell(40,5, $gender, 1,0,'L');
                                $pdf -> Cell(40,5,'RELIGION',1,0,'L');
                                $pdf -> Cell(70,5, $religion, 1,1,'L');
                                $pdf -> ln(10);
                                //SUBJECTS  header
                                $pdf ->SetFont('Times','B', 10);
                                $pdf ->Cell(75,5,'SUBJECTS',1,0,'L');
                                $pdf ->Cell(10,5,'CA',1,0,'C');
                                $pdf ->Cell(15,5,'EXAM',1,0,'C');
                                $pdf ->Cell(15,5,'TOTAL',1,0,'C');
                                $pdf ->Cell(20,5,'1st Term',1,0,'C');
                                $pdf ->Cell(25,5,'GRADE',1,0,'C');
                                $pdf ->Cell(30,5,'REMARKS',1,1,'C');
                                //Second Row
                                $pdf ->ln(0);
                                $pdf ->SetFont('Times','BI', 10);
                                $pdf ->Cell(75,5,'Maximum Mark Obtainable',1,0,'L');
                                $pdf ->Cell(10,5,'40',1,0,'C');
                                $pdf ->Cell(15,5,'60',1,0,'C');
                                $pdf ->Cell(15,5,'100',1,0,'C');
                                $pdf ->Cell(20,5,'100',1,0,'C');
                                $pdf ->Cell(25,5,'',1,0,'C');
                                $pdf ->Cell(30,5,'',1,1,'C');
                                $pdf ->ln(0);
                                //Fetching result
                                $res = mysqli_query($con, "SELECT * FROM result_tbl WHERE admNo='$admNo' AND session_name='$session_name' AND term_name='$term_name';");
                                if(mysqli_num_rows($res) > 0)
                                {
                                    $subject_count = mysqli_num_rows($res);
                                    while($results = mysqli_fetch_assoc($res))
                                    {
                                        $subject = $results['subject_id'];
                                        //Converting subject id to subject name
                                        $subject_query = mysqli_query($con, "SELECT * FROM subject_tbl WHERE subject_id = '$subject';");
                                        if($subject_query){
                                            foreach($subject_query as $subject_fetch)
                                            {
                                                $subject = $subject_fetch['subject_name'];
                                                $subject_id = $subject_fetch['subject_id'];
            
                                                //Row ENGLISH LANGUAGE
                                                $pdf ->SetFont('Times','', 10);
                                                $pdf ->Cell(75,5, $subject, 1,0,'L');
                                            }
                                        }
                                        $ca = $results['ca'];
                                        $exam = $results['exam'];
                                        $total = $results['total'];
                                        $grade = $results['grade'];
                                        $remark = $results['remark'];
                                        $pdf ->Cell(10,5, $ca,1,0,'C');
                                        $pdf ->Cell(15,5, $exam,1,0,'C');
                                        $pdf ->Cell(15,5, $total,1,0,'C');
                                        //Getting the overall total of each subject and adding to first term column
                                        $first_trm_query = mysqli_query($con, "SELECT * FROM result_tbl WHERE admNo='$admNo' AND session_name='$session_name' AND term_name='FIRST TERM' AND subject_id = '$subject_id';");
                                        $first_trm_num = mysqli_num_rows($first_trm_query);
                                        if($first_trm_num > 0)
                                        {
                                            while($firt_trm_fetch = mysqli_fetch_assoc($first_trm_query))
                                            {
                                                $first_term = $firt_trm_fetch['total'];
                                                $pdf ->Cell(20,5, $first_term,1,0,'C');
                                            }    
                                        }else{
                                            $error = "0";
                                            $pdf ->Cell(20,5, $error,1,0,'C');
                                        }
                                        $pdf ->Cell(25,5, $grade,1,0,'C');
                                        $pdf ->Cell(30,5, $remark,1,1,'C');
                                        $pdf ->ln(0);                            
                                    }    
                                    }else{
                                        $error = "No Result uploaded";
                                        $pdf ->Cell(190,5, $error,1,0,'C');                        
                                    }
                                        //CREATE A SINGLE SPACE AFTER LISTS OF SUBJECTS
                                        $pdf ->SetFont('Times','', 10);
                                        $pdf ->Cell(75,5, "",1,0,'C');
                                        $pdf ->Cell(10,5, "",1,0,'C');
                                        $pdf ->Cell(15,5, "",1,0,'C');
                                        $pdf ->Cell(15,5, "",1,0,'C');
                                        $pdf ->Cell(20,5, "",1,0,'C');
                                        $pdf ->Cell(25,5, "",1,0,'C');
                                        $pdf ->Cell(30,5, "",1,1,'C');
                                        $pdf ->ln(0);
                                    
                                    //Getting total and average
                                    $sql_sum = mysqli_query($con, " SELECT SUM(total) AS total_sum FROM result_tbl WHERE admNo = '$admNo' AND session_name='$session_name' AND term_name='$term_name';");
                                    if(mysqli_num_rows($sql_sum) > 0)
                                    {
                                        $sql_fetch = mysqli_fetch_assoc($sql_sum);
                                        $total = $sql_fetch['total_sum'];
                                        //Row MATHS
                                        $pdf ->SetFont('Times','B', 10);
                                        $pdf ->Cell(100,5, "TOTAL ",1,0,'R');
                                        $pdf ->Cell(15,5, $total, 1,0,'C');
                                    }
                                    else{
                                        $error = "0";
                                        $pdf ->Cell(15,5, $error, 1,0,'C');
                                    }
                                    
                                    //Getting Firt term total
                                    $first_term = "FIRST TERM";
                                    $first_term_sum = mysqli_query($con, "SELECT SUM(total) AS first_total_sum FROM result_tbl WHERE admNo = '$admNo' AND session_name='$session_name' AND term_name='$first_term';");
                                    if(mysqli_num_rows($first_term_sum) > 0)
                                    {
                                        $first_fetch = mysqli_fetch_assoc($first_term_sum);
                                        $first_term_total = $first_fetch['first_total_sum'];
                                        
                                        $pdf ->Cell(20,5, $first_term_total, 1,0,'C');
                                    }
                                    else{
                                        $error = "0";
                                        $pdf ->Cell(20,5, $error, 1,0,'C');
                                    }
                                    //TERM AVERAGE
                                    $pdf ->Cell(55,5,'AVERAGE = '.round(($total/$subject_count), 2, PHP_ROUND_HALF_UP),1,1,'L');
                                    $pdf ->Cell(190,5,'',1,1,'C');
                                    $pdf ->Cell(190,5,'',1,1,'C');
                                    $pdf ->Cell(190,5,'GRADES:  A1[80-100] B2[75-79] B3[70-74]  C4[65-69]  C5[60-64]  C6[50-59] D7[45-49] E8[40-44]  F9[0-39]',1,1,'C');
                                    $pdf ->ln(20);
                                
                                    //CLASS TEACHERS COMMENT HEAD
                                    $pdf ->SetFont('Times','B', 10);
                                    $pdf ->Cell(25,5,'BEHAVIOUR',1,0,'C');
                                    $pdf ->Cell(13,5,'RATE',1,0,'C');
                                    $pdf ->Cell(25,5,'BEHAVIOUR',1,0,'C');
                                    $pdf ->Cell(13,5,'RATE',1,0,'C');
                                    $pdf ->Cell(25,5,'BEHAVIOUR',1,0,'C');
                                    $pdf ->Cell(13,5,'RATE',1,0,'C');
                                    $pdf ->Cell(25,5,'BEHAVIOUR',1,0,'C');
                                    $pdf ->Cell(13,5,'RATE',1,0,'C');
                                    $pdf ->Cell(25,5,'BEHAVIOUR',1,0,'C');
                                    $pdf ->Cell(13,5,'RATE',1,1,'C');
                                    
                                    //CLASS TEACHERS COMMENT BODY
                                    $com = mysqli_query($con, "SELECT * FROM comments_tbl WHERE admNo='$admNo' AND session_name='$session_name' AND term_name='$term_name';");
                                    if(mysqli_num_rows($com) > 0)
                                    {
                                        while($results = mysqli_fetch_assoc($com))
                                        {
                                            $attendance = $results['attendance'];
                                            $honesty = $results['honesty'];
                                            $neatness = $results['neatness'];
                                            $obedience = $results['obedience'];
                                            $punctuality = $results['punctuality'];
                                            $tolerance = $results['tolerance'];
                                            $creativity = $results['creativity'];
                                            $dexterity = $results['dexterity'];
                                            $fluency = $results['fluency'];
                                            $handwriting = $results['handwriting'];
                                            $teacher_comment = $results['teacher_comment'];
                                            $principal_comment = $results['principal_comment'];
                                            $pdf ->SetFont('Times','', 8);
                                            $pdf ->Cell(25,5,'NEATNESS',1,0,'C');
                                            $pdf ->Cell(13,5, $neatness,1,0,'C');
                                            $pdf ->Cell(25,5,'PUNCTUALITY',1,0,'C');
                                            $pdf ->Cell(13,5,$punctuality,1,0,'C');
                                            $pdf ->Cell(25,5,'FLUENCY',1,0,'C');
                                            $pdf ->Cell(13,5, $fluency, 1,0,'C');
                                            $pdf ->Cell(25,5,'TOLERANCE',1,0,'C');
                                            $pdf ->Cell(13,5, $tolerance,1,0,'C');
                                            $pdf ->Cell(25,5,'OBEDIENCE',1,0,'C');
                                            $pdf ->Cell(13,5, $obedience,1,1,'C');
        
                                            //CLASS TEACHERS COMMENT BODY
                                            $pdf ->SetFont('Times','', 8);
                                            $pdf ->Cell(25,5,'ATTENDANCE',1,0,'C');
                                            $pdf ->Cell(13,5,$attendance,1,0,'C');
                                            $pdf ->Cell(25,5,'HONESTY',1,0,'C');
                                            $pdf ->Cell(13,5,$honesty,1,0,'C');
                                            $pdf ->Cell(25,5,'CREATIVITY',1,0,'C');
                                            $pdf ->Cell(13,5,$creativity,1,0,'C');
                                            $pdf ->Cell(25,5,'HANDWRITING',1,0,'C');
                                            $pdf ->Cell(13,5, $handwriting,1,0,'C');
                                            $pdf ->Cell(25,5,'DEXTERITY',1,0,'C');
                                            $pdf ->Cell(13,5, $dexterity,1,1,'C');
                                            $pdf ->SetFont('Times','B', 8);
                                            $pdf ->Cell(190,5,'KEY RATING:       A-EXCELLENT     B-VERY GOOD     C-SATISFACTORY      D-POOR      E-VERY POOR',1,1,'C');
                                            $pdf -> ln(10);
                                            //CLASS TEACHER AND PRINCIPAL COMMENTS
                                            $pdf ->SetFont('Times','B', 9);
                                            $teacher_query = mysqli_query($con, "SELECT * FROM staff_tbl WHERE staff_id='$teacher_id';");
                                            $teacher_fetch = mysqli_fetch_assoc($teacher_query);
                                            $t_fname = $teacher_fetch['fname'];
                                            $t_sname = $teacher_fetch['sname'];
                                            $t_oname = $teacher_fetch['oname'];
                                            $pdf ->Cell(53,5, "CLASS TEACHER'S NAME",1,0,'L');
                                            $pdf ->Cell(80,5, $t_fname." ".$t_sname." ".$t_oname,1,1,'L');
                                            $pdf ->Cell(53,5,"CLASS TEACHER'S COMMENT",1,0,'L');
                                            $pdf ->Cell(80,5, $teacher_comment,1,1,'L');
                                            $pdf ->Cell(53,5,'PRINCIPAL COMMENT',1,0,'L');
                                            $pdf ->Cell(80,5, $principal_comment,1,1,'L');
                                            $pdf ->Cell(53,5,'NEXT TERM BEGIN',1,0,'L');
                                            //Getting Next Term begins from the database
                                            $nxt_term_query = mysqli_query($con, "SELECT * FROM next_term_tbl;");
                                            if($nxt_term_query)
                                            {
                                                if(mysqli_num_rows($nxt_term_query) > 0)
                                                {
                                                    foreach($nxt_term_query as $next_term)
                                                    {
                                                        $next_term = ['next_term'];
                                                        $pdf ->Cell(80,5, $next_term,1,1,'L');
                                                    }
                                                }else{
                                                    $error = "Not Schedule";
                                                    $pdf ->Cell(80,5, $error,1,1,'L');
                                                }
                                            }
                                            else
                                            {
                                                $error = die("Query failed ".mysqli_error($con));
                                                $pdf ->Cell(80,5, $error,1,1,'L');    
                                            }
                                        }
                                    }
                                    else{
                                            $error = " No Teacher/Principal comments";
                                            $pdf ->Cell(190,5, $error,1,0,'C');                        
                                        }
                                            //SCHOOL STAMP
                                            //$pdf ->Image('img/signature.jpeg', 145,230,55,28);
                                            $pdf ->ln(30); 
                            }                       
                                //Outputting the pdf file
                                $pdf ->SetTitle($admNo.' ('.$session_name.' - '.$term_name.')');
                                //making it downloadable
                                //$pdf->Output($class_name.' ('.$session_name.' - '.$term_name.')', 'D');
                                $pdf->Output();
                        }  
                    }
                }
                else
                {
                    die("No Result found for this class session/term ".mysqli_error($con));
                }

            
            }        
        } */
        /* if($term_name == "THIRD TERM")
        {
            $sql_run = mysqli_query($con, "SELECT * FROM result_tbl WHERE admNo = '$admNo' AND session_name='$session_name' AND term_name='$term_name' LIMIT 1;");
            if(!$sql_run)
            {
                die("Query failed ".mysqli_error($con));
            }
            else
            {
                if(mysqli_num_rows($sql_run) > 0)
                {
                    require_once('includes/fpdf8/fpdf.php');
                    class PDF extends FPDF
                    {
                        // Page footer
                        function Footer()
                        {
                            // Position at 1.5 cm from bottom
                            $this->SetY(-20);
                            // Arial italic 8
                            $this->SetFont('Arial','I',8);
                            // Page number
                            $this->Cell(0,5,'Page '.$this->PageNo().'/{nb}',0,1,'C');
                            $this->SetFont('Arial','I',8);
                            // Page number
                            $this->Cell(0,5,'(Date printed: '.date('d/M/Y').')',0,1,'R');
                        }
                    }
                    //Instanciation of inherited class
                    $pdf = new PDF('P', 'mm', 'A4' );
                    $pdf->AliasNbPages();
                    $pdf->AddPage();
                    
                    //fetching the results
                    $class_query = mysqli_fetch_assoc($sql_run);
                    $class_id = $class_query['class_id'];
                    $class_q = mysqli_query($con, "SELECT * FROM class_tbl WHERE class_id = '$class_id'");
                    $class_num = mysqli_num_rows($class_q);
                    $class_fetch = mysqli_fetch_assoc($class_q);
                    $class_name = $class_fetch['class_name'];
                    $teacher_id = $class_fetch['instructor_id'];
                    //Getting class population
                    $class_population = mysqli_query($con, "SELECT * FROM students_tbl WHERE class_name='$class_name';");
                    $class_num = mysqli_num_rows($class_population);
                    $get_student = mysqli_query($con, "SELECT * FROM students_tbl WHERE class_name='$class_name' LIMIT 1;");
                    if(!$get_student)
                    {
                        die("Query failed ".mysqli_error($con));
                    }
                    else
                    {
                        //$class_num = mysqli_num_rows($get_student);
                        if($get_student)
                        {
                            foreach($get_student as $row)
                            {
                                $class_name = $row['class_name'];
                                $sname = $row['sname'];
                                $lname = $row['lname'];
                                $oname = $row['oname'];
                                $gender = $row['gender'];
                                $admNo = $row['admNo'];
                                $religion = $row['religion'];
                                $passport = $row['passport'];
                                //Add image logo
                                $pdf ->Image('img/logoPdf.png', 7,7,33,34);
                                $pdf ->SetFont('Arial', 'B', 25);
                                $pdf ->Cell(190,10, 'SUCCESS SCHOOLS SOKOTO', 0, 0,'C');
                                $pdf ->ln(7);
                                $pdf ->SetFont('Times','I', 12);
                                $pdf ->Cell(180,10,'Nursery, Primary and Secondary',0,0,'C');
                                $pdf ->ln(7);
                                $pdf ->SetFont('Times','B', 14);
                                $pdf ->Cell(180,10,"Off Western Bypass Sokoto, Sokoto State.",0,0,'C');
                                $pdf ->ln(10);
                                $pdf ->SetFont('Times','I', 12);
                                $pdf ->Cell(180,10,"Tel: 08036505717, 08060860664",0,0,'C');
                                $pdf ->ln(5);
                                $pdf ->SetFont('Times','I', 12);
                                $pdf ->Cell(180,10,"Email: successschoolsnigeria@gmail.com",0,0,'C');
                                $pdf ->ln(20);
                                //Adding another image for the next record
                                $pdf ->Image('img/logoPdf.png', 7,7,33,34);
                                $pdf ->ln(10);
                                //Student Information goes here
                                $pdf ->SetFont('Arial','B', 15);
                                $pdf ->Cell(190,10,"$term_name REPORT SHEET $session_name SESSION ",0,1,'C');
                                $pdf ->ln(-3);
                                //Add Student image
                                //Controlling image
                                if(!empty($passport)){
                                    $pdf ->Image($passport, 170,30,30,30);    
                                }
                                //$pdf ->Image($passport, 170,30,30,30);
                                $pdf ->ln(5);
                                $pdf -> SetFont('Times','B', 10);
                                $pdf -> Cell(40,5,'ADMISSION NO.',1,0,'L');
                                $pdf -> Cell(40,5, $admNo, 1,0,'L');
                                $pdf -> Cell(40,5,'NAME',1,0,'L');
                                $pdf -> Cell(70,5, $sname." ".$lname." ".$oname, 1,1,'L');
                                $pdf -> Cell(40,5,'CLASS',1,0,'L');
                                $pdf -> Cell(40,5, $class_name, 1,0,'L');
                                $pdf -> Cell(40,5, "CLASS SIZE",1,0,'L');
                                $pdf -> Cell(70,5, $class_num, 1,1,'L');
                                $pdf -> Cell(40,5,'GENDER',1,0,'L');
                                $pdf -> Cell(40,5, $gender, 1,0,'L');
                                $pdf -> Cell(40,5,'RELIGION',1,0,'L');
                                $pdf -> Cell(70,5, $religion, 1,1,'L');
                                $pdf -> ln(5);
                                //SUBJECTS  header
                                $pdf ->SetFont('Times','B', 10);
                                $pdf ->Cell(75,5,'SUBJECTS',1,0,'L');
                                $pdf ->Cell(7,5,'CA',1,0,'C');
                                $pdf ->Cell(12,5,'EXAM',1,0,'C');
                                $pdf ->Cell(13,5,'TOTAL',1,0,'C');
                                $pdf ->Cell(18,5,"2nd TERM",1,0,'C');
                                $pdf ->Cell(17,5,'1st TERM',1,0,'C');
                                $pdf ->Cell(13,5,'S-AVG',1,0,'C');
                                $pdf ->Cell(10,5,'GRD',1,0,'C');
                                $pdf ->Cell(25,5,'REM',1,1,'C');
                                //Second Row
                                $pdf ->ln(0);
                                $pdf ->SetFont('Times','BI', 10);
                                $pdf ->Cell(75,5,'Maximum Mark Obtainable',1,0,'L');
                                $pdf ->Cell(7,5,'40',1,0,'C');
                                $pdf ->Cell(12,5,'60',1,0,'C');
                                $pdf ->Cell(13,5,'100',1,0,'C');
                                $pdf ->Cell(18,5,'100',1,0,'C');
                                $pdf ->Cell(17,5,'100',1,0,'C');
                                $pdf ->Cell(13,5,'100',1,0,'C');
                                $pdf ->Cell(10,5,'',1,0,'C');
                                $pdf ->Cell(25,5,'',1,1,'C');
                                $pdf ->ln(0);
                                //Fetching result
                                $res = mysqli_query($con, "SELECT * FROM result_tbl WHERE admNo='$admNo' AND session_name='$session_name' AND term_name='$term_name';");
                                if(mysqli_num_rows($res) > 0)
                                {
                                    $subject_count = mysqli_num_rows($res);
                                    while($results = mysqli_fetch_assoc($res))
                                    {
                                        $subject = $results['subject_id'];
                                        //Converting subject id to subject name
                                        $subject_query = mysqli_query($con, "SELECT * FROM subject_tbl WHERE subject_id = '$subject';");
                                        if($subject_query){
                                            foreach($subject_query as $subject_fetch)
                                            {
                                                $subject = $subject_fetch['subject_name'];
                                                $subject_id = $subject_fetch['subject_id'];
            
                                                //Row ENGLISH LANGUAGE
                                                $pdf ->SetFont('Times','', 10);
                                                $pdf ->Cell(75,5, $subject, 1,0,'L');
                                            }
                                        }
                                        $ca = $results['ca'];
                                        $exam = $results['exam'];
                                        $total = $results['total'];
                                        $grade = $results['grade'];
                                        $remark = $results['remark'];
                                        $pdf ->Cell(7,5, $ca,1,0,'C');
                                        $pdf ->Cell(12,5, $exam,1,0,'C');
                                        $pdf ->Cell(13,5, $total,1,0,'C');
                                        //Getting the overall total of each subject and adding to second term column
                                        $second_trm_query = mysqli_query($con, "SELECT * FROM result_tbl WHERE admNo='$admNo' AND session_name='$session_name' AND term_name='SECOND TERM' AND subject_id = '$subject_id';");
                                        $second_trm_num = mysqli_num_rows($second_trm_query);
                                        if($second_trm_num > 0)
                                        {
                                            while($second_trm_fetch = mysqli_fetch_assoc($second_trm_query))
                                            {
                                                $second_term = $second_trm_fetch['total'];
                                                $pdf ->Cell(18,5, $second_term,1,0,'C');
                                            }    
                                        }
                                        else{
                                            $error = "0";
                                            $pdf ->Cell(18,5, $error,1,0,'C');
                                        }
                                        //Getting the overall total of each subject and adding to first term column
                                        $first_trm_query = mysqli_query($con, "SELECT * FROM result_tbl WHERE admNo='$admNo' AND session_name='$session_name' AND term_name='FIRST TERM' AND subject_id = '$subject_id';");
                                        $first_trm_num = mysqli_num_rows($first_trm_query);
                                        if($first_trm_num > 0)
                                        {
                                            while($firt_trm_fetch = mysqli_fetch_assoc($first_trm_query))
                                            {
                                                $first_term = $firt_trm_fetch['total'];
                                                $pdf ->Cell(17,5, $first_term,1,0,'C');
                                            }    
                                        }
                                        else{
                                            $error = "0";
                                            $pdf ->Cell(17,5, $error,1,0,'C');
                                        }
                                        //sessional average for each subjects column
                                        $sess_avg_query = mysqli_query($con, "SELECT SUM(total) AS sess_avg_sum FROM result_tbl WHERE admNo = '$admNo' AND session_name='$session_name' AND subject_id = '$subject_id';");
                                        $sess_avg_num = mysqli_num_rows($sess_avg_query);
                                        if($sess_avg_num > 0)
                                        {
                                            while($sess_avg_fetch = mysqli_fetch_assoc($sess_avg_query))
                                            {
                                                //DIVIDING BY 3
                                                $sess_avg_term = $sess_avg_fetch['sess_avg_sum'];
                                                $pdf ->Cell(13,5, round(($sess_avg_term/3), 2, PHP_ROUND_HALF_UP),1,0,'C');
                                            }    
                                        }
                                        else{
                                            $error = "0";
                                            $pdf ->Cell(13,5, $error,1,0,'C');
                                        }                                    
                                        $pdf ->Cell(10,5, $grade,1,0,'C');
                                        $pdf ->Cell(25,5, $remark,1,1,'C');
                                        $pdf ->ln(0);                            
                                    }    
                                    }else{
                                        $error = "No Result uploaded";
                                        $pdf ->Cell(190,5, $error,1,0,'C');                        
                                    }
                                    
                                        //CREATE A SINGLE SPACE AFTER LISTS OF SUBJECTS
                                        $pdf ->SetFont('Times','', 10);
                                        $pdf ->Cell(75,5, "", 1,0,'L');
                                        $pdf ->Cell(7,5, "",1,0,'C');
                                        $pdf ->Cell(12,5, "",1,0,'C');
                                        $pdf ->Cell(13,5, "",1,0,'C');
                                        $pdf ->Cell(18,5, "",1,0,'C');
                                        $pdf ->Cell(17,5, "",1,0,'C');
                                        $pdf ->Cell(13,5, "",1,0,'C');
                                        $pdf ->Cell(10,5, "",1,0,'C');
                                        $pdf ->Cell(25,5, "",1,1,'C');
                                        $pdf ->ln(0);                            
                                
                                    
                                    //Getting total and average
                                    $sql_sum = mysqli_query($con, "SELECT SUM(total) AS total_sum FROM result_tbl WHERE admNo = '$admNo' AND session_name='$session_name' AND term_name='$term_name';");
                                    if(mysqli_num_rows($sql_sum) > 0)
                                    {
                                        $sql_fetch = mysqli_fetch_assoc($sql_sum);
                                        $total = $sql_fetch['total_sum'];
                                        //Row MATHS
                                        $pdf ->SetFont('Times','B', 10);
                                        $pdf ->Cell(94,5, "TOTAL ",1,0,'R');
                                        $pdf ->Cell(13,5, $total, 1,0,'C');
                                    }
                                    else{
                                        $error = "0";
                                        $pdf ->Cell(13,5, $error, 1,0,'C');
                                    }
                                    //Getting Second term total
                                    $second_term = "SECOND TERM";
                                    $second_term_sum = mysqli_query($con, "SELECT SUM(total) AS second_total_sum FROM result_tbl WHERE admNo = '$admNo' AND session_name='$session_name' AND term_name='$second_term';");
                                    if(mysqli_num_rows($second_term_sum) > 0)
                                    {
                                        $second_fetch = mysqli_fetch_assoc($second_term_sum);
                                        $second_term_total = $second_fetch['second_total_sum'];
                                        
                                        $pdf ->Cell(18,5, $second_term_total, 1,0,'C');
                                    }
                                    else{
                                        $error = "0";
                                        $pdf ->Cell(18,5, $error, 1,0,'C');
                                    }
                                    //Getting Firt term total
                                    $first_term = "FIRST TERM";
                                    $first_term_sum = mysqli_query($con, "SELECT SUM(total) AS first_total_sum FROM result_tbl WHERE admNo = '$admNo' AND session_name='$session_name' AND term_name='$first_term';");
                                    if(mysqli_num_rows($first_term_sum) > 0)
                                    {
                                        $first_fetch = mysqli_fetch_assoc($first_term_sum);
                                        $first_term_total = $first_fetch['first_total_sum'];
                                        
                                        $pdf ->Cell(17,5, $first_term_total, 1,0,'C');
                                    }
                                    else{
                                        $error = "0";
                                        $pdf ->Cell(17,5, $error, 1,0,'C');
                                    }
                                    //Getting sessional total (1st + 2nd + 3rd)/3
                                    $sessional_term_sum = mysqli_query($con, "SELECT SUM(total) AS sessional_total_sum FROM result_tbl WHERE admNo = '$admNo' AND session_name='$session_name';");
                                    if(mysqli_num_rows($sessional_term_sum) > 0)
                                    {
                                        $sessional_fetch = mysqli_fetch_assoc($sessional_term_sum);
                                        $sessional_term_total = $sessional_fetch['sessional_total_sum'];
                                        //Dividing by 3
                                        $div = ($sessional_term_total/3);
                                        //Rounding it up into 2 decimal places
                                        $sess_avg = round($div, 2, PHP_ROUND_HALF_UP);
                                        $pdf ->Cell(13,5, $sess_avg, 1,0,'C');
                                    }
                                    else{
                                        $error = "0";
                                        $pdf ->Cell(13,5, $error, 1,0,'C');
                                    }
                                    //TERM AVERAGE
                                    $pdf ->Cell(35,5,'AVERAGE = '.round(($total/$subject_count), 2, PHP_ROUND_HALF_UP),1,1,'C');
                                    $pdf ->Cell(190,5,'',1,1,'C');
                                    $pdf ->Cell(190,5,'',1,1,'C');
                                    $pdf ->Cell(190,5,'GRADES:  A1[80-100] B2[75-79] B3[70-74]  C4[65-69]  C5[60-64]  C6[50-59] D7[45-49] E8[40-44]  F9[0-39]',1,1,'C');
                                    $pdf ->Cell(190,5,'KEY NOTE: S-AVG [Sessional Average] GRD [Grade] REM [Remark] N/V [Not Available]',1,1,'C');
                                    $pdf ->ln(10);
                                
                                    //CLASS TEACHERS COMMENT HEAD
                                    $pdf ->SetFont('Times','B', 10);
                                    $pdf ->Cell(25,5,'BEHAVIOUR',1,0,'C');
                                    $pdf ->Cell(13,5,'RATE',1,0,'C');
                                    $pdf ->Cell(25,5,'BEHAVIOUR',1,0,'C');
                                    $pdf ->Cell(13,5,'RATE',1,0,'C');
                                    $pdf ->Cell(25,5,'BEHAVIOUR',1,0,'C');
                                    $pdf ->Cell(13,5,'RATE',1,0,'C');
                                    $pdf ->Cell(25,5,'BEHAVIOUR',1,0,'C');
                                    $pdf ->Cell(13,5,'RATE',1,0,'C');
                                    $pdf ->Cell(25,5,'BEHAVIOUR',1,0,'C');
                                    $pdf ->Cell(13,5,'RATE',1,1,'C');
                                    
                                    //CLASS TEACHERS COMMENT BODY
                                    $com = mysqli_query($con, "SELECT * FROM comments_tbl WHERE admNo='$admNo' AND session_name='$session_name' AND term_name='$term_name';");
                                    if(mysqli_num_rows($com) > 0)
                                    {
                                        while($results = mysqli_fetch_assoc($com))
                                        {
                                            $attendance = $results['attendance'];
                                            $honesty = $results['honesty'];
                                            $neatness = $results['neatness'];
                                            $obedience = $results['obedience'];
                                            $punctuality = $results['punctuality'];
                                            $tolerance = $results['tolerance'];
                                            $creativity = $results['creativity'];
                                            $dexterity = $results['dexterity'];
                                            $fluency = $results['fluency'];
                                            $handwriting = $results['handwriting'];
                                            $teacher_comment = $results['teacher_comment'];
                                            $principal_comment = $results['principal_comment'];
                                            $pdf ->SetFont('Times','', 8);
                                            $pdf ->Cell(25,5,'NEATNESS',1,0,'C');
                                            $pdf ->Cell(13,5, $neatness,1,0,'C');
                                            $pdf ->Cell(25,5,'PUNCTUALITY',1,0,'C');
                                            $pdf ->Cell(13,5,$punctuality,1,0,'C');
                                            $pdf ->Cell(25,5,'FLUENCY',1,0,'C');
                                            $pdf ->Cell(13,5, $fluency, 1,0,'C');
                                            $pdf ->Cell(25,5,'TOLERANCE',1,0,'C');
                                            $pdf ->Cell(13,5, $tolerance,1,0,'C');
                                            $pdf ->Cell(25,5,'OBEDIENCE',1,0,'C');
                                            $pdf ->Cell(13,5, $obedience,1,1,'C');
        
                                            //CLASS TEACHERS COMMENT BODY
                                            $pdf ->SetFont('Times','', 8);
                                            $pdf ->Cell(25,5,'ATTENDANCE',1,0,'C');
                                            $pdf ->Cell(13,5,$attendance,1,0,'C');
                                            $pdf ->Cell(25,5,'HONESTY',1,0,'C');
                                            $pdf ->Cell(13,5,$honesty,1,0,'C');
                                            $pdf ->Cell(25,5,'CREATIVITY',1,0,'C');
                                            $pdf ->Cell(13,5,$creativity,1,0,'C');
                                            $pdf ->Cell(25,5,'HANDWRITING',1,0,'C');
                                            $pdf ->Cell(13,5, $handwriting,1,0,'C');
                                            $pdf ->Cell(25,5,'DEXTERITY',1,0,'C');
                                            $pdf ->Cell(13,5, $dexterity,1,1,'C');
                                            $pdf ->SetFont('Times','B', 8);
                                            $pdf ->Cell(190,5,'KEY RATING:       A-EXCELLENT     B-VERY GOOD     C-SATISFACTORY      D-POOR      E-VERY POOR',1,1,'C');
                                            $pdf -> ln(10);
                                            //CLASS TEACHER AND PRINCIPAL COMMENTS
                                            $pdf ->SetFont('Times','B', 9);
                                            $teacher_query = mysqli_query($con, "SELECT * FROM staff_tbl WHERE staff_id='$teacher_id';");
                                            $teacher_fetch = mysqli_fetch_assoc($teacher_query);
                                            $t_fname = $teacher_fetch['fname'];
                                            $t_sname = $teacher_fetch['sname'];
                                            $t_oname = $teacher_fetch['oname'];
                                            $pdf ->Cell(53,5, "CLASS TEACHER'S NAME",1,0,'L');
                                            $pdf ->Cell(80,5, $t_fname." ".$t_sname." ".$t_oname,1,1,'L');
                                            $pdf ->Cell(53,5,"CLASS TEACHER'S COMMENT",1,0,'L');
                                            $pdf ->Cell(80,5, $teacher_comment,1,1,'L');
                                            $pdf ->Cell(53,5,'PRINCIPAL COMMENT',1,0,'L');
                                            $pdf ->Cell(80,5, $principal_comment,1,1,'L');
                                            $pdf ->Cell(53,5,'NEXT TERM BEGIN',1,0,'L');
                                            //Getting Next Term begins from the database
                                            $nxt_term_query = mysqli_query($con, "SELECT * FROM next_term_tbl;");
                                            if($nxt_term_query)
                                            {
                                                if(mysqli_num_rows($nxt_term_query) > 0)
                                                {
                                                    foreach($nxt_term_query as $next_term)
                                                    {
                                                        $next_term = ['next_term'];
                                                        $pdf ->Cell(80,5, $next_term,1,1,'L');
                                                    }
                                                }else{
                                                    $error = "Not Schedule";
                                                    $pdf ->Cell(80,5, $error,1,1,'L');
                                                }
                                            }
                                            else
                                            {
                                                $error = die("Query failed ".mysqli_error($con));
                                                $pdf ->Cell(80,5, $error,1,1,'L');    
                                            }
                                        }
                                    }
                                    else{
                                            $error = " No Teacher/Principal comments";
                                            $pdf ->Cell(190,5, $error,1,0,'C');                        
                                        }
                                            //SCHOOL STAMP
                                            //$pdf ->Image('img/signature.jpeg', 145,240,55,28);
                                            $pdf ->ln(30); 
                            }                       
                                //Outputting the pdf file
                                $pdf ->SetTitle($admNo.' ('.$session_name.' - '.$term_name.')');
                                //making it downloadable
                                //$pdf->Output($class_name.' ('.$session_name.' - '.$term_name.')', 'D');
                                $pdf->Output(); 
                            
                        }  
                    }
                }
                else
                {
                    die("No Result found for this class session/term ".mysqli_error($con));
                }

            
            }
        } */
    
}

/*****************************Making Downloadable ***********************************/
if (isset($_POST['download_view_btn'])) {
    $error = false;

    $admNo = mysqli_real_escape_string($con, $_POST['admNo']);
    $session_name = mysqli_real_escape_string($con, $_POST['session_name']);
    $term_name = mysqli_real_escape_string($con, $_POST['term_name']);
    if (empty($admNo)) {
        $error = true;
        echo "Adm Number is required";
        exit();
    }
    if (empty($session_name)) {
        $error = true;
        echo "Session is required";
        exit();
    }
    if (empty($term_name)) {
        $error = true;
        echo "Term is required";
        exit();
    }
    if (!$error) {
        //Checking if term is 1 2 and 3 Term
        if ($term_name == "FIRST TERM") {
            $sql_run = mysqli_query($con, "SELECT * FROM result_tbl WHERE admNo = '$admNo' AND session_name='$session_name' AND term_name='$term_name' LIMIT 1;");
            if (!$sql_run) {
                die("Query failed " . mysqli_error($con));
            } else {
                if (mysqli_num_rows($sql_run) > 0) {
                    require_once('includes/fpdf8/fpdf.php');
                    class PDF extends FPDF
                    {
                        // Page footer
                        function Footer()
                        {
                            // Position at 1.5 cm from bottom
                            $this->SetY(-20);
                            // Arial italic 8
                            $this->SetFont('Arial', 'I', 8);
                            // Page number
                            $this->Cell(0, 5, 'Page ' . $this->PageNo() . '/{nb}', 0, 1, 'C');
                            $this->SetFont('Arial', 'I', 8);
                            // Page number
                            $this->Cell(0, 5, '(Date printed: ' . date('d/M/Y') . ')', 0, 1, 'R');
                        }
                    }
                    //Instanciation of inherited class
                    $pdf = new PDF('P', 'mm', 'A4');
                    $pdf->AliasNbPages();
                    $pdf->AddPage();
                    //fetching the results
                    $class_query = mysqli_fetch_assoc($sql_run);
                    $class_id = $class_query['class_id'];
                    $class_q = mysqli_query($con, "SELECT * FROM class_tbl WHERE class_id = '$class_id';");
                    $class_num = mysqli_num_rows($class_q);
                    $class_fetch = mysqli_fetch_assoc($class_q);
                    $class_name = $class_fetch['class_name'];
                    $teacher_id = $class_fetch['instructor_id'];
                    //Getting class population
                    $class_population = mysqli_query($con, "SELECT * FROM students_tbl WHERE class_name='$class_name';");
                    $class_num = mysqli_num_rows($class_population);
                    $get_student = mysqli_query($con, "SELECT * FROM students_tbl WHERE class_name='$class_name' LIMIT 1;");
                    if (!$get_student) {
                        die("Query failed " . mysqli_error($con));
                    } else {
                        //$class_num = mysqli_num_rows($get_student);
                        if ($get_student) {
                            foreach ($get_student as $row) {
                                $class_name = $row['class_name'];
                                $sname = $row['sname'];
                                $lname = $row['lname'];
                                $oname = $row['oname'];
                                $gender = $row['gender'];
                                $admNo = $row['admNo'];
                                $religion = $row['religion'];
                                $passport = $row['passport'];
                                //Add image logo
                                //$pdf ->Image('img/logoPdf.png', 7,7,33,34);
                                $pdf->SetFont('Arial', 'B', 25);
                                $pdf->Cell(190, 10, 'SUCCESS SCHOOLS SOKOTO', 0, 0, 'C');
                                $pdf->ln(7);
                                $pdf->SetFont('Times', 'I', 12);
                                $pdf->Cell(180, 10, 'Nursery, Primary and Secondary', 0, 0, 'C');
                                $pdf->ln(7);
                                $pdf->SetFont('Times', 'B', 14);
                                $pdf->Cell(180, 10, "Off Western Bypass Sokoto, Sokoto State.", 0, 0, 'C');
                                $pdf->ln(10);
                                $pdf->SetFont('Times', 'I', 12);
                                $pdf->Cell(180, 10, "Tel: 08036505717, 08060860664", 0, 0, 'C');
                                $pdf->ln(5);
                                $pdf->SetFont('Times', 'I', 12);
                                $pdf->Cell(180, 10, "Email: successschoolsnigeria@gmail.com", 0, 0, 'C');
                                $pdf->ln(20);
                                //Adding another image for the next record
                                $pdf->Image('img/logoPdf.png', 7, 7, 33, 34);
                                $pdf->ln();
                                //Student Information goes here
                                $pdf->SetFont('Arial', 'B', 15);
                                $pdf->Cell(190, 10, "$term_name REPORT SHEET $session_name SESSION ", 0, 1, 'C');
                                $pdf->ln(-3);
                                //Add Student image
                                //Controlling image
                                if (!empty($passport)) {
                                    $pdf->Image($passport, 170, 30, 30, 30);
                                }
                                //$pdf ->Image($passport, 170,30,30,30);
                                $pdf->ln(5);
                                $pdf->SetFont('Times', 'B', 10);
                                $pdf->Cell(40, 5, 'ADMISSION NO.', 1, 0, 'L');
                                $pdf->Cell(40, 5, $admNo, 1, 0, 'L');
                                $pdf->Cell(40, 5, 'NAME', 1, 0, 'L');
                                $pdf->Cell(70, 5, $sname . " " . $lname . " " . $oname, 1, 1, 'L');
                                $pdf->Cell(40, 5, 'CLASS', 1, 0, 'L');
                                $pdf->Cell(40, 5, $class_name, 1, 0, 'L');
                                $pdf->Cell(40, 5, "CLASS SIZE", 1, 0, 'L');
                                $pdf->Cell(70, 5, $class_num, 1, 1, 'L');
                                $pdf->Cell(40, 5, 'GENDER', 1, 0, 'L');
                                $pdf->Cell(40, 5, $gender, 1, 0, 'L');
                                $pdf->Cell(40, 5, 'RELIGION', 1, 0, 'L');
                                $pdf->Cell(70, 5, $religion, 1, 1, 'L');
                                $pdf->ln(10);
                                //SUBJECTS  header
                                $pdf->SetFont('Times', 'B', 10);
                                $pdf->Cell(75, 5, 'SUBJECTS', 1, 0, 'L');
                                $pdf->Cell(15, 5, 'CA', 1, 0, 'C');
                                $pdf->Cell(20, 5, 'EXAM', 1, 0, 'C');
                                $pdf->Cell(20, 5, 'TOTAL', 1, 0, 'C');
                                $pdf->Cell(30, 5, 'GRADE', 1, 0, 'C');
                                $pdf->Cell(30, 5, 'REMARKS', 1, 1, 'C');
                                //Second Row
                                $pdf->ln(0);
                                $pdf->SetFont('Times', 'BI', 10);
                                $pdf->Cell(75, 5, 'Maximum Mark Obtainable', 1, 0, 'L');
                                $pdf->Cell(15, 5, '40', 1, 0, 'C');
                                $pdf->Cell(20, 5, '60', 1, 0, 'C');
                                $pdf->Cell(20, 5, '100', 1, 0, 'C');
                                $pdf->Cell(30, 5, '', 1, 0, 'C');
                                $pdf->Cell(30, 5, '', 1, 1, 'C');
                                $pdf->ln(0);
                                //Fetching result
                                $res = mysqli_query($con, "SELECT * FROM result_tbl WHERE admNo='$admNo' AND session_name='$session_name' AND term_name='$term_name';");
                                if (mysqli_num_rows($res) > 0) {
                                    $subject_count = mysqli_num_rows($res);
                                    while ($results = mysqli_fetch_assoc($res)) {
                                        $subject = $results['subject_id'];
                                        //Converting subject id to subject name
                                        $subject_query = mysqli_query($con, "SELECT * FROM subject_tbl WHERE subject_id = '$subject';");
                                        if ($subject_query) {
                                            foreach ($subject_query as $subject_fetch) {
                                                $subject = $subject_fetch['subject_name'];

                                                //Row ENGLISH LANGUAGE
                                                $pdf->SetFont('Times', '', 10);
                                                $pdf->Cell(75, 5, $subject, 1, 0, 'L');
                                            }
                                        }
                                        $ca = $results['ca'];
                                        $exam = $results['exam'];
                                        $total = $results['total'];
                                        $grade = $results['grade'];
                                        $remark = $results['remark'];
                                        $pdf->Cell(15, 5, $ca, 1, 0, 'C');
                                        $pdf->Cell(20, 5, $exam, 1, 0, 'C');
                                        $pdf->Cell(20, 5, $total, 1, 0, 'C');
                                        $pdf->Cell(30, 5, $grade, 1, 0, 'C');
                                        $pdf->Cell(30, 5, $remark, 1, 1, 'C');
                                        $pdf->ln(0);
                                    }
                                } else {
                                    $error = " No Result uploaded for this student";
                                    $pdf->Cell(190, 5, $error, 1, 0, 'C');
                                }
                                /*************************************** */
                                //CREATE A SINGLE SPACE AFTER LISTS OF SUBJECTS
                                $pdf->SetFont('Times', '', 10);
                                $pdf->Cell(75, 5, "", 1, 0, 'C');
                                $pdf->Cell(15, 5, "", 1, 0, 'C');
                                $pdf->Cell(20, 5, "", 1, 0, 'C');
                                $pdf->Cell(20, 5, "", 1, 0, 'C');
                                $pdf->Cell(30, 5, "", 1, 0, 'C');
                                $pdf->Cell(30, 5, "", 1, 0, 'C');
                                $pdf->ln();

                                /********************************************* */
                                //Getting total and average
                                $sql_sum = mysqli_query($con, " SELECT SUM(total) AS total_sum FROM result_tbl WHERE admNo = '$admNo' AND session_name='$session_name' AND term_name='$term_name';");
                                $sql_fetch = mysqli_fetch_assoc($sql_sum);
                                $total = $sql_fetch['total_sum'];
                                //Row MATHS
                                $pdf->SetFont('Times', 'B', 10);
                                $pdf->Cell(110, 5, 'TOTAL = ', 1, 0, 'R');
                                //$pdf ->Cell(15,5,'',1,0,'C');
                                //$pdf ->Cell(20,5,'',1,0,'C');
                                $pdf->Cell(20, 5, $total, 1, 0, 'C');
                                //TERM AVERAGE
                                $pdf->Cell(60, 5, 'AVERAGE = ' . round(($total / $subject_count), 2, PHP_ROUND_HALF_UP), 1, 1, 'L');
                                $pdf->Cell(190, 5, '', 1, 1, 'C');
                                $pdf->Cell(190, 5, '', 1, 1, 'C');
                                $pdf->Cell(190, 5, 'GRADES:  A1[80-100] B2[75-79] B3[70-74]  C4[65-69]  C5[60-64]  C6[50-59] D7[45-49] E8[40-44]  F9[0-39]', 1, 1, 'C');
                                $pdf->ln(10);

                                //CLASS TEACHERS COMMENT HEAD
                                $pdf->SetFont('Times', 'B', 10);
                                $pdf->Cell(25, 5, 'BEHAVIOUR', 1, 0, 'C');
                                $pdf->Cell(13, 5, 'RATE', 1, 0, 'C');
                                $pdf->Cell(25, 5, 'BEHAVIOUR', 1, 0, 'C');
                                $pdf->Cell(13, 5, 'RATE', 1, 0, 'C');
                                $pdf->Cell(25, 5, 'BEHAVIOUR', 1, 0, 'C');
                                $pdf->Cell(13, 5, 'RATE', 1, 0, 'C');
                                $pdf->Cell(25, 5, 'BEHAVIOUR', 1, 0, 'C');
                                $pdf->Cell(13, 5, 'RATE', 1, 0, 'C');
                                $pdf->Cell(25, 5, 'BEHAVIOUR', 1, 0, 'C');
                                $pdf->Cell(13, 5, 'RATE', 1, 1, 'C');

                                //CLASS TEACHERS COMMENT BODY
                                $com = mysqli_query($con, "SELECT * FROM comments_tbl WHERE admNo='$admNo' AND session_name='$session_name' AND term_name='$term_name';");
                                if (mysqli_num_rows($com) > 0) {
                                    while ($results = mysqli_fetch_assoc($com)) {
                                        $attendance = $results['attendance'];
                                        $honesty = $results['honesty'];
                                        $neatness = $results['neatness'];
                                        $obedience = $results['obedience'];
                                        $punctuality = $results['punctuality'];
                                        $tolerance = $results['tolerance'];
                                        $creativity = $results['creativity'];
                                        $dexterity = $results['dexterity'];
                                        $fluency = $results['fluency'];
                                        $handwriting = $results['handwriting'];
                                        $teacher_comment = $results['teacher_comment'];
                                        $principal_comment = $results['principal_comment'];
                                        $pdf->SetFont('Times', '', 8);
                                        $pdf->Cell(25, 5, 'NEATNESS', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $neatness, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'PUNCTUALITY', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $punctuality, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'FLUENCY', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $fluency, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'TOLERANCE', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $tolerance, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'OBEDIENCE', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $obedience, 1, 1, 'C');

                                        //CLASS TEACHERS COMMENT BODY
                                        $pdf->SetFont('Times', '', 8);
                                        $pdf->Cell(25, 5, 'ATTENDANCE', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $attendance, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'HONESTY', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $honesty, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'CREATIVITY', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $creativity, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'HANDWRITING', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $handwriting, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'DEXTERITY', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $dexterity, 1, 1, 'C');
                                        $pdf->SetFont('Times', 'B', 8);
                                        $pdf->Cell(190, 5, 'KEY RATING:       A-EXCELLENT     B-VERY GOOD     C-SATISFACTORY      D-POOR      E-VERY POOR', 1, 1, 'C');
                                        $pdf->ln(10);
                                        //CLASS TEACHER AND PRINCIPAL COMMENTS
                                        $pdf->SetFont('Times', 'B', 9);
                                        $teacher_query = mysqli_query($con, "SELECT * FROM staff_tbl WHERE staff_id='$teacher_id';");
                                        $teacher_fetch = mysqli_fetch_assoc($teacher_query);
                                        $t_fname = $teacher_fetch['fname'];
                                        $t_sname = $teacher_fetch['sname'];
                                        $t_oname = $teacher_fetch['oname'];
                                        $pdf->Cell(53, 5, "CLASS TEACHER'S NAME", 1, 0, 'L');
                                        $pdf->Cell(80, 5, $t_fname . " " . $t_sname . " " . $t_oname, 1, 1, 'L');
                                        $pdf->Cell(53, 5, "CLASS TEACHER'S COMMENT", 1, 0, 'L');
                                        $pdf->Cell(80, 5, $teacher_comment, 1, 1, 'L');
                                        $pdf->Cell(53, 5, 'PRINCIPAL COMMENT', 1, 0, 'L');
                                        $pdf->Cell(80, 5, $principal_comment, 1, 1, 'L');
                                        $pdf->Cell(53, 5, 'NEXT TERM BEGIN', 1, 0, 'L');
                                        //Getting Next Term begins from the database
                                        $nxt_term_query = mysqli_query($con, "SELECT * FROM next_term_tbl");
                                        if ($nxt_term_query) {
                                            if (mysqli_num_rows($nxt_term_query) > 0) {
                                                foreach ($nxt_term_query as $next_term) {
                                                    $next_term = ['next_term'];
                                                    $pdf->Cell(80, 5, $next_term, 1, 1, 'L');
                                                }
                                            } else {
                                                $error = "Not Schedule";
                                                $pdf->Cell(80, 5, $error, 1, 1, 'L');
                                            }
                                        } else {
                                            $error = die("Query failed " . mysqli_error($con));
                                            $pdf->Cell(80, 5, $error, 1, 1, 'L');
                                        }
                                    }
                                } else {
                                    $error = " No Teacher/Principal comments";
                                    $pdf->Cell(190, 5, $error, 1, 0, 'C');
                                }
                                //SCHOOL STAMP
                                //$pdf ->Image('img/signature.jpeg', 145,245,55,28);
                                $pdf->ln(30);
                            }
                            //making it downloadable
                            $downloadPDF = $admNo . ' (' . $session_name . ' - ' . $term_name . ')';
                            $pdf->Output($downloadPDF . '.pdf', 'D');
                        }
                    }
                } else {
                    die("No Result found for this class session/term " . mysqli_error($con));
                }
            }
        }

        if ($term_name == "SECOND TERM") {
            $sql_run = mysqli_query($con, "SELECT * FROM result_tbl WHERE admNo = '$admNo' AND session_name='$session_name' AND term_name='$term_name' LIMIT 1;");
            if (!$sql_run) {
                die("Query failed " . mysqli_error($con));
            } else {
                if (mysqli_num_rows($sql_run) > 0) {
                    require_once('includes/fpdf8/fpdf.php');
                    class PDF extends FPDF
                    {
                        // Page footer
                        function Footer()
                        {
                            // Position at 1.5 cm from bottom
                            $this->SetY(-20);
                            // Arial italic 8
                            $this->SetFont('Arial', 'I', 8);
                            // Page number
                            $this->Cell(0, 5, 'Page ' . $this->PageNo() . '/{nb}', 0, 1, 'C');
                            $this->SetFont('Arial', 'I', 8);
                            // Page number
                            $this->Cell(0, 5, '(Date printed: ' . date('d/M/Y') . ')', 0, 1, 'R');
                        }
                    }
                    //Instanciation of inherited class
                    $pdf = new PDF('P', 'mm', 'A4');
                    $pdf->AliasNbPages();
                    $pdf->AddPage();

                    //fetching the results
                    $class_query = mysqli_fetch_assoc($sql_run);
                    $class_id = $class_query['class_id'];
                    $class_q = mysqli_query($con, "SELECT * FROM class_tbl WHERE class_id = '$class_id';");
                    $class_num = mysqli_num_rows($class_q);
                    $class_fetch = mysqli_fetch_assoc($class_q);
                    $class_name = $class_fetch['class_name'];
                    $teacher_id = $class_fetch['instructor_id'];
                    //Getting class population
                    $class_population = mysqli_query($con, "SELECT * FROM students_tbl WHERE class_name='$class_name';");
                    $class_num = mysqli_num_rows($class_population);
                    $get_student = mysqli_query($con, "SELECT * FROM students_tbl WHERE class_name='$class_name' LIMIT 1;");
                    if (!$get_student) {
                        die("Query failed " . mysqli_error($con));
                    } else {
                        //$class_num = mysqli_num_rows($get_student);
                        if ($get_student) {
                            foreach ($get_student as $row) {
                                $class_name = $row['class_name'];
                                $sname = $row['sname'];
                                $lname = $row['lname'];
                                $oname = $row['oname'];
                                $gender = $row['gender'];
                                $admNo = $row['admNo'];
                                $religion = $row['religion'];
                                $passport = $row['passport'];
                                //Add image logo
                                $pdf->Image('img/logoPdf.png', 7, 7, 33, 34);
                                $pdf->SetFont('Arial', 'B', 25);
                                $pdf->Cell(190, 10, 'SUCCESS SCHOOLS SOKOTO', 0, 0, 'C');
                                $pdf->ln(7);
                                $pdf->SetFont('Times', 'I', 12);
                                $pdf->Cell(180, 10, 'Nursery, Primary and Secondary', 0, 0, 'C');
                                $pdf->ln(7);
                                $pdf->SetFont('Times', 'B', 14);
                                $pdf->Cell(180, 10, "Off Western Bypass Sokoto, Sokoto State.", 0, 0, 'C');
                                $pdf->ln(10);
                                $pdf->SetFont('Times', 'I', 12);
                                $pdf->Cell(180, 10, "Tel: 08036505717, 08060860664", 0, 0, 'C');
                                $pdf->ln(5);
                                $pdf->SetFont('Times', 'I', 12);
                                $pdf->Cell(180, 10, "Email: successschoolsnigeria@gmail.com", 0, 0, 'C');
                                $pdf->ln(20);
                                //Adding another image for the next record
                                $pdf->Image('img/logoPdf.png', 7, 7, 33, 34);
                                //Student Information goes here
                                $pdf->SetFont('Arial', 'B', 15);
                                $pdf->Cell(190, 10, "$term_name REPORT SHEET $session_name SESSION ", 0, 1, 'C');
                                $pdf->ln(-3);
                                //Add Student image
                                //Controlling image
                                if (!empty($passport)) {
                                    $pdf->Image($passport, 170, 30, 30, 30);
                                }
                                //$pdf ->Image($passport, 170,30,30,30);
                                $pdf->ln(5);
                                $pdf->SetFont('Times', 'B', 10);
                                $pdf->Cell(40, 5, 'ADMISSION NO.', 1, 0, 'L');
                                $pdf->Cell(40, 5, $admNo, 1, 0, 'L');
                                $pdf->Cell(40, 5, 'NAME', 1, 0, 'L');
                                $pdf->Cell(70, 5, $sname . " " . $lname . " " . $oname, 1, 1, 'L');
                                $pdf->Cell(40, 5, 'CLASS', 1, 0, 'L');
                                $pdf->Cell(40, 5, $class_name, 1, 0, 'L');
                                $pdf->Cell(40, 5, "CLASS SIZE", 1, 0, 'L');
                                $pdf->Cell(70, 5, $class_num, 1, 1, 'L');
                                $pdf->Cell(40, 5, 'GENDER', 1, 0, 'L');
                                $pdf->Cell(40, 5, $gender, 1, 0, 'L');
                                $pdf->Cell(40, 5, 'RELIGION', 1, 0, 'L');
                                $pdf->Cell(70, 5, $religion, 1, 1, 'L');
                                $pdf->ln(10);
                                //SUBJECTS  header
                                $pdf->SetFont('Times', 'B', 10);
                                $pdf->Cell(75, 5, 'SUBJECTS', 1, 0, 'L');
                                $pdf->Cell(10, 5, 'CA', 1, 0, 'C');
                                $pdf->Cell(15, 5, 'EXAM', 1, 0, 'C');
                                $pdf->Cell(15, 5, 'TOTAL', 1, 0, 'C');
                                $pdf->Cell(20, 5, '1st Term', 1, 0, 'C');
                                $pdf->Cell(25, 5, 'GRADE', 1, 0, 'C');
                                $pdf->Cell(30, 5, 'REMARKS', 1, 1, 'C');
                                //Second Row
                                $pdf->ln(0);
                                $pdf->SetFont('Times', 'BI', 10);
                                $pdf->Cell(75, 5, 'Maximum Mark Obtainable', 1, 0, 'L');
                                $pdf->Cell(10, 5, '40', 1, 0, 'C');
                                $pdf->Cell(15, 5, '60', 1, 0, 'C');
                                $pdf->Cell(15, 5, '100', 1, 0, 'C');
                                $pdf->Cell(20, 5, '100', 1, 0, 'C');
                                $pdf->Cell(25, 5, '', 1, 0, 'C');
                                $pdf->Cell(30, 5, '', 1, 1, 'C');
                                $pdf->ln(0);
                                //Fetching result
                                $res = mysqli_query($con, "SELECT * FROM result_tbl WHERE admNo='$admNo' AND session_name='$session_name' AND term_name='$term_name';");
                                if (mysqli_num_rows($res) > 0) {
                                    $subject_count = mysqli_num_rows($res);
                                    while ($results = mysqli_fetch_assoc($res)) {
                                        $subject = $results['subject_id'];
                                        //Converting subject id to subject name
                                        $subject_query = mysqli_query($con, "SELECT * FROM subject_tbl WHERE subject_id = '$subject';");
                                        if ($subject_query) {
                                            foreach ($subject_query as $subject_fetch) {
                                                $subject = $subject_fetch['subject_name'];
                                                $subject_id = $subject_fetch['subject_id'];

                                                //Row ENGLISH LANGUAGE
                                                $pdf->SetFont('Times', '', 10);
                                                $pdf->Cell(75, 5, $subject, 1, 0, 'L');
                                            }
                                        }
                                        $ca = $results['ca'];
                                        $exam = $results['exam'];
                                        $total = $results['total'];
                                        $grade = $results['grade'];
                                        $remark = $results['remark'];
                                        $pdf->Cell(10, 5, $ca, 1, 0, 'C');
                                        $pdf->Cell(15, 5, $exam, 1, 0, 'C');
                                        $pdf->Cell(15, 5, $total, 1, 0, 'C');
                                        //Getting the overall total of each subject and adding to first term column
                                        $first_trm_query = mysqli_query($con, "SELECT * FROM result_tbl WHERE admNo='$admNo' AND session_name='$session_name' AND term_name='FIRST TERM' AND subject_id = '$subject_id';");
                                        $first_trm_num = mysqli_num_rows($first_trm_query);
                                        if ($first_trm_num > 0) {
                                            while ($firt_trm_fetch = mysqli_fetch_assoc($first_trm_query)) {
                                                $first_term = $firt_trm_fetch['total'];
                                                $pdf->Cell(20, 5, $first_term, 1, 0, 'C');
                                            }
                                        } else {
                                            $error = "0";
                                            $pdf->Cell(20, 5, $error, 1, 0, 'C');
                                        }
                                        $pdf->Cell(25, 5, $grade, 1, 0, 'C');
                                        $pdf->Cell(30, 5, $remark, 1, 1, 'C');
                                        $pdf->ln(0);
                                    }
                                } else {
                                    $error = "No Result uploaded";
                                    $pdf->Cell(190, 5, $error, 1, 0, 'C');
                                }
                                /*************************************** */
                                //CREATE A SINGLE SPACE AFTER LISTS OF SUBJECTS
                                $pdf->SetFont('Times', '', 10);
                                $pdf->Cell(75, 5, "", 1, 0, 'C');
                                $pdf->Cell(10, 5, "", 1, 0, 'C');
                                $pdf->Cell(15, 5, "", 1, 0, 'C');
                                $pdf->Cell(15, 5, "", 1, 0, 'C');
                                $pdf->Cell(20, 5, "", 1, 0, 'C');
                                $pdf->Cell(25, 5, "", 1, 0, 'C');
                                $pdf->Cell(30, 5, "", 1, 1, 'C');
                                $pdf->ln(0);
                                /****************************************/
                                //Getting total and average
                                $sql_sum = mysqli_query($con, " SELECT SUM(total) AS total_sum FROM result_tbl WHERE admNo = '$admNo' AND session_name='$session_name' AND term_name='$term_name';");
                                if (mysqli_num_rows($sql_sum) > 0) {
                                    $sql_fetch = mysqli_fetch_assoc($sql_sum);
                                    $total = $sql_fetch['total_sum'];
                                    //Row MATHS
                                    $pdf->SetFont('Times', 'B', 10);
                                    $pdf->Cell(100, 5, "TOTAL ", 1, 0, 'R');
                                    $pdf->Cell(15, 5, $total, 1, 0, 'C');
                                } else {
                                    $error = "0";
                                    $pdf->Cell(15, 5, $error, 1, 0, 'C');
                                }

                                //Getting Firt term total
                                $first_term = "FIRST TERM";
                                $first_term_sum = mysqli_query($con, "SELECT SUM(total) AS first_total_sum FROM result_tbl WHERE admNo = '$admNo' AND session_name='$session_name' AND term_name='$first_term';");
                                if (mysqli_num_rows($first_term_sum) > 0) {
                                    $first_fetch = mysqli_fetch_assoc($first_term_sum);
                                    $first_term_total = $first_fetch['first_total_sum'];

                                    $pdf->Cell(20, 5, $first_term_total, 1, 0, 'C');
                                } else {
                                    $error = "0";
                                    $pdf->Cell(20, 5, $error, 1, 0, 'C');
                                }
                                //TERM AVERAGE
                                $pdf->Cell(55, 5, 'AVERAGE = ' . round(($total / $subject_count), 2, PHP_ROUND_HALF_UP), 1, 1, 'L');
                                $pdf->Cell(190, 5, '', 1, 1, 'C');
                                $pdf->Cell(190, 5, '', 1, 1, 'C');
                                $pdf->Cell(190, 5, 'GRADES:  A1[80-100] B2[75-79] B3[70-74]  C4[65-69]  C5[60-64]  C6[50-59] D7[45-49] E8[40-44]  F9[0-39]', 1, 1, 'C');
                                $pdf->ln(20);

                                //CLASS TEACHERS COMMENT HEAD
                                $pdf->SetFont('Times', 'B', 10);
                                $pdf->Cell(25, 5, 'BEHAVIOUR', 1, 0, 'C');
                                $pdf->Cell(13, 5, 'RATE', 1, 0, 'C');
                                $pdf->Cell(25, 5, 'BEHAVIOUR', 1, 0, 'C');
                                $pdf->Cell(13, 5, 'RATE', 1, 0, 'C');
                                $pdf->Cell(25, 5, 'BEHAVIOUR', 1, 0, 'C');
                                $pdf->Cell(13, 5, 'RATE', 1, 0, 'C');
                                $pdf->Cell(25, 5, 'BEHAVIOUR', 1, 0, 'C');
                                $pdf->Cell(13, 5, 'RATE', 1, 0, 'C');
                                $pdf->Cell(25, 5, 'BEHAVIOUR', 1, 0, 'C');
                                $pdf->Cell(13, 5, 'RATE', 1, 1, 'C');

                                //CLASS TEACHERS COMMENT BODY
                                $com = mysqli_query($con, "SELECT * FROM comments_tbl WHERE admNo='$admNo' AND session_name='$session_name' AND term_name='$term_name';");
                                if (mysqli_num_rows($com) > 0) {
                                    while ($results = mysqli_fetch_assoc($com)) {
                                        $attendance = $results['attendance'];
                                        $honesty = $results['honesty'];
                                        $neatness = $results['neatness'];
                                        $obedience = $results['obedience'];
                                        $punctuality = $results['punctuality'];
                                        $tolerance = $results['tolerance'];
                                        $creativity = $results['creativity'];
                                        $dexterity = $results['dexterity'];
                                        $fluency = $results['fluency'];
                                        $handwriting = $results['handwriting'];
                                        $teacher_comment = $results['teacher_comment'];
                                        $principal_comment = $results['principal_comment'];
                                        $pdf->SetFont('Times', '', 8);
                                        $pdf->Cell(25, 5, 'NEATNESS', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $neatness, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'PUNCTUALITY', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $punctuality, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'FLUENCY', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $fluency, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'TOLERANCE', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $tolerance, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'OBEDIENCE', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $obedience, 1, 1, 'C');

                                        //CLASS TEACHERS COMMENT BODY
                                        $pdf->SetFont('Times', '', 8);
                                        $pdf->Cell(25, 5, 'ATTENDANCE', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $attendance, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'HONESTY', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $honesty, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'CREATIVITY', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $creativity, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'HANDWRITING', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $handwriting, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'DEXTERITY', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $dexterity, 1, 1, 'C');
                                        $pdf->SetFont('Times', 'B', 8);
                                        $pdf->Cell(190, 5, 'KEY RATING:       A-EXCELLENT     B-VERY GOOD     C-SATISFACTORY      D-POOR      E-VERY POOR', 1, 1, 'C');
                                        $pdf->ln(10);
                                        //CLASS TEACHER AND PRINCIPAL COMMENTS
                                        $pdf->SetFont('Times', 'B', 9);
                                        $teacher_query = mysqli_query($con, "SELECT * FROM staff_tbl WHERE staff_id='$teacher_id';");
                                        $teacher_fetch = mysqli_fetch_assoc($teacher_query);
                                        $t_fname = $teacher_fetch['fname'];
                                        $t_sname = $teacher_fetch['sname'];
                                        $t_oname = $teacher_fetch['oname'];
                                        $pdf->Cell(53, 5, "CLASS TEACHER'S NAME", 1, 0, 'L');
                                        $pdf->Cell(80, 5, $t_fname . " " . $t_sname . " " . $t_oname, 1, 1, 'L');
                                        $pdf->Cell(53, 5, "CLASS TEACHER'S COMMENT", 1, 0, 'L');
                                        $pdf->Cell(80, 5, $teacher_comment, 1, 1, 'L');
                                        $pdf->Cell(53, 5, 'PRINCIPAL COMMENT', 1, 0, 'L');
                                        $pdf->Cell(80, 5, $principal_comment, 1, 1, 'L');
                                        $pdf->Cell(53, 5, 'NEXT TERM BEGIN', 1, 0, 'L');
                                        //Getting Next Term begins from the database
                                        $nxt_term_query = mysqli_query($con, "SELECT * FROM next_term_tbl;");
                                        if ($nxt_term_query) {
                                            if (mysqli_num_rows($nxt_term_query) > 0) {
                                                foreach ($nxt_term_query as $next_term) {
                                                    $next_term = ['next_term'];
                                                    $pdf->Cell(80, 5, $next_term, 1, 1, 'L');
                                                }
                                            } else {
                                                $error = "Not Schedule";
                                                $pdf->Cell(80, 5, $error, 1, 1, 'L');
                                            }
                                        } else {
                                            $error = die("Query failed " . mysqli_error($con));
                                            $pdf->Cell(80, 5, $error, 1, 1, 'L');
                                        }
                                    }
                                } else {
                                    $error = " No Teacher/Principal comments";
                                    $pdf->Cell(190, 5, $error, 1, 0, 'C');
                                }
                                //SCHOOL STAMP
                                //$pdf ->Image('img/signature.jpeg', 145,230,55,28);
                                $pdf->ln(30);
                            }
                            //making it downloadable
                            $downloadPDF = $admNo . ' (' . $session_name . ' - ' . $term_name . ')';
                            $pdf->Output($downloadPDF . '.pdf', 'D');
                        }
                    }
                } else {
                    die("No Result found for this class session/term " . mysqli_error($con));
                }
            }
        }
        if ($term_name == "THIRD TERM") {
            $sql_run = mysqli_query($con, "SELECT * FROM result_tbl WHERE admNo = '$admNo' AND session_name='$session_name' AND term_name='$term_name' LIMIT 1;");
            if (!$sql_run) {
                die("Query failed " . mysqli_error($con));
            } else {
                if (mysqli_num_rows($sql_run) > 0) {
                    require_once('includes/fpdf8/fpdf.php');
                    class PDF extends FPDF
                    {
                        // Page footer
                        function Footer()
                        {
                            // Position at 1.5 cm from bottom
                            $this->SetY(-20);
                            // Arial italic 8
                            $this->SetFont('Arial', 'I', 8);
                            // Page number
                            $this->Cell(0, 5, 'Page ' . $this->PageNo() . '/{nb}', 0, 1, 'C');
                            $this->SetFont('Arial', 'I', 8);
                            // Page number
                            $this->Cell(0, 5, '(Date printed: ' . date('d/M/Y') . ')', 0, 1, 'R');
                        }
                    }
                    //Instanciation of inherited class
                    $pdf = new PDF('P', 'mm', 'A4');
                    $pdf->AliasNbPages();
                    $pdf->AddPage();

                    //fetching the results
                    $class_query = mysqli_fetch_assoc($sql_run);
                    $class_id = $class_query['class_id'];
                    $class_q = mysqli_query($con, "SELECT * FROM class_tbl WHERE class_id = '$class_id';");
                    $class_num = mysqli_num_rows($class_q);
                    $class_fetch = mysqli_fetch_assoc($class_q);
                    $class_name = $class_fetch['class_name'];
                    $teacher_id = $class_fetch['instructor_id'];
                    //Getting class population
                    $class_population = mysqli_query($con, "SELECT * FROM students_tbl WHERE class_name='$class_name';");
                    $class_num = mysqli_num_rows($class_population);
                    $get_student = mysqli_query($con, "SELECT * FROM students_tbl WHERE class_name='$class_name' LIMIT 1;");
                    if (!$get_student) {
                        die("Query failed " . mysqli_error($con));
                    } else {
                        //$class_num = mysqli_num_rows($get_student);
                        if ($get_student) {
                            foreach ($get_student as $row) {
                                $class_name = $row['class_name'];
                                $sname = $row['sname'];
                                $lname = $row['lname'];
                                $oname = $row['oname'];
                                $gender = $row['gender'];
                                $admNo = $row['admNo'];
                                $religion = $row['religion'];
                                $passport = $row['passport'];
                                //Add image logo
                                $pdf->Image('img/logoPdf.png', 7, 7, 33, 34);
                                $pdf->SetFont('Arial', 'B', 25);
                                $pdf->Cell(190, 10, 'SUCCESS SCHOOLS SOKOTO', 0, 0, 'C');
                                $pdf->ln(7);
                                $pdf->SetFont('Times', 'I', 12);
                                $pdf->Cell(180, 10, 'Nursery, Primary and Secondary', 0, 0, 'C');
                                $pdf->ln(7);
                                $pdf->SetFont('Times', 'B', 14);
                                $pdf->Cell(180, 10, "Off Western Bypass Sokoto, Sokoto State.", 0, 0, 'C');
                                $pdf->ln(10);
                                $pdf->SetFont('Times', 'I', 12);
                                $pdf->Cell(180, 10, "Tel: 08036505717, 08060860664", 0, 0, 'C');
                                $pdf->ln(5);
                                $pdf->SetFont('Times', 'I', 12);
                                $pdf->Cell(180, 10, "Email: successschoolsnigeria@gmail.com", 0, 0, 'C');
                                $pdf->ln(20);
                                //Adding another image for the next record
                                $pdf->Image('img/logoPdf.png', 7, 7, 33, 34);
                                $pdf->ln(10);
                                //Student Information goes here
                                $pdf->SetFont('Arial', 'B', 15);
                                $pdf->Cell(190, 10, "$term_name REPORT SHEET $session_name SESSION ", 0, 1, 'C');
                                $pdf->ln(-3);
                                //Add Student image
                                //Controlling image
                                if (!empty($passport)) {
                                    $pdf->Image($passport, 170, 30, 30, 30);
                                }
                                //$pdf ->Image($passport, 170,30,30,30);
                                $pdf->ln(5);
                                $pdf->SetFont('Times', 'B', 10);
                                $pdf->Cell(40, 5, 'ADMISSION NO.', 1, 0, 'L');
                                $pdf->Cell(40, 5, $admNo, 1, 0, 'L');
                                $pdf->Cell(40, 5, 'NAME', 1, 0, 'L');
                                $pdf->Cell(70, 5, $sname . " " . $lname . " " . $oname, 1, 1, 'L');
                                $pdf->Cell(40, 5, 'CLASS', 1, 0, 'L');
                                $pdf->Cell(40, 5, $class_name, 1, 0, 'L');
                                $pdf->Cell(40, 5, "CLASS SIZE", 1, 0, 'L');
                                $pdf->Cell(70, 5, $class_num, 1, 1, 'L');
                                $pdf->Cell(40, 5, 'GENDER', 1, 0, 'L');
                                $pdf->Cell(40, 5, $gender, 1, 0, 'L');
                                $pdf->Cell(40, 5, 'RELIGION', 1, 0, 'L');
                                $pdf->Cell(70, 5, $religion, 1, 1, 'L');
                                $pdf->ln(5);
                                //SUBJECTS  header
                                $pdf->SetFont('Times', 'B', 10);
                                $pdf->Cell(75, 5, 'SUBJECTS', 1, 0, 'L');
                                $pdf->Cell(7, 5, 'CA', 1, 0, 'C');
                                $pdf->Cell(12, 5, 'EXAM', 1, 0, 'C');
                                $pdf->Cell(13, 5, 'TOTAL', 1, 0, 'C');
                                $pdf->Cell(18, 5, "2nd TERM", 1, 0, 'C');
                                $pdf->Cell(17, 5, '1st TERM', 1, 0, 'C');
                                $pdf->Cell(13, 5, 'S-AVG', 1, 0, 'C');
                                $pdf->Cell(10, 5, 'GRD', 1, 0, 'C');
                                $pdf->Cell(25, 5, 'REM', 1, 1, 'C');
                                //Second Row
                                $pdf->ln(0);
                                $pdf->SetFont('Times', 'BI', 10);
                                $pdf->Cell(75, 5, 'Maximum Mark Obtainable', 1, 0, 'L');
                                $pdf->Cell(7, 5, '40', 1, 0, 'C');
                                $pdf->Cell(12, 5, '60', 1, 0, 'C');
                                $pdf->Cell(13, 5, '100', 1, 0, 'C');
                                $pdf->Cell(18, 5, '100', 1, 0, 'C');
                                $pdf->Cell(17, 5, '100', 1, 0, 'C');
                                $pdf->Cell(13, 5, '100', 1, 0, 'C');
                                $pdf->Cell(10, 5, '', 1, 0, 'C');
                                $pdf->Cell(25, 5, '', 1, 1, 'C');
                                $pdf->ln(0);
                                //Fetching result
                                $res = mysqli_query($con, "SELECT * FROM result_tbl WHERE admNo='$admNo' AND session_name='$session_name' AND term_name='$term_name';");
                                if (mysqli_num_rows($res) > 0) {
                                    $subject_count = mysqli_num_rows($res);
                                    while ($results = mysqli_fetch_assoc($res)) {
                                        $subject = $results['subject_id'];
                                        //Converting subject id to subject name
                                        $subject_query = mysqli_query($con, "SELECT * FROM subject_tbl WHERE subject_id = '$subject';");
                                        if ($subject_query) {
                                            foreach ($subject_query as $subject_fetch) {
                                                $subject = $subject_fetch['subject_name'];
                                                $subject_id = $subject_fetch['subject_id'];

                                                //Row ENGLISH LANGUAGE
                                                $pdf->SetFont('Times', '', 10);
                                                $pdf->Cell(75, 5, $subject, 1, 0, 'L');
                                            }
                                        }
                                        $ca = $results['ca'];
                                        $exam = $results['exam'];
                                        $total = $results['total'];
                                        $grade = $results['grade'];
                                        $remark = $results['remark'];
                                        $pdf->Cell(7, 5, $ca, 1, 0, 'C');
                                        $pdf->Cell(12, 5, $exam, 1, 0, 'C');
                                        $pdf->Cell(13, 5, $total, 1, 0, 'C');
                                        //Getting the overall total of each subject and adding to second term column
                                        $second_trm_query = mysqli_query($con, "SELECT * FROM result_tbl WHERE admNo='$admNo' AND session_name='$session_name' AND term_name='SECOND TERM' AND subject_id = '$subject_id';");
                                        $second_trm_num = mysqli_num_rows($second_trm_query);
                                        if ($second_trm_num > 0) {
                                            while ($second_trm_fetch = mysqli_fetch_assoc($second_trm_query)) {
                                                $second_term = $second_trm_fetch['total'];
                                                $pdf->Cell(18, 5, $second_term, 1, 0, 'C');
                                            }
                                        } else {
                                            $error = "0";
                                            $pdf->Cell(18, 5, $error, 1, 0, 'C');
                                        }
                                        //Getting the overall total of each subject and adding to first term column
                                        $first_trm_query = mysqli_query($con, "SELECT * FROM result_tbl WHERE admNo='$admNo' AND session_name='$session_name' AND term_name='FIRST TERM' AND subject_id = '$subject_id';");
                                        $first_trm_num = mysqli_num_rows($first_trm_query);
                                        if ($first_trm_num > 0) {
                                            while ($firt_trm_fetch = mysqli_fetch_assoc($first_trm_query)) {
                                                $first_term = $firt_trm_fetch['total'];
                                                $pdf->Cell(17, 5, $first_term, 1, 0, 'C');
                                            }
                                        } else {
                                            $error = "0";
                                            $pdf->Cell(17, 5, $error, 1, 0, 'C');
                                        }
                                        //sessional average for each subjects column
                                        $sess_avg_query = mysqli_query($con, "SELECT SUM(total) AS sess_avg_sum FROM result_tbl WHERE admNo = '$admNo' AND session_name='$session_name' AND subject_id = '$subject_id';");
                                        $sess_avg_num = mysqli_num_rows($sess_avg_query);
                                        if ($sess_avg_num > 0) {
                                            while ($sess_avg_fetch = mysqli_fetch_assoc($sess_avg_query)) {
                                                //DIVIDING BY 3
                                                $sess_avg_term = $sess_avg_fetch['sess_avg_sum'];
                                                $pdf->Cell(13, 5, round(($sess_avg_term / 3), 2, PHP_ROUND_HALF_UP), 1, 0, 'C');
                                            }
                                        } else {
                                            $error = "0";
                                            $pdf->Cell(13, 5, $error, 1, 0, 'C');
                                        }
                                        $pdf->Cell(10, 5, $grade, 1, 0, 'C');
                                        $pdf->Cell(25, 5, $remark, 1, 1, 'C');
                                        $pdf->ln(0);
                                    }
                                } else {
                                    $error = "No Result uploaded";
                                    $pdf->Cell(190, 5, $error, 1, 0, 'C');
                                }
                                /*************************************** */
                                //CREATE A SINGLE SPACE AFTER LISTS OF SUBJECTS
                                $pdf->SetFont('Times', '', 10);
                                $pdf->Cell(75, 5, "", 1, 0, 'L');
                                $pdf->Cell(7, 5, "", 1, 0, 'C');
                                $pdf->Cell(12, 5, "", 1, 0, 'C');
                                $pdf->Cell(13, 5, "", 1, 0, 'C');
                                $pdf->Cell(18, 5, "", 1, 0, 'C');
                                $pdf->Cell(17, 5, "", 1, 0, 'C');
                                $pdf->Cell(13, 5, "", 1, 0, 'C');
                                $pdf->Cell(10, 5, "", 1, 0, 'C');
                                $pdf->Cell(25, 5, "", 1, 1, 'C');
                                $pdf->ln(0);

                                /****************************************/
                                //Getting total and average
                                $sql_sum = mysqli_query($con, "SELECT SUM(total) AS total_sum FROM result_tbl WHERE admNo = '$admNo' AND session_name='$session_name' AND term_name='$term_name';");
                                if (mysqli_num_rows($sql_sum) > 0) {
                                    $sql_fetch = mysqli_fetch_assoc($sql_sum);
                                    $total = $sql_fetch['total_sum'];
                                    //Row MATHS
                                    $pdf->SetFont('Times', 'B', 10);
                                    $pdf->Cell(94, 5, "TOTAL ", 1, 0, 'R');
                                    $pdf->Cell(13, 5, $total, 1, 0, 'C');
                                } else {
                                    $error = "0";
                                    $pdf->Cell(13, 5, $error, 1, 0, 'C');
                                }
                                //Getting Second term total
                                $second_term = "SECOND TERM";
                                $second_term_sum = mysqli_query($con, "SELECT SUM(total) AS second_total_sum FROM result_tbl WHERE admNo = '$admNo' AND session_name='$session_name' AND term_name='$second_term';");
                                if (mysqli_num_rows($second_term_sum) > 0) {
                                    $second_fetch = mysqli_fetch_assoc($second_term_sum);
                                    $second_term_total = $second_fetch['second_total_sum'];

                                    $pdf->Cell(18, 5, $second_term_total, 1, 0, 'C');
                                } else {
                                    $error = "0";
                                    $pdf->Cell(18, 5, $error, 1, 0, 'C');
                                }
                                //Getting Firt term total
                                $first_term = "FIRST TERM";
                                $first_term_sum = mysqli_query($con, "SELECT SUM(total) AS first_total_sum FROM result_tbl WHERE admNo = '$admNo' AND session_name='$session_name' AND term_name='$first_term';");
                                if (mysqli_num_rows($first_term_sum) > 0) {
                                    $first_fetch = mysqli_fetch_assoc($first_term_sum);
                                    $first_term_total = $first_fetch['first_total_sum'];

                                    $pdf->Cell(17, 5, $first_term_total, 1, 0, 'C');
                                } else {
                                    $error = "0";
                                    $pdf->Cell(17, 5, $error, 1, 0, 'C');
                                }
                                //Getting sessional total (1st + 2nd + 3rd)/3
                                $sessional_term_sum = mysqli_query($con, "SELECT SUM(total) AS sessional_total_sum FROM result_tbl WHERE admNo = '$admNo' AND session_name='$session_name';");
                                if (mysqli_num_rows($sessional_term_sum) > 0) {
                                    $sessional_fetch = mysqli_fetch_assoc($sessional_term_sum);
                                    $sessional_term_total = $sessional_fetch['sessional_total_sum'];
                                    //Dividing by 3
                                    $div = ($sessional_term_total / 3);
                                    //Rounding it up into 2 decimal places
                                    $sess_avg = round($div, 2, PHP_ROUND_HALF_UP);
                                    $pdf->Cell(13, 5, $sess_avg, 1, 0, 'C');
                                } else {
                                    $error = "0";
                                    $pdf->Cell(13, 5, $error, 1, 0, 'C');
                                }
                                //TERM AVERAGE
                                $pdf->Cell(35, 5, 'AVERAGE = ' . round(($total / $subject_count), 2, PHP_ROUND_HALF_UP), 1, 1, 'C');
                                $pdf->Cell(190, 5, '', 1, 1, 'C');
                                $pdf->Cell(190, 5, '', 1, 1, 'C');
                                $pdf->Cell(190, 5, 'GRADES:  A1[80-100] B2[75-79] B3[70-74]  C4[65-69]  C5[60-64]  C6[50-59] D7[45-49] E8[40-44]  F9[0-39]', 1, 1, 'C');
                                $pdf->Cell(190, 5, 'KEY NOTE: S-AVG [Sessional Average] GRD [Grade] REM [Remark] N/V [Not Available]', 1, 1, 'C');
                                $pdf->ln(10);

                                //CLASS TEACHERS COMMENT HEAD
                                $pdf->SetFont('Times', 'B', 10);
                                $pdf->Cell(25, 5, 'BEHAVIOUR', 1, 0, 'C');
                                $pdf->Cell(13, 5, 'RATE', 1, 0, 'C');
                                $pdf->Cell(25, 5, 'BEHAVIOUR', 1, 0, 'C');
                                $pdf->Cell(13, 5, 'RATE', 1, 0, 'C');
                                $pdf->Cell(25, 5, 'BEHAVIOUR', 1, 0, 'C');
                                $pdf->Cell(13, 5, 'RATE', 1, 0, 'C');
                                $pdf->Cell(25, 5, 'BEHAVIOUR', 1, 0, 'C');
                                $pdf->Cell(13, 5, 'RATE', 1, 0, 'C');
                                $pdf->Cell(25, 5, 'BEHAVIOUR', 1, 0, 'C');
                                $pdf->Cell(13, 5, 'RATE', 1, 1, 'C');

                                //CLASS TEACHERS COMMENT BODY
                                $com = mysqli_query($con, "SELECT * FROM comments_tbl WHERE admNo='$admNo' AND session_name='$session_name' AND term_name='$term_name';");
                                if (mysqli_num_rows($com) > 0) {
                                    while ($results = mysqli_fetch_assoc($com)) {
                                        $attendance = $results['attendance'];
                                        $honesty = $results['honesty'];
                                        $neatness = $results['neatness'];
                                        $obedience = $results['obedience'];
                                        $punctuality = $results['punctuality'];
                                        $tolerance = $results['tolerance'];
                                        $creativity = $results['creativity'];
                                        $dexterity = $results['dexterity'];
                                        $fluency = $results['fluency'];
                                        $handwriting = $results['handwriting'];
                                        $teacher_comment = $results['teacher_comment'];
                                        $principal_comment = $results['principal_comment'];
                                        $pdf->SetFont('Times', '', 8);
                                        $pdf->Cell(25, 5, 'NEATNESS', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $neatness, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'PUNCTUALITY', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $punctuality, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'FLUENCY', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $fluency, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'TOLERANCE', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $tolerance, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'OBEDIENCE', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $obedience, 1, 1, 'C');

                                        //CLASS TEACHERS COMMENT BODY
                                        $pdf->SetFont('Times', '', 8);
                                        $pdf->Cell(25, 5, 'ATTENDANCE', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $attendance, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'HONESTY', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $honesty, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'CREATIVITY', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $creativity, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'HANDWRITING', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $handwriting, 1, 0, 'C');
                                        $pdf->Cell(25, 5, 'DEXTERITY', 1, 0, 'C');
                                        $pdf->Cell(13, 5, $dexterity, 1, 1, 'C');
                                        $pdf->SetFont('Times', 'B', 8);
                                        $pdf->Cell(190, 5, 'KEY RATING:       A-EXCELLENT     B-VERY GOOD     C-SATISFACTORY      D-POOR      E-VERY POOR', 1, 1, 'C');
                                        $pdf->ln(10);
                                        //CLASS TEACHER AND PRINCIPAL COMMENTS
                                        $pdf->SetFont('Times', 'B', 9);
                                        $teacher_query = mysqli_query($con, "SELECT * FROM staff_tbl WHERE staff_id='$teacher_id';");
                                        $teacher_fetch = mysqli_fetch_assoc($teacher_query);
                                        $t_fname = $teacher_fetch['fname'];
                                        $t_sname = $teacher_fetch['sname'];
                                        $t_oname = $teacher_fetch['oname'];
                                        $pdf->Cell(53, 5, "CLASS TEACHER'S NAME", 1, 0, 'L');
                                        $pdf->Cell(80, 5, $t_fname . " " . $t_sname . " " . $t_oname, 1, 1, 'L');
                                        $pdf->Cell(53, 5, "CLASS TEACHER'S COMMENT", 1, 0, 'L');
                                        $pdf->Cell(80, 5, $teacher_comment, 1, 1, 'L');
                                        $pdf->Cell(53, 5, 'PRINCIPAL COMMENT', 1, 0, 'L');
                                        $pdf->Cell(80, 5, $principal_comment, 1, 1, 'L');
                                        $pdf->Cell(53, 5, 'NEXT TERM BEGIN', 1, 0, 'L');
                                        //Getting Next Term begins from the database
                                        $nxt_term_query = mysqli_query($con, "SELECT * FROM next_term_tbl;");
                                        if ($nxt_term_query) {
                                            if (mysqli_num_rows($nxt_term_query) > 0) {
                                                foreach ($nxt_term_query as $next_term) {
                                                    $next_term = ['next_term'];
                                                    $pdf->Cell(80, 5, $next_term, 1, 1, 'L');
                                                }
                                            } else {
                                                $error = "Not Schedule";
                                                $pdf->Cell(80, 5, $error, 1, 1, 'L');
                                            }
                                        } else {
                                            $error = die("Query failed " . mysqli_error($con));
                                            $pdf->Cell(80, 5, $error, 1, 1, 'L');
                                        }
                                    }
                                } else {
                                    $error = " No Teacher/Principal comments";
                                    $pdf->Cell(190, 5, $error, 1, 0, 'C');
                                }
                                //SCHOOL STAMP
                                //$pdf ->Image('img/signature.jpeg', 145,240,55,28);
                                $pdf->ln(30);
                            }
                            //making it downloadable
                            $downloadPDF = $admNo . ' (' . $session_name . ' - ' . $term_name . ')';
                            $pdf->Output($downloadPDF . '.pdf', 'D');
                        }
                    }
                } else {
                    die("No Result found for this class session/term " . mysqli_error($con));
                }
            }
        }
    }
}


/*****************************Making Downloadable ***********************************/
