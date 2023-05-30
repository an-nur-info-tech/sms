<?php
include('../database/Database.php');
require('../assets/fpdf8/fpdf.php');

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

if (isset($_POST['view_class_btn'])) {
    $class_id = $_POST['class_id'];
    $session_id = $_POST['session_id'];
    $term_id = $_POST['term_id'];

    $db = new Database();
    // Get class total
    $db->query("SELECT * FROM students_tbl WHERE class_id = :class_id;");
    $db->bind(':class_id', $class_id);
    $db->execute();
    if ($db->rowCount() > 0) {
        $class_total = $db->rowCount();
    } else {
        $class_total = "No class total";
    }
    // Get class teacher  name
    $db->query("SELECT sname, fname, oname FROM class_tbl AS cs JOIN staff_tbl ON staff_tbl.staff_id = cs.instructor_id WHERE class_id = :class_id;");
    $db->bind(':class_id', $class_id);
    $db->execute();
    if ($db->rowCount() > 0) {
        $result = $db->single();
        $class_teacher_name =  "$result->fname $result->sname $result->oname";
    } else {
        $class_teacher_name = "N/A";
    }
    // Get session name and term name
    $db->query(
        "SELECT * FROM tbl_year_session AS tbs 
        JOIN session_tbl ON session_tbl.session_id = tbs.session_id
        JOIN term_tbl ON term_tbl.term_id = tbs.term_id
        WHERE tbs.session_id = :session_id AND tbs.term_id = :term_id;
    "
    );
    $db->bind(':session_id', $session_id);
    $db->bind(':term_id', $term_id);
    $db->execute();
    if ($db->rowCount() > 0) {
        $result = $db->single();
        $session_name = $result->session_name;
        $term_name = $result->term_name;
    } else {
        $session_name = "N/A";
        $term_name = "N/A";
    }

    if ($term_id == 1) //First term
    {
        // Check if result exist
        $db->query(
            "SELECT * FROM result_tbl 
            WHERE class_id = :class_id 
            AND session_id = :session_id 
            AND term_id = :term_id 
            LIMIT 1;
        "
        );
        $db->bind(':class_id', $class_id);
        $db->bind(':session_id', $session_id);
        $db->bind(':term_id', $term_id);
        if ($db->execute()) {
            if ($db->rowCount() > 0) {
                // Create PDF Object 
                $pdf = new PDF('P', 'mm', 'A4');
                $pdf->AliasNbPages();
                $pdf->AddPage();

                // Fetch each student result
                $db->query(
                    "SELECT * FROM students_tbl AS rt JOIN class_tbl ON class_tbl.class_id = rt.class_id  WHERE rt.class_id = :class_id;
                "
                );
                $db->bind(':class_id', $class_id);
                $db->execute();
                if ($db->rowCount() > 0) {
                    $result = $db->resultset();
                    foreach ($result as $row) {
                        $class_name = $row->class_name;
                        $sname = $row->sname;
                        $lname = $row->lname;
                        $oname = $row->oname;
                        $gender = $row->gender;
                        $admNo = $row->admNo;
                        $religion = $row->religion;
                        $passport = $row->passport;
                        //Add image logo
                        // $pdf ->Image('../uploads/img/logoPdf.png', 7,7,33,34);
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
                        $pdf->Image('../uploads/img/logoPdf.png', 7, 7, 33, 34);
                        $pdf->ln();
                        //Student Information goes here
                        $pdf->SetFont('Arial', 'B', 15);
                        $pdf->Cell(190, 10, "$term_name REPORT SHEET $session_name SESSION ", 0, 1, 'C');
                        $pdf->ln(-3);
                        //Add Student image
                        if (!empty($passport)) {
                            $pdf->Image($passport, 170, 30, 30, 30);
                        }
                        $pdf->Image('../uploads/student_image.jpg', 170, 30, 30, 30);
                        $pdf->ln(5);
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'ADMISSION NO.', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(40, 5, $admNo, 1, 0, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'NAME', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(70, 5, $sname . " " . $lname . " " . $oname, 1, 1, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'CLASS', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(40, 5, $class_name, 1, 0, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, "CLASS SIZE", 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(70, 5, $class_total, 1, 1, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'GENDER', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(40, 5, $gender, 1, 0, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'RELIGION', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
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
                        $db->query(
                            "SELECT * FROM result_tbl AS rs 
                            JOIN subject_tbl ON subject_tbl.subject_id = rs.subject_id 
                            WHERE admNo = :admNo 
                            AND session_id = :session_id 
                            AND term_id = :term_id;"
                        );
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $results = $db->resultset();

                            foreach ($results as $result) {
                                $pdf->SetFont('Times', '', 10);
                                $pdf->Cell(75, 5, $result->subject_name, 1, 0, 'L');

                                if (($result->ca == 0) || ($result->ca == null) || empty($result->ca)) {
                                    $ca = "N/A";
                                } else {
                                    $ca = $result->ca;
                                }

                                if (($result->exam == 0) || ($result->exam == null) || empty($result->exam)) {
                                    $exam = "N/A";
                                } else {
                                    $exam = $result->exam;
                                }

                                $total = $result->total;
                                $grade = $result->grade;
                                $remark = $result->remark;

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
                        //Getting total 
                        $db->query("SELECT SUM(total) AS total_sum FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        $db->execute();
                        $total = $db->single()->total_sum;
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(110, 5, 'TOTAL = ', 1, 0, 'R');
                        //$pdf ->Cell(15,5,'',1,0,'C');
                        //$pdf ->Cell(20,5,'',1,0,'C');
                        $pdf->Cell(20, 5, $total, 1, 0, 'C');

                        //Getting  average
                        $db->query("SELECT AVG(total) AS average FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        $db->execute();
                        $average = $db->single()->average;
                        //TERM AVERAGE
                        $pdf->Cell(60, 5, 'AVERAGE = ' . round(($average), 2, PHP_ROUND_HALF_UP), 1, 1, 'L');
                        $pdf->Cell(190, 5, '', 1, 1, 'C');
                        $pdf->Cell(190, 5, '', 1, 1, 'C');
                        $pdf->Cell(190, 5, 'GRADES:  A1[80-100] B2[75-79] B3[70-74]  C4[65-69]  C5[60-64]  C6[50-59] D7[45-49] E8[40-44]  F9[0-39]', 1, 1, 'C');
                        $pdf ->Cell(190,5,'KEY NOTE: N/A [Not Available]',1,1,'C');
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
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $results = $db->single();

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

                            //CLASS TEACHER AND PRINCIPAL COMMENTS
                            $pdf->SetFont('Times', 'B', 9);
                            $pdf->Cell(53, 5, "CLASS TEACHER'S NAME", 1, 0, 'L');
                            $pdf->Cell(80, 5, $class_teacher_name, 1, 1, 'L');
                            $pdf->Cell(53, 5, "CLASS TEACHER'S COMMENT", 1, 0, 'L');
                            $pdf->Cell(80, 5, $teacher_comment, 1, 1, 'L');
                            $pdf->Cell(53, 5, 'PRINCIPAL COMMENT', 1, 0, 'L');
                            $pdf->Cell(80, 5, $principal_comment, 1, 1, 'L');
                            $pdf->Cell(53, 5, 'NEXT TERM BEGIN', 1, 0, 'L');

                            //Getting Next Term begins from the database
                            $db->query("SELECT * FROM next_term_tbl");
                            $db->execute();
                            if ($db->rowCount() > 0) {
                                $result = $db->single();
                                $pdf->Cell(80, 5, $result->next_term, 1, 1, 'L');
                            } else {
                                $error = "Not Schedule";
                                $pdf->Cell(80, 5, $error, 1, 1, 'L');
                            }
                        } else {
                            $error = " No Teacher/Principal comments";
                            $pdf->Cell(190, 5, $error, 1, 0, 'C');
                        }
                        //SCHOOL STAMP
                        //$pdf ->Image('img/signature.jpeg', 145,245,55,28);
                        $pdf->ln(30);
                    }
                    //Outputting the pdf file
                    $pdf->SetTitle($class_name . ' (' . $session_name . ' - ' . $term_name . ')');
                    //making it downloadable
                    // $pdf->Output($class_name.' ('.$session_name.' - '.$term_name.')', 'D');
                    $pdf->Output();
                }
            } else {
                echo "No result found";
            }
        } else {
            die($db->getError());
        }
    } else if ($term_id == 2) //Second term
    {
        // Check if result exist
        $db->query(
            "SELECT * FROM result_tbl 
            WHERE class_id = :class_id 
            AND session_id = :session_id 
            AND term_id = :term_id 
            LIMIT 1;
        "
        );
        $db->bind(':class_id', $class_id);
        $db->bind(':session_id', $session_id);
        $db->bind(':term_id', $term_id);
        if ($db->execute()) {
            if ($db->rowCount() > 0) {
                // Create PDF Object 
                $pdf = new PDF('P', 'mm', 'A4');
                $pdf->AliasNbPages();
                $pdf->AddPage();

                // Fetch each student result
                $db->query(
                    "SELECT * FROM students_tbl AS rt JOIN class_tbl ON class_tbl.class_id = rt.class_id  WHERE rt.class_id = :class_id;
                "
                );
                $db->bind(':class_id', $class_id);
                $db->execute();
                if ($db->rowCount() > 0) {
                    $result = $db->resultset();
                    foreach ($result as $row) {
                        $class_name = $row->class_name;
                        $sname = $row->sname;
                        $lname = $row->lname;
                        $oname = $row->oname;
                        $gender = $row->gender;
                        $admNo = $row->admNo;
                        $religion = $row->religion;
                        $passport = $row->passport;
                        //Add image logo
                        $pdf->Image('../uploads/img/logoPdf.png', 7, 7, 33, 34);
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
                        // $pdf ->Image('../uploads/img/logoPdf.png', 7,7,33,34);

                        //Student Information goes here
                        $pdf->SetFont('Arial', 'B', 15);
                        $pdf->Cell(190, 10, "$term_name REPORT SHEET $session_name SESSION ", 0, 1, 'C');
                        $pdf->ln(-3);
                        //Add Student image
                        //Controlling image
                        if (!empty($passport)) {
                            $pdf->Image($passport, 170, 30, 30, 30);
                        } else {
                            $pdf->Image('../uploads/student_image.jpg', 170, 30, 30, 30);
                        }
                        $pdf->ln(5);
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'ADMISSION NO.', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(40, 5, $admNo, 1, 0, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'NAME', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(70, 5, $sname . " " . $lname . " " . $oname, 1, 1, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'CLASS', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(40, 5, $class_name, 1, 0, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, "CLASS SIZE", 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(70, 5, $class_total, 1, 1, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'GENDER', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(40, 5, $gender, 1, 0, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'RELIGION', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
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
                        $db->query(
                            "SELECT * FROM result_tbl AS rs 
                            JOIN subject_tbl ON subject_tbl.subject_id = rs.subject_id 
                            WHERE admNo = :admNo 
                            AND session_id = :session_id 
                            AND term_id = :term_id;"
                        );
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $results = $db->resultset();

                            foreach ($results as $result) {
                                $subject_id = $result->subject_id;

                                $pdf->SetFont('Times', '', 10);
                                $pdf->Cell(75, 5, $result->subject_name, 1, 0, 'L');

                                // $pdf ->SetFont('Times','', 10);
                                // $pdf ->Cell(75,5, $result->subject_name, 1,0,'L');

                                if (($result->ca == 0) || ($result->ca == null) || empty($result->ca)) {
                                    $ca = "N/A";
                                } else {
                                    $ca = $result->ca;
                                }

                                if (($result->exam == 0) || ($result->exam == null) || empty($result->exam)) {
                                    $exam = "N/A";
                                } else {
                                    $exam = $result->exam;
                                }

                                $total = $result->total;
                                $grade = $result->grade;
                                $remark = $result->remark;

                                $pdf->Cell(10, 5, $ca, 1, 0, 'C');
                                $pdf->Cell(15, 5, $exam, 1, 0, 'C');
                                $pdf->Cell(15, 5, $total, 1, 0, 'C');

                                //Get the total score of the first term subject 
                                $db->query("SELECT * FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id AND subject_id = :subject_id;");
                                $db->bind(':admNo', $admNo);
                                $db->bind(':session_id', $session_id);
                                $db->bind(':term_id', 1);
                                $db->bind(':subject_id', $subject_id);
                                $db->execute();
                                $first_trm_num = $db->rowCount();
                                if ($first_trm_num > 0) {
                                    $result = $db->resultset();
                                    foreach ($result as $row) {
                                        $first_term = $row->total;
                                        if (($first_term == 0) || ($first_term == null)) {
                                            $pdf->Cell(20, 5, 'N/A', 1, 0, 'C');
                                        } else {
                                            $pdf->Cell(20, 5, $first_term, 1, 0, 'C');
                                        }
                                    }
                                } else {
                                    $error = "Null";
                                    $pdf->Cell(20, 5, $error, 1, 0, 'C');
                                }
                                $pdf->Cell(25, 5, $grade, 1, 0, 'C');
                                $pdf->Cell(30, 5, $remark, 1, 1, 'C');
                                $pdf->ln(0);
                            }
                        } else {
                            $error = "Result not found ";
                            $pdf->Cell(190, 5, $error, 1, 0, 'C');
                        }
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

                        //Getting total 
                        $db->query("SELECT SUM(total) AS total_sum FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $total = $db->single()->total_sum;
                            $pdf->SetFont('Times', 'B', 10);
                            $pdf->Cell(100, 5, "TOTAL ", 1, 0, 'R');
                            $pdf->Cell(15, 5, $total, 1, 0, 'C');
                        } else {
                            $pdf->Cell(15, 5, 'Null', 1, 0, 'C');
                        }

                        //Getting Firt term total
                        $db->query("SELECT SUM(total) AS first_total_sum FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', 1);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $first_term_total = $db->single()->first_total_sum;
                            $pdf->Cell(20, 5, $first_term_total, 1, 0, 'C');
                        } else {
                            $pdf->Cell(20, 5, 'Null', 1, 0, 'C');
                        }
                        //Getting  average
                        $db->query("SELECT AVG(total) AS average FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $average = $db->single()->average;
                            //TERM AVERAGE
                            $pdf->Cell(55, 5, 'AVERAGE = ' . round(($average), 2, PHP_ROUND_HALF_UP), 1, 1, 'L');
                        } else {
                            $pdf->Cell(60, 5, 'Null', 1, 1, 'L');
                        }
                        $pdf->Cell(190, 5, '', 1, 1, 'C');
                        $pdf->Cell(190, 5, '', 1, 1, 'C');
                        $pdf->Cell(190, 5, 'GRADES:  A1[80-100] B2[75-79] B3[70-74]  C4[65-69]  C5[60-64]  C6[50-59] D7[45-49] E8[40-44]  F9[0-39]', 1, 1, 'C');
                        $pdf ->Cell(190,5,'KEY NOTE: N/A [Not Available]',1,1,'C');
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
                        $db->query("SELECT * FROM comments_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $results = $db->single();

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

                            //CLASS TEACHER AND PRINCIPAL COMMENTS
                            $pdf->SetFont('Times', 'B', 9);
                            $pdf->Cell(53, 5, "CLASS TEACHER'S NAME", 1, 0, 'L');
                            $pdf->Cell(80, 5, $class_teacher_name, 1, 1, 'L');
                            $pdf->Cell(53, 5, "CLASS TEACHER'S COMMENT", 1, 0, 'L');
                            $pdf->Cell(80, 5, $teacher_comment, 1, 1, 'L');
                            $pdf->Cell(53, 5, 'PRINCIPAL COMMENT', 1, 0, 'L');
                            $pdf->Cell(80, 5, $principal_comment, 1, 1, 'L');
                            $pdf->Cell(53, 5, 'NEXT TERM BEGIN', 1, 0, 'L');

                            //Getting Next Term begins from the database
                            $db->query("SELECT * FROM next_term_tbl");
                            $db->execute();
                            if ($db->rowCount() > 0) {
                                $result = $db->single();
                                $pdf->Cell(80, 5, $result->next_term, 1, 1, 'L');
                            } else {
                                $error = "Not Schedule";
                                $pdf->Cell(80, 5, $error, 1, 1, 'L');
                            }
                        } else {
                            $error = " No Teacher/Principal comments";
                            $pdf->Cell(190, 5, $error, 1, 0, 'C');
                        }
                        //SCHOOL STAMP
                        //$pdf ->Image('img/signature.jpeg', 145,230,55,28);
                        $pdf->ln(30);
                    }

                    //Outputting the pdf file
                    $pdf->SetTitle($class_name . ' (' . $session_name . ' - ' . $term_name . ')');
                    //making it downloadable
                    // $pdf->Output($class_name.' ('.$session_name.' - '.$term_name.')', 'D');
                    $pdf->Output();
                }
            } else {
                echo "No result found";
            }
        } else {
            die($db->getError());
        }
    } else if ($term_id == 3) {
        // Check if result exist
        $db->query(
            "SELECT * FROM result_tbl 
            WHERE class_id = :class_id 
            AND session_id = :session_id 
            AND term_id = :term_id 
            LIMIT 1;
        "
        );
        $db->bind(':class_id', $class_id);
        $db->bind(':session_id', $session_id);
        $db->bind(':term_id', $term_id);
        if ($db->execute()) {
            if ($db->rowCount() > 0) {
                // Create PDF Object 
                $pdf = new PDF('P', 'mm', 'A4');
                $pdf->AliasNbPages();
                $pdf->AddPage();

                // Fetch each student result
                $db->query(
                    "SELECT * FROM students_tbl AS rt JOIN class_tbl ON class_tbl.class_id = rt.class_id  WHERE rt.class_id = :class_id;
                "
                );
                $db->bind(':class_id', $class_id);
                $db->execute();
                if ($db->rowCount() > 0) {
                    $result = $db->resultset();
                    foreach ($result as $row) {
                        $class_name = $row->class_name;
                        $sname = $row->sname;
                        $lname = $row->lname;
                        $oname = $row->oname;
                        $gender = $row->gender;
                        $admNo = $row->admNo;
                        $religion = $row->religion;
                        $passport = $row->passport;
                        //Add image logo
                        $pdf->Image('../uploads/img/logoPdf.png', 7, 7, 33, 34);
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

                        //Student Information goes here
                        $pdf->SetFont('Arial', 'B', 15);
                        $pdf->Cell(190, 10, "$term_name REPORT SHEET $session_name SESSION ", 0, 1, 'C');
                        $pdf->ln(-3);
                        //Add Student image
                        //Controlling image
                        if (!empty($passport)) {
                            $pdf->Image($passport, 170, 30, 30, 30);
                        } else {
                            $pdf->Image('../uploads/student_image.jpg', 170, 30, 30, 30);
                        }
                        $pdf->ln(5);
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'ADMISSION NO.', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(40, 5, $admNo, 1, 0, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'NAME', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(70, 5, $sname . " " . $lname . " " . $oname, 1, 1, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'CLASS', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(40, 5, $class_name, 1, 0, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, "CLASS SIZE", 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(70, 5, $class_total, 1, 1, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'GENDER', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(40, 5, $gender, 1, 0, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'RELIGION', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(70, 5, $religion, 1, 1, 'L');
                        $pdf->ln(10);
                        //SUBJECTS  header
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(75, 5, 'SUBJECTS', 1, 0, 'L');
                        $pdf->Cell(7, 5, 'CA', 1, 0, 'C');
                        $pdf->Cell(12, 5, 'EXAM', 1, 0, 'C');
                        $pdf->Cell(13, 5, 'TOTAL', 1, 0, 'C');
                        $pdf->Cell(18, 5, "2nd TERM", 1, 0, 'C');
                        $pdf->Cell(17, 5, '1st TERM', 1, 0, 'C');
                        $pdf->Cell(13, 5, 'S-Avg', 1, 0, 'C');
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
                        $db->query(
                            "SELECT * FROM result_tbl AS rs 
                            JOIN subject_tbl ON subject_tbl.subject_id = rs.subject_id 
                            WHERE admNo = :admNo 
                            AND session_id = :session_id 
                            AND term_id = :term_id;"
                        );
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $results = $db->resultset();

                            foreach ($results as $result) {
                                $subject_id = $result->subject_id;

                                //Row ENGLISH LANGUAGE
                                $pdf->SetFont('Times', '', 10);
                                $pdf->Cell(75, 5, $result->subject_name, 1, 0, 'L');

                                if (($result->ca == 0) || ($result->ca == null) || empty($result->ca)) {
                                    $ca = "N/A";
                                } else {
                                    $ca = $result->ca;
                                }

                                if (($result->exam == 0) || ($result->exam == null) || empty($result->exam)) {
                                    $exam = "N/A";
                                } else {
                                    $exam = $result->exam;
                                }
                                $total = $result->total;
                                $grade = $result->grade;
                                $remark = $result->remark;

                                $pdf->Cell(7, 5, $ca, 1, 0, 'C');
                                $pdf->Cell(12, 5, $exam, 1, 0, 'C');
                                $pdf->Cell(13, 5, $total, 1, 0, 'C');
                                //Getting the overall total of each subject and adding to second term column
                                $db->query("SELECT * FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id AND subject_id = :subject_id;");
                                $db->bind(':admNo', $admNo);
                                $db->bind(':session_id', $session_id);
                                $db->bind(':term_id', 2);
                                $db->bind(':subject_id', $subject_id);
                                $db->execute();
                                if ($db->rowCount() > 0) {
                                    $result = $db->resultset();
                                    foreach ($result as $row) {
                                        $pdf->Cell(18, 5, $row->total, 1, 0, 'C');
                                    }
                                } else {
                                    $pdf->Cell(18, 5, 'Null', 1, 0, 'C');
                                }

                                //Getting the overall total of each subject and adding to first term column
                                $db->query("SELECT * FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id AND subject_id = :subject_id;");
                                $db->bind(':admNo', $admNo);
                                $db->bind(':session_id', $session_id);
                                $db->bind(':term_id', 1);
                                $db->bind(':subject_id', $subject_id);
                                $db->execute();
                                if ($db->rowCount() > 0) {
                                    $result = $db->resultset();
                                    foreach ($result as $row) {
                                        $pdf->Cell(17, 5, $row->total, 1, 0, 'C');
                                    }
                                } else {
                                    $pdf->Cell(17, 5, 'Null', 1, 0, 'C');
                                }

                                //sessional average for each subjects column TODO
                                $db->query("SELECT AVG(total) AS sess_avg_sum FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND subject_id = :subject_id");
                                $db->bind(':admNo', $admNo);
                                $db->bind(':session_id', $session_id);
                                $db->bind(':subject_id', $subject_id);
                                $db->execute();
                                if ($db->rowCount() > 0) {
                                    $result = $db->single();
                                    // while($sess_avg_fetch = mysqli_fetch_assoc($sess_avg_query))
                                    // {
                                    //DIVIDING BY 3
                                    $pdf->Cell(13, 5, round(($result->sess_avg_sum), 2, PHP_ROUND_HALF_UP), 1, 0, 'C');
                                    // }    
                                } else {
                                    $pdf->Cell(13, 5, 'Nul', 1, 0, 'C');
                                }
                                $pdf->Cell(10, 5, $grade, 1, 0, 'C');
                                $pdf->Cell(25, 5, $remark, 1, 1, 'C');
                                $pdf->ln(0);
                            }
                        } else {
                            $pdf->Cell(190, 5, 'Result not found', 1, 0, 'C');
                        }
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

                        //Getting total
                        $db->query("SELECT SUM(total) AS total_sum FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $total = $db->single()->total_sum;
                            $pdf->SetFont('Times', 'B', 10);
                            $pdf->Cell(94, 5, "TOTAL ", 1, 0, 'R');
                            $pdf->Cell(13, 5, $total, 1, 0, 'C');
                        } else {
                            $pdf->SetFont('Times', 'B', 10);
                            $pdf->Cell(94, 5, "TOTAL ", 1, 0, 'R');
                            $pdf->Cell(13, 5, 'Nul', 1, 0, 'C');
                        }

                        //Getting Second term total
                        $db->query("SELECT SUM(total) AS second_term_total FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', 2);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $second_term_total = $db->single()->second_term_total;
                            $pdf->Cell(18, 5, $second_term_total, 1, 0, 'C');
                        } else {
                            $pdf->Cell(18, 5, 'Nul', 1, 0, 'C');
                        }

                        //Getting Firt term total
                        $db->query("SELECT SUM(total) AS first_total_sum FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', 1);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $first_term_total = $db->single()->first_total_sum;
                            $pdf->Cell(17, 5, $first_term_total, 1, 0, 'C');
                        } else {
                            $pdf->Cell(17, 5, 'Nul', 1, 0, 'C');
                        }

                        //Getting sessional total (1st + 2nd + 3rd)/3
                        $db->query("SELECT AVG(total) AS sessional_average_sum FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $sessional_term_average = $db->single()->sessional_average_sum;
                            $sess_avg = round($sessional_term_average, 2, PHP_ROUND_HALF_UP);
                            $pdf->Cell(13, 5, $sess_avg, 1, 0, 'C');
                        } else {
                            $pdf->Cell(13, 5, 'Nul', 1, 0, 'C');
                        }

                        //TERM AVERAGE
                        $db->query("SELECT AVG(total) AS average FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $average = $db->single()->average;
                            $pdf->Cell(35, 5, 'AVERAGE = ' . round(($average), 2, PHP_ROUND_HALF_UP), 1, 1, 'C');
                        } else {
                            $pdf->Cell(35, 5, 'Nul', 1, 1, 'C');
                        }

                        $pdf->Cell(190, 5, '', 1, 1, 'C');
                        $pdf->Cell(190, 5, '', 1, 1, 'C');
                        $pdf->Cell(190, 5, 'GRADES:  A1[80-100] B2[75-79] B3[70-74]  C4[65-69]  C5[60-64]  C6[50-59] D7[45-49] E8[40-44]  F9[0-39]', 1, 1, 'C');
                        $pdf->Cell(190, 5, 'KEY NOTE: S-Avg [Sessional Average] GRD [Grade] REM [Remark] N/A [Not Available]', 1, 1, 'C');
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
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $results = $db->single();

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

                            //CLASS TEACHER AND PRINCIPAL COMMENTS
                            $pdf->SetFont('Times', 'B', 9);
                            $pdf->Cell(53, 5, "CLASS TEACHER'S NAME", 1, 0, 'L');
                            $pdf->Cell(80, 5, $class_teacher_name, 1, 1, 'L');
                            $pdf->Cell(53, 5, "CLASS TEACHER'S COMMENT", 1, 0, 'L');
                            $pdf->Cell(80, 5, $teacher_comment, 1, 1, 'L');
                            $pdf->Cell(53, 5, 'PRINCIPAL COMMENT', 1, 0, 'L');
                            $pdf->Cell(80, 5, $principal_comment, 1, 1, 'L');
                            $pdf->Cell(53, 5, 'NEXT TERM BEGIN', 1, 0, 'L');

                            //Getting Next Term begins from the database
                            $db->query("SELECT * FROM next_term_tbl");
                            $db->execute();
                            if ($db->rowCount() > 0) {
                                $result = $db->single();
                                $pdf->Cell(80, 5, $result->next_term, 1, 1, 'L');
                            } else {
                                $error = "Not Schedule";
                                $pdf->Cell(80, 5, $error, 1, 1, 'L');
                            }
                        } else {
                            $error = " No Teacher/Principal comments";
                            $pdf->Cell(190, 5, $error, 1, 0, 'C');
                        }
                        //SCHOOL STAMP
                        //$pdf ->Image('img/signature.jpeg', 145,240,55,28);
                        $pdf->ln(30);
                    }
                    //Outputting the pdf file
                    $pdf->SetTitle($class_name . ' (' . $session_name . ' - ' . $term_name . ')');
                    //making it downloadable
                    // $pdf->Output($class_name.' ('.$session_name.' - '.$term_name.')', 'D');
                    $pdf->Output();
                }
            } else {
                echo "No result found";
            }
        } else {
            die($db->getError());
        }
    } else {
        echo "Term is annulled!";
    }
    $db->Disconect();
}


/*******************DOWNLOADABLE PDF ******************************************/
if (isset($_POST['download_class_btn'])) {
    $class_id = $_POST['class_id'];
    $session_id = $_POST['session_id'];
    $term_id = $_POST['term_id'];

    $db = new Database();
    // Get class total
    $db->query("SELECT * FROM students_tbl WHERE class_id = :class_id;");
    $db->bind(':class_id', $class_id);
    $db->execute();
    if ($db->rowCount() > 0) {
        $class_total = $db->rowCount();
    } else {
        $class_total = "No class total";
    }
    // Get class teacher  name
    $db->query("SELECT sname, fname, oname FROM class_tbl AS cs JOIN staff_tbl ON staff_tbl.staff_id = cs.instructor_id WHERE class_id = :class_id;");
    $db->bind(':class_id', $class_id);
    $db->execute();
    if ($db->rowCount() > 0) {
        $result = $db->single();
        $class_teacher_name =  "$result->fname $result->sname $result->oname";
    } else {
        $class_teacher_name = "N/A";
    }
    // Get session name and term name
    $db->query(
        "SELECT * FROM tbl_year_session AS tbs 
        JOIN session_tbl ON session_tbl.session_id = tbs.session_id
        JOIN term_tbl ON term_tbl.term_id = tbs.term_id
        WHERE tbs.session_id = :session_id AND tbs.term_id = :term_id;
    "
    );
    $db->bind(':session_id', $session_id);
    $db->bind(':term_id', $term_id);
    $db->execute();
    if ($db->rowCount() > 0) {
        $result = $db->single();
        $session_name = $result->session_name;
        $term_name = $result->term_name;
    } else {
        $session_name = "N/A";
        $term_name = "N/A";
    }

    if ($term_id == 1) //First term
    {
        // Check if result exist
        $db->query(
            "SELECT * FROM result_tbl 
            WHERE class_id = :class_id 
            AND session_id = :session_id 
            AND term_id = :term_id 
            LIMIT 1;
        "
        );
        $db->bind(':class_id', $class_id);
        $db->bind(':session_id', $session_id);
        $db->bind(':term_id', $term_id);
        if ($db->execute()) {
            if ($db->rowCount() > 0) {
                // Create PDF Object 
                $pdf = new PDF('P', 'mm', 'A4');
                $pdf->AliasNbPages();
                $pdf->AddPage();

                // Fetch each student result
                $db->query(
                    "SELECT * FROM students_tbl AS rt JOIN class_tbl ON class_tbl.class_id = rt.class_id  WHERE rt.class_id = :class_id;
                "
                );
                $db->bind(':class_id', $class_id);
                $db->execute();
                if ($db->rowCount() > 0) {
                    $result = $db->resultset();
                    foreach ($result as $row) {
                        $class_name = $row->class_name;
                        $sname = $row->sname;
                        $lname = $row->lname;
                        $oname = $row->oname;
                        $gender = $row->gender;
                        $admNo = $row->admNo;
                        $religion = $row->religion;
                        $passport = $row->passport;
                        //Add image logo
                        // $pdf ->Image('../uploads/img/logoPdf.png', 7,7,33,34);
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
                        $pdf->Image('../uploads/img/logoPdf.png', 7, 7, 33, 34);
                        $pdf->ln();
                        //Student Information goes here
                        $pdf->SetFont('Arial', 'B', 15);
                        $pdf->Cell(190, 10, "$term_name REPORT SHEET $session_name SESSION ", 0, 1, 'C');
                        $pdf->ln(-3);
                        //Add Student image
                        if (!empty($passport)) {
                            $pdf->Image($passport, 170, 30, 30, 30);
                        }
                        $pdf->Image('../uploads/student_image.jpg', 170, 30, 30, 30);
                        $pdf->ln(5);
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'ADMISSION NO.', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(40, 5, $admNo, 1, 0, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'NAME', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(70, 5, $sname . " " . $lname . " " . $oname, 1, 1, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'CLASS', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(40, 5, $class_name, 1, 0, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, "CLASS SIZE", 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(70, 5, $class_total, 1, 1, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'GENDER', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(40, 5, $gender, 1, 0, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'RELIGION', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
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
                        $db->query(
                            "SELECT * FROM result_tbl AS rs 
                            JOIN subject_tbl ON subject_tbl.subject_id = rs.subject_id 
                            WHERE admNo = :admNo 
                            AND session_id = :session_id 
                            AND term_id = :term_id;"
                        );
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $results = $db->resultset();

                            foreach ($results as $result) {
                                $pdf->SetFont('Times', '', 10);
                                $pdf->Cell(75, 5, $result->subject_name, 1, 0, 'L');

                                if (($result->ca == 0) || ($result->ca == null) || empty($result->ca)) {
                                    $ca = "N/A";
                                } else {
                                    $ca = $result->ca;
                                }

                                if (($result->exam == 0) || ($result->exam == null) || empty($result->exam)) {
                                    $exam = "N/A";
                                } else {
                                    $exam = $result->exam;
                                }

                                $total = $result->total;
                                $grade = $result->grade;
                                $remark = $result->remark;

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
                        //Getting total 
                        $db->query("SELECT SUM(total) AS total_sum FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        $db->execute();
                        $total = $db->single()->total_sum;
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(110, 5, 'TOTAL = ', 1, 0, 'R');
                        //$pdf ->Cell(15,5,'',1,0,'C');
                        //$pdf ->Cell(20,5,'',1,0,'C');
                        $pdf->Cell(20, 5, $total, 1, 0, 'C');

                        //Getting  average
                        $db->query("SELECT AVG(total) AS average FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        $db->execute();
                        $average = $db->single()->average;
                        //TERM AVERAGE
                        $pdf->Cell(60, 5, 'AVERAGE = ' . round(($average), 2, PHP_ROUND_HALF_UP), 1, 1, 'L');
                        $pdf->Cell(190, 5, '', 1, 1, 'C');
                        $pdf->Cell(190, 5, '', 1, 1, 'C');
                        $pdf->Cell(190, 5, 'GRADES:  A1[80-100] B2[75-79] B3[70-74]  C4[65-69]  C5[60-64]  C6[50-59] D7[45-49] E8[40-44]  F9[0-39]', 1, 1, 'C');
                        $pdf ->Cell(190,5,'KEY NOTE: N/A [Not Available]',1,1,'C');
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
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $results = $db->single();

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

                            //CLASS TEACHER AND PRINCIPAL COMMENTS
                            $pdf->SetFont('Times', 'B', 9);
                            $pdf->Cell(53, 5, "CLASS TEACHER'S NAME", 1, 0, 'L');
                            $pdf->Cell(80, 5, $class_teacher_name, 1, 1, 'L');
                            $pdf->Cell(53, 5, "CLASS TEACHER'S COMMENT", 1, 0, 'L');
                            $pdf->Cell(80, 5, $teacher_comment, 1, 1, 'L');
                            $pdf->Cell(53, 5, 'PRINCIPAL COMMENT', 1, 0, 'L');
                            $pdf->Cell(80, 5, $principal_comment, 1, 1, 'L');
                            $pdf->Cell(53, 5, 'NEXT TERM BEGIN', 1, 0, 'L');

                            //Getting Next Term begins from the database
                            $db->query("SELECT * FROM next_term_tbl");
                            $db->execute();
                            if ($db->rowCount() > 0) {
                                $result = $db->single();
                                $pdf->Cell(80, 5, $result->next_term, 1, 1, 'L');
                            } else {
                                $error = "Not Schedule";
                                $pdf->Cell(80, 5, $error, 1, 1, 'L');
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
                    $downloadPDF = $class_name.' ('.$session_name.' - '.$term_name.')';
                    $pdf->Output($downloadPDF.'.pdf', 'D');
                }
            } else {
                echo "No result found";
            }
        } else {
            die($db->getError());
        }
    } else if ($term_id == 2) //Second term
    {
        // Check if result exist
        $db->query(
            "SELECT * FROM result_tbl 
            WHERE class_id = :class_id 
            AND session_id = :session_id 
            AND term_id = :term_id 
            LIMIT 1;
        "
        );
        $db->bind(':class_id', $class_id);
        $db->bind(':session_id', $session_id);
        $db->bind(':term_id', $term_id);
        if ($db->execute()) {
            if ($db->rowCount() > 0) {
                // Create PDF Object 
                $pdf = new PDF('P', 'mm', 'A4');
                $pdf->AliasNbPages();
                $pdf->AddPage();

                // Fetch each student result
                $db->query(
                    "SELECT * FROM students_tbl AS rt JOIN class_tbl ON class_tbl.class_id = rt.class_id  WHERE rt.class_id = :class_id;
                "
                );
                $db->bind(':class_id', $class_id);
                $db->execute();
                if ($db->rowCount() > 0) {
                    $result = $db->resultset();
                    foreach ($result as $row) {
                        $class_name = $row->class_name;
                        $sname = $row->sname;
                        $lname = $row->lname;
                        $oname = $row->oname;
                        $gender = $row->gender;
                        $admNo = $row->admNo;
                        $religion = $row->religion;
                        $passport = $row->passport;
                        //Add image logo
                        $pdf->Image('../uploads/img/logoPdf.png', 7, 7, 33, 34);
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
                        // $pdf ->Image('../uploads/img/logoPdf.png', 7,7,33,34);

                        //Student Information goes here
                        $pdf->SetFont('Arial', 'B', 15);
                        $pdf->Cell(190, 10, "$term_name REPORT SHEET $session_name SESSION ", 0, 1, 'C');
                        $pdf->ln(-3);
                        //Add Student image
                        //Controlling image
                        if (!empty($passport)) {
                            $pdf->Image($passport, 170, 30, 30, 30);
                        } else {
                            $pdf->Image('../uploads/student_image.jpg', 170, 30, 30, 30);
                        }
                        $pdf->ln(5);
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'ADMISSION NO.', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(40, 5, $admNo, 1, 0, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'NAME', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(70, 5, $sname . " " . $lname . " " . $oname, 1, 1, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'CLASS', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(40, 5, $class_name, 1, 0, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, "CLASS SIZE", 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(70, 5, $class_total, 1, 1, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'GENDER', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(40, 5, $gender, 1, 0, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'RELIGION', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
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
                        $db->query(
                            "SELECT * FROM result_tbl AS rs 
                            JOIN subject_tbl ON subject_tbl.subject_id = rs.subject_id 
                            WHERE admNo = :admNo 
                            AND session_id = :session_id 
                            AND term_id = :term_id;"
                        );
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $results = $db->resultset();

                            foreach ($results as $result) {
                                $subject_id = $result->subject_id;

                                $pdf->SetFont('Times', '', 10);
                                $pdf->Cell(75, 5, $result->subject_name, 1, 0, 'L');

                                // $pdf ->SetFont('Times','', 10);
                                // $pdf ->Cell(75,5, $result->subject_name, 1,0,'L');

                                if (($result->ca == 0) || ($result->ca == null) || empty($result->ca)) {
                                    $ca = "N/A";
                                } else {
                                    $ca = $result->ca;
                                }

                                if (($result->exam == 0) || ($result->exam == null) || empty($result->exam)) {
                                    $exam = "N/A";
                                } else {
                                    $exam = $result->exam;
                                }

                                $total = $result->total;
                                $grade = $result->grade;
                                $remark = $result->remark;

                                $pdf->Cell(10, 5, $ca, 1, 0, 'C');
                                $pdf->Cell(15, 5, $exam, 1, 0, 'C');
                                $pdf->Cell(15, 5, $total, 1, 0, 'C');

                                //Get the total score of the first term subject 
                                $db->query("SELECT * FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id AND subject_id = :subject_id;");
                                $db->bind(':admNo', $admNo);
                                $db->bind(':session_id', $session_id);
                                $db->bind(':term_id', 1);
                                $db->bind(':subject_id', $subject_id);
                                $db->execute();
                                $first_trm_num = $db->rowCount();
                                if ($first_trm_num > 0) {
                                    $result = $db->resultset();
                                    foreach ($result as $row) {
                                        $first_term = $row->total;
                                        if (($first_term == 0) || ($first_term == null)) {
                                            $pdf->Cell(20, 5, 'N/A', 1, 0, 'C');
                                        } else {
                                            $pdf->Cell(20, 5, $first_term, 1, 0, 'C');
                                        }
                                    }
                                } else {
                                    $error = "Null";
                                    $pdf->Cell(20, 5, $error, 1, 0, 'C');
                                }
                                $pdf->Cell(25, 5, $grade, 1, 0, 'C');
                                $pdf->Cell(30, 5, $remark, 1, 1, 'C');
                                $pdf->ln(0);
                            }
                        } else {
                            $error = "Result not found ";
                            $pdf->Cell(190, 5, $error, 1, 0, 'C');
                        }
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

                        //Getting total 
                        $db->query("SELECT SUM(total) AS total_sum FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $total = $db->single()->total_sum;
                            $pdf->SetFont('Times', 'B', 10);
                            $pdf->Cell(100, 5, "TOTAL ", 1, 0, 'R');
                            $pdf->Cell(15, 5, $total, 1, 0, 'C');
                        } else {
                            $pdf->Cell(15, 5, 'Null', 1, 0, 'C');
                        }

                        //Getting Firt term total
                        $db->query("SELECT SUM(total) AS first_total_sum FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', 1);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $first_term_total = $db->single()->first_total_sum;
                            $pdf->Cell(20, 5, $first_term_total, 1, 0, 'C');
                        } else {
                            $pdf->Cell(20, 5, 'Null', 1, 0, 'C');
                        }
                        //Getting  average
                        $db->query("SELECT AVG(total) AS average FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $average = $db->single()->average;
                            //TERM AVERAGE
                            $pdf->Cell(55, 5, 'AVERAGE = ' . round(($average), 2, PHP_ROUND_HALF_UP), 1, 1, 'L');
                        } else {
                            $pdf->Cell(60, 5, 'Null', 1, 1, 'L');
                        }
                        $pdf->Cell(190, 5, '', 1, 1, 'C');
                        $pdf->Cell(190, 5, '', 1, 1, 'C');
                        $pdf->Cell(190, 5, 'GRADES:  A1[80-100] B2[75-79] B3[70-74]  C4[65-69]  C5[60-64]  C6[50-59] D7[45-49] E8[40-44]  F9[0-39]', 1, 1, 'C');
                        $pdf ->Cell(190,5,'KEY NOTE: N/A [Not Available]',1,1,'C');
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
                        $db->query("SELECT * FROM comments_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $results = $db->single();

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

                            //CLASS TEACHER AND PRINCIPAL COMMENTS
                            $pdf->SetFont('Times', 'B', 9);
                            $pdf->Cell(53, 5, "CLASS TEACHER'S NAME", 1, 0, 'L');
                            $pdf->Cell(80, 5, $class_teacher_name, 1, 1, 'L');
                            $pdf->Cell(53, 5, "CLASS TEACHER'S COMMENT", 1, 0, 'L');
                            $pdf->Cell(80, 5, $teacher_comment, 1, 1, 'L');
                            $pdf->Cell(53, 5, 'PRINCIPAL COMMENT', 1, 0, 'L');
                            $pdf->Cell(80, 5, $principal_comment, 1, 1, 'L');
                            $pdf->Cell(53, 5, 'NEXT TERM BEGIN', 1, 0, 'L');

                            //Getting Next Term begins from the database
                            $db->query("SELECT * FROM next_term_tbl");
                            $db->execute();
                            if ($db->rowCount() > 0) {
                                $result = $db->single();
                                $pdf->Cell(80, 5, $result->next_term, 1, 1, 'L');
                            } else {
                                $error = "Not Schedule";
                                $pdf->Cell(80, 5, $error, 1, 1, 'L');
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
                    $downloadPDF = $class_name.' ('.$session_name.' - '.$term_name.')';
                    $pdf->Output($downloadPDF.'.pdf', 'D');
                }
            } else {
                echo "No result found";
            }
        } else {
            die($db->getError());
        }
    } else if ($term_id == 3) {
        // Check if result exist
        $db->query(
            "SELECT * FROM result_tbl 
            WHERE class_id = :class_id 
            AND session_id = :session_id 
            AND term_id = :term_id 
            LIMIT 1;
        "
        );
        $db->bind(':class_id', $class_id);
        $db->bind(':session_id', $session_id);
        $db->bind(':term_id', $term_id);
        if ($db->execute()) {
            if ($db->rowCount() > 0) {
                // Create PDF Object 
                $pdf = new PDF('P', 'mm', 'A4');
                $pdf->AliasNbPages();
                $pdf->AddPage();

                // Fetch each student result
                $db->query(
                    "SELECT * FROM students_tbl AS rt JOIN class_tbl ON class_tbl.class_id = rt.class_id  WHERE rt.class_id = :class_id;
                "
                );
                $db->bind(':class_id', $class_id);
                $db->execute();
                if ($db->rowCount() > 0) {
                    $result = $db->resultset();
                    foreach ($result as $row) {
                        $class_name = $row->class_name;
                        $sname = $row->sname;
                        $lname = $row->lname;
                        $oname = $row->oname;
                        $gender = $row->gender;
                        $admNo = $row->admNo;
                        $religion = $row->religion;
                        $passport = $row->passport;
                        //Add image logo
                        $pdf->Image('../uploads/img/logoPdf.png', 7, 7, 33, 34);
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

                        //Student Information goes here
                        $pdf->SetFont('Arial', 'B', 15);
                        $pdf->Cell(190, 10, "$term_name REPORT SHEET $session_name SESSION ", 0, 1, 'C');
                        $pdf->ln(-3);
                        //Add Student image
                        //Controlling image
                        if (!empty($passport)) {
                            $pdf->Image($passport, 170, 30, 30, 30);
                        } else {
                            $pdf->Image('../uploads/student_image.jpg', 170, 30, 30, 30);
                        }
                        $pdf->ln(5);
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'ADMISSION NO.', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(40, 5, $admNo, 1, 0, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'NAME', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(70, 5, $sname . " " . $lname . " " . $oname, 1, 1, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'CLASS', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(40, 5, $class_name, 1, 0, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, "CLASS SIZE", 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(70, 5, $class_total, 1, 1, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'GENDER', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(40, 5, $gender, 1, 0, 'L');
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(40, 5, 'RELIGION', 1, 0, 'L');
                        $pdf->SetFont('Times', '', 10);
                        $pdf->Cell(70, 5, $religion, 1, 1, 'L');
                        $pdf->ln(10);
                        //SUBJECTS  header
                        $pdf->SetFont('Times', 'B', 10);
                        $pdf->Cell(75, 5, 'SUBJECTS', 1, 0, 'L');
                        $pdf->Cell(7, 5, 'CA', 1, 0, 'C');
                        $pdf->Cell(12, 5, 'EXAM', 1, 0, 'C');
                        $pdf->Cell(13, 5, 'TOTAL', 1, 0, 'C');
                        $pdf->Cell(18, 5, "2nd TERM", 1, 0, 'C');
                        $pdf->Cell(17, 5, '1st TERM', 1, 0, 'C');
                        $pdf->Cell(13, 5, 'S-Avg', 1, 0, 'C');
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
                        $db->query(
                            "SELECT * FROM result_tbl AS rs 
                            JOIN subject_tbl ON subject_tbl.subject_id = rs.subject_id 
                            WHERE admNo = :admNo 
                            AND session_id = :session_id 
                            AND term_id = :term_id;"
                        );
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $results = $db->resultset();

                            foreach ($results as $result) {
                                $subject_id = $result->subject_id;

                                //Row ENGLISH LANGUAGE
                                $pdf->SetFont('Times', '', 10);
                                $pdf->Cell(75, 5, $result->subject_name, 1, 0, 'L');

                                if (($result->ca == 0) || ($result->ca == null) || empty($result->ca)) {
                                    $ca = "N/A";
                                } else {
                                    $ca = $result->ca;
                                }

                                if (($result->exam == 0) || ($result->exam == null) || empty($result->exam)) {
                                    $exam = "N/A";
                                } else {
                                    $exam = $result->exam;
                                }
                                $total = $result->total;
                                $grade = $result->grade;
                                $remark = $result->remark;

                                $pdf->Cell(7, 5, $ca, 1, 0, 'C');
                                $pdf->Cell(12, 5, $exam, 1, 0, 'C');
                                $pdf->Cell(13, 5, $total, 1, 0, 'C');
                                //Getting the overall total of each subject and adding to second term column
                                $db->query("SELECT * FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id AND subject_id = :subject_id;");
                                $db->bind(':admNo', $admNo);
                                $db->bind(':session_id', $session_id);
                                $db->bind(':term_id', 2);
                                $db->bind(':subject_id', $subject_id);
                                $db->execute();
                                if ($db->rowCount() > 0) {
                                    $result = $db->resultset();
                                    foreach ($result as $row) {
                                        $pdf->Cell(18, 5, $row->total, 1, 0, 'C');
                                    }
                                } else {
                                    $pdf->Cell(18, 5, 'Null', 1, 0, 'C');
                                }

                                //Getting the overall total of each subject and adding to first term column
                                $db->query("SELECT * FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id AND subject_id = :subject_id;");
                                $db->bind(':admNo', $admNo);
                                $db->bind(':session_id', $session_id);
                                $db->bind(':term_id', 1);
                                $db->bind(':subject_id', $subject_id);
                                $db->execute();
                                if ($db->rowCount() > 0) {
                                    $result = $db->resultset();
                                    foreach ($result as $row) {
                                        $pdf->Cell(17, 5, $row->total, 1, 0, 'C');
                                    }
                                } else {
                                    $pdf->Cell(17, 5, 'Null', 1, 0, 'C');
                                }

                                //sessional average for each subjects column TODO
                                $db->query("SELECT AVG(total) AS sess_avg_sum FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND subject_id = :subject_id");
                                $db->bind(':admNo', $admNo);
                                $db->bind(':session_id', $session_id);
                                $db->bind(':subject_id', $subject_id);
                                $db->execute();
                                if ($db->rowCount() > 0) {
                                    $result = $db->single();
                                    // while($sess_avg_fetch = mysqli_fetch_assoc($sess_avg_query))
                                    // {
                                    //DIVIDING BY 3
                                    $pdf->Cell(13, 5, round(($result->sess_avg_sum), 2, PHP_ROUND_HALF_UP), 1, 0, 'C');
                                    // }    
                                } else {
                                    $pdf->Cell(13, 5, 'Nul', 1, 0, 'C');
                                }
                                $pdf->Cell(10, 5, $grade, 1, 0, 'C');
                                $pdf->Cell(25, 5, $remark, 1, 1, 'C');
                                $pdf->ln(0);
                            }
                        } else {
                            $pdf->Cell(190, 5, 'Result not found', 1, 0, 'C');
                        }
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

                        //Getting total
                        $db->query("SELECT SUM(total) AS total_sum FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $total = $db->single()->total_sum;
                            $pdf->SetFont('Times', 'B', 10);
                            $pdf->Cell(94, 5, "TOTAL ", 1, 0, 'R');
                            $pdf->Cell(13, 5, $total, 1, 0, 'C');
                        } else {
                            $pdf->SetFont('Times', 'B', 10);
                            $pdf->Cell(94, 5, "TOTAL ", 1, 0, 'R');
                            $pdf->Cell(13, 5, 'Nul', 1, 0, 'C');
                        }

                        //Getting Second term total
                        $db->query("SELECT SUM(total) AS second_term_total FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', 2);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $second_term_total = $db->single()->second_term_total;
                            $pdf->Cell(18, 5, $second_term_total, 1, 0, 'C');
                        } else {
                            $pdf->Cell(18, 5, 'Nul', 1, 0, 'C');
                        }

                        //Getting Firt term total
                        $db->query("SELECT SUM(total) AS first_total_sum FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', 1);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $first_term_total = $db->single()->first_total_sum;
                            $pdf->Cell(17, 5, $first_term_total, 1, 0, 'C');
                        } else {
                            $pdf->Cell(17, 5, 'Nul', 1, 0, 'C');
                        }

                        //Getting sessional total (1st + 2nd + 3rd)/3
                        $db->query("SELECT AVG(total) AS sessional_average_sum FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $sessional_term_average = $db->single()->sessional_average_sum;
                            $sess_avg = round($sessional_term_average, 2, PHP_ROUND_HALF_UP);
                            $pdf->Cell(13, 5, $sess_avg, 1, 0, 'C');
                        } else {
                            $pdf->Cell(13, 5, 'Nul', 1, 0, 'C');
                        }

                        //TERM AVERAGE
                        $db->query("SELECT AVG(total) AS average FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', $term_id);
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $average = $db->single()->average;
                            $pdf->Cell(35, 5, 'AVERAGE = ' . round(($average), 2, PHP_ROUND_HALF_UP), 1, 1, 'C');
                        } else {
                            $pdf->Cell(35, 5, 'Nul', 1, 1, 'C');
                        }

                        $pdf->Cell(190, 5, '', 1, 1, 'C');
                        $pdf->Cell(190, 5, '', 1, 1, 'C');
                        $pdf->Cell(190, 5, 'GRADES:  A1[80-100] B2[75-79] B3[70-74]  C4[65-69]  C5[60-64]  C6[50-59] D7[45-49] E8[40-44]  F9[0-39]', 1, 1, 'C');
                        $pdf->Cell(190, 5, 'KEY NOTE: S-Avg [Sessional Average] GRD [Grade] REM [Remark] N/A [Not Available]', 1, 1, 'C');
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
                        $db->execute();
                        if ($db->rowCount() > 0) {
                            $results = $db->single();

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

                            //CLASS TEACHER AND PRINCIPAL COMMENTS
                            $pdf->SetFont('Times', 'B', 9);
                            $pdf->Cell(53, 5, "CLASS TEACHER'S NAME", 1, 0, 'L');
                            $pdf->Cell(80, 5, $class_teacher_name, 1, 1, 'L');
                            $pdf->Cell(53, 5, "CLASS TEACHER'S COMMENT", 1, 0, 'L');
                            $pdf->Cell(80, 5, $teacher_comment, 1, 1, 'L');
                            $pdf->Cell(53, 5, 'PRINCIPAL COMMENT', 1, 0, 'L');
                            $pdf->Cell(80, 5, $principal_comment, 1, 1, 'L');
                            $pdf->Cell(53, 5, 'NEXT TERM BEGIN', 1, 0, 'L');

                            //Getting Next Term begins from the database
                            $db->query("SELECT * FROM next_term_tbl");
                            $db->execute();
                            if ($db->rowCount() > 0) {
                                $result = $db->single();
                                $pdf->Cell(80, 5, $result->next_term, 1, 1, 'L');
                            } else {
                                $error = "Not Schedule";
                                $pdf->Cell(80, 5, $error, 1, 1, 'L');
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
                    $downloadPDF = $class_name.' ('.$session_name.' - '.$term_name.')';
                    $pdf->Output($downloadPDF.'.pdf', 'D');
                }
            } else {
                echo "No result found";
            }
        } else {
            die($db->getError());
        }
    } else {
        echo "Term is annulled!";
    }
    $db->Disconect();
}
/***********************DOWNLOADABLE ENDS HERE ********************************/
