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

if (isset($_POST['single_view_btn'])) {
    $admNo = $_POST['admNo'];
    $session_id = $_POST['session_id'];
    $term_id = $_POST['term_id'];

    $db = new Database();
    // Fetch frontend informations
    $db = new Database();
    $db->query("SELECT * FROM frontend_tbl");
    if ($db->execute()) {
        if ($db->rowCount() > 0) {
            $row = $db->single();
            $school_name = $row->school_name;
            $school_sections = $row->school_sections;
            $school_addr = $row->school_addr;
            $contact = "$row->school_contact1, $row->school_contact2";
            $email = $row->email;
            $img_logo = $row->img_logo;
        }else{
            $school_name = "An-Nur Info-Tech";
            $school_sections = "Nursery, Primary, and Secondary";
            $school_addr = "No. 125 Idi-ogun Offa, Kwara State.";
            $contact = "+234 8137541749, +234 7058875762";
            $email = "wasiubello60@gmail.com";
            $img_logo = '../uploads/img/apple-touch-icon.png';
        }
    }
    // Fetch result
    $db->query(
        "SELECT * FROM result_tbl AS rt
            JOIN class_tbl ON class_tbl.class_id = rt.class_id 
            JOIN students_tbl ON students_tbl.admNo = rt.admNo
            JOIN session_tbl ON session_tbl.session_id = rt.session_id
            JOIN term_tbl ON term_tbl.term_id = rt.term_id
            WHERE rt.admNo = :admNo AND rt.session_id = :session_id AND rt.term_id = :term_id;
        "
    );
    $db->bind(':admNo', $admNo);
    $db->bind(':session_id', $session_id);
    $db->bind(':term_id', $term_id);

    if ($db->execute()) {
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
            $term_id = $result->term_id;
            $term_name = $result->term_name;
            
            //Getting class population
            $db->query("SELECT * FROM students_tbl WHERE class_id = :class_id;");
            $db->bind(':class_id', $class_id);
            $db->execute();
            $rst = $db->single();
            $class_num = $db->rowCount(); // Getting total number of students in a class

            //Checking if term is 1 2 and 3 Term TODO
            if ($term_id == 1) {
                //Instanciation of inherited class
                $pdf = new PDF('P', 'mm', 'A4');
                $pdf->AliasNbPages();
                $pdf->AddPage();

                //Add image logo
                //$pdf ->Image('img/logoPdf.png', 7,7,33,34);
                $pdf->SetFont('Arial', 'B', 25);
                $pdf->Cell(190, 10, $school_name, 0, 0, 'C');
                $pdf->ln(7);
                $pdf->SetFont(
                    'Times',
                    'I',
                    12
                );
                $pdf->Cell(180, 10, $school_sections, 0, 0, 'C');
                $pdf->ln(7);
                $pdf->SetFont(
                    'Times',
                    'B',
                    14
                );
                $pdf->Cell(180, 10, $school_addr, 0, 0, 'C');
                $pdf->ln(10);
                $pdf->SetFont(
                    'Times',
                    'I',
                    12
                );
                $pdf->Cell(180, 10, "Tel: $contact", 0, 0, 'C');
                $pdf->ln(5);
                $pdf->SetFont(
                    'Times',
                    'I',
                    12
                );
                $pdf->Cell(180, 10, "Email: $email", 0, 0, 'C');
                $pdf->ln(20);
                //Adding another image for the next record
                $pdf->Image($img_logo, 7, 7, 33, 34);
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
                if (!empty($passport) || ($passport != null)) {
                    $pdf->Image(
                        $passport,
                        170,
                        30,
                        30,
                        30
                    );
                } else {
                    $pdf->Image(
                        "../uploads/student_image.jpg",
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
                $pdf->SetFont('Times', '', 10);
                $pdf->Cell(
                    40,
                    5,
                    $admNo,
                    1,
                    0,
                    'L'
                );
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
                $pdf->Cell(70, 5, $class_num, 1, 1, 'L');
                $pdf->SetFont('Times', 'B', 10);
                $pdf->Cell(40, 5, 'GENDER', 1, 0, 'L');
                $pdf->SetFont('Times', '', 10);
                $pdf->Cell(
                    40,
                    5,
                    $gender,
                    1,
                    0,
                    'L'
                );
                $pdf->SetFont('Times', 'B', 10);
                $pdf->Cell(40, 5, 'RELIGION', 1, 0, 'L');
                $pdf->SetFont('Times', '', 10);
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
                            "
                );
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', $term_id);

                if ($db->execute()) {
                    if ($db->rowCount() > 0) {
                        $row = $db->resultset();
                        foreach ($row as $results) {
                            $subjects = $results->subject_name;
                            //Row SUBJECTS
                            $pdf->SetFont('Times', '', 10);
                            $pdf->Cell(75, 5, $subjects, 1, 0, 'L');

                            // $ca = $results->ca;
                            // $exam = $results->exam;
                            if (($results->ca == 0) || ($results->ca == null) || empty($results->ca)) {
                                $ca = "N/A";
                            } else {
                                $ca = $results->ca;
                            }

                            if (($results->exam == 0) || ($results->exam == null) || empty($results->exam)) {
                                $exam = "N/A";
                            } else {
                                $exam = $results->exam;
                            }
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
                    } else {
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
                } else {
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
                $total = $db->single()->total_sum;
                // $total = $sql_fetch->total_sum;
                //Getting Average
                $db->query("SELECT AVG(total) AS average FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', $term_id);
                $db->execute();
                $average = $db->single()->average;
                // $average = $sql_fetch->average;
                $pdf->SetFont('Times', 'B', 10);
                $pdf->Cell(110, 5, 'TOTAL = ', 1, 0, 'R');
                //$pdf ->Cell(15,5,'',1,0,'C');
                //$pdf ->Cell(20,5,'',1,0,'C');
                $pdf->Cell(20, 5, $total, 1, 0, 'C');
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

                //PRINCIPAL AUTO COMMENT TODO
                if($average <= 39 )
                {
                    $p_c = "This is a poor result, you can do more better";
                } 
                if (($average >= 40) || ($average >= 49)) {
                    $p_c = "This is below average, you can try harder";
                }
                if (($average >= 50) || ($average >= 59)) {
                    $p_c = "An average result, you can try harder";
                }  
                if (($average >= 60) || ($average >= 69)) {
                    $p_c = "Good result, you can do better";
                }  
                if (($average >= 70) || ($average >= 79)) {
                    $p_c = "Bravo! Do not give up";
                }  
                if (($average >= 80) || ($average >= 100)) {
                    $p_c = "Excellent! Keep the energy intact";
                }
                if ($db->execute()) {
                    if ($db->rowCount() > 0) {
                        $row = $db->resultset();
                        foreach ($row as $results) {
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
                            $pdf->Cell(53, 5, "CLASS TEACHER'S NAME", 1, 0, 'L');
                            //Getting class teacher name
                            $db->query(
                                "SELECT * FROM class_tbl AS tn
                                        JOIN staff_tbl ON staff_tbl.staff_id = tn.instructor_id
                                        WHERE tn.class_id = :class_id;"
                            );
                            $db->bind(':class_id', $class_id);
                            if ($db->execute()) {
                                if ($db->rowCount() > 0) {
                                    $result = $db->resultset();
                                    foreach ($result as $teacher) {
                                        $pdf->SetFont('Times', 'B', 9);
                                        $pdf->Cell(80, 5, $teacher->fname . " " . $teacher->sname . " " . $teacher->oname, 1, 1, 'L');
                                    }
                                } else {
                                    $pdf->SetFont('Times', 'B', 9);
                                    //$pdf ->Cell(80,5, "Teacher's not found",1,1,'L');
                                    $pdf->Cell(80, 5, "Teacher not found", 1, 1, 'L');
                                }
                            } else {
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
                            $db->query("SELECT next_term_begin FROM tbl_year_session WHERE session_id = :session_id AND term_id = :term_id;");
                            $db->bind(':session_id', $session_id);
                            $db->bind(':term_id', $term_id);                            
                            if ($db->execute()) {
                                if ($db->rowCount() > 0) {
                                    $nxt_term = $db->single()->next_term_begin;
                                    $pdf->SetFont('Times', '', 11);
                                    $pdf->Cell(80, 5, $nxt_term, 1, 1, 'L');
                                } else {
                                    $pdf->SetFont('Times', '', 11);
                                    $pdf->Cell(80, 5, 'Resumption date not scheduled', 1, 1, 'L');
                                }
                            } else {
                                die("Query failed".$db->getError());
                            }
                        }
                    } else {
                        $error = "No Teacher's comment";
                        $pdf->Cell(190, 5, $error, 1, 0, 'C');
                    }
                } else {
                    die($db->getError());
                    exit();
                }
                //SCHOOL STAMP
                //$pdf ->Image('img/signature.jpeg', 145,245,55,28);
                $pdf->ln(30);

                //Outputting the pdf file
                $pdf->SetTitle($admNo . ' (' . $session_name . ' - ' . $term_name . ')');
                //making it downloadable
                //$pdf->Output($class_name.' ('.$session_name.' - '.$term_name.')', 'D');
                $pdf->Output();
            } 
            else if ($term_id == 2) {
                //Instanciation of inherited class
                $pdf = new PDF('P', 'mm', 'A4');
                $pdf->AliasNbPages();
                $pdf->AddPage();

                //Add image logo
                //$pdf ->Image('img/logoPdf.png', 7,7,33,34);
                $pdf->SetFont('Arial', 'B', 25);
                $pdf->Cell(190, 10, $school_name, 0, 0, 'C');
                $pdf->ln(7);
                $pdf->SetFont(
                    'Times',
                    'I',
                    12
                );
                $pdf->Cell(180, 10, $school_sections, 0, 0, 'C');
                $pdf->ln(7);
                $pdf->SetFont(
                    'Times',
                    'B',
                    14
                );
                $pdf->Cell(180, 10, $school_addr, 0, 0, 'C');
                $pdf->ln(10);
                $pdf->SetFont(
                    'Times',
                    'I',
                    12
                );
                $pdf->Cell(180, 10, "Tel: $contact", 0, 0, 'C');
                $pdf->ln(5);
                $pdf->SetFont(
                    'Times',
                    'I',
                    12
                );
                $pdf->Cell(180, 10, "Email: $email", 0, 0, 'C');
                $pdf->ln(20);
                //Adding another image for the next record
                $pdf->Image($img_logo, 7, 7, 33, 34);
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
                if (!empty($passport) || ($passport != null)) {
                    $pdf->Image(
                        $passport,
                        170,
                        30,
                        30,
                        30
                    );
                } else {
                    $pdf->Image(
                        "../uploads/student_image.jpg",
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
                $pdf->SetFont('Times', '', 10);
                $pdf->Cell(
                    40,
                    5,
                    $admNo,
                    1,
                    0,
                    'L'
                );
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
                $pdf->Cell(70, 5, $class_num, 1, 1, 'L');
                $pdf->SetFont('Times', 'B', 10);
                $pdf->Cell(40, 5, 'GENDER', 1, 0, 'L');
                $pdf->SetFont('Times', '', 10);
                $pdf->Cell(
                    40,
                    5,
                    $gender,
                    1,
                    0,
                    'L'
                );
                $pdf->SetFont('Times', 'B', 10);
                $pdf->Cell(40, 5, 'RELIGION', 1, 0, 'L');
                $pdf->SetFont('Times', '', 10);
                $pdf->Cell(70, 5, $religion, 1, 1, 'L');
                $pdf->ln(10);
                $pdf ->SetFont('Times','B', 10);
                // Subjects header
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
                $db->query(
                    "SELECT * FROM result_tbl AS rs 
                    JOIN subject_tbl ON subject_tbl.subject_id = rs.subject_id
                    WHERE rs.admNo = :admNo AND rs.session_id = :session_id AND rs.term_id = :term_id;
                    "
                );
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', $term_id);

                if ($db->execute()) {
                    if ($db->rowCount() > 0) {
                        $row = $db->resultset();
                        foreach ($row as $results) {
                            $subjects = $results->subject_name;
                            $subject_id = $results->subject_id;
                            //Row SUBJECTS
                            $pdf->SetFont('Times', '', 10);
                            $pdf->Cell(75, 5, $subjects, 1, 0, 'L');

                            // $ca = $results->ca;
                            // $exam = $results->exam;
                            if (($results->ca == 0) || ($results->ca == null) || empty($results->ca)) {
                                $ca = "N/A";
                            } else {
                                $ca = $results->ca;
                            }

                            if (($results->exam == 0) || ($results->exam == null) || empty($results->exam)) {
                                $exam = "N/A";
                            } else {
                                $exam = $results->exam;
                            }
                            $total = $results->total;
                            $grade = $results->grade;
                            $remark = $results->remark;

                            $pdf ->Cell(10,5, $ca,1,0,'C');
                            $pdf ->Cell(15,5, $exam,1,0,'C');
                            $pdf ->Cell(15,5, $total,1,0,'C');
                            
                            //Getting each subject total on first term column
                            $db->query("SELECT * FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id AND subject_id = :subject_id;");
                            $db->bind(':admNo', $admNo);
                            $db->bind(':session_id', $session_id);
                            $db->bind(':term_id', 1);
                            $db->bind(':subject_id', $subject_id);
                            $db->execute();
                            if($db->rowCount() > 0)
                            {
                                $result = $db->resultset();
                                foreach ($result as $row)
                                {
                                    $first_term_total = $row->total;
                                    if (($first_term_total == 0) || $first_term_total == null)
                                    {
                                        $pdf ->Cell(20,5, 'N/A',1,0,'C');
                                    }else{
                                        $pdf ->Cell(20,5, $first_term_total,1,0,'C');
                                    }
                                }    
                            }else{
                                $error = "N/A";
                                $pdf ->Cell(20,5, $error,1,0,'C');
                            }
                            $pdf ->Cell(25,5, $grade,1,0,'C');
                            $pdf ->Cell(30,5, $remark,1,1,'C');
                            $pdf->ln(0);
                        }
                    } else {
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
                } else {
                    die($db->getError());
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

                //Getting total 
                $db->query("SELECT SUM(total) AS total_sum FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', $term_id);
                $db->execute();
                $total = $db->single()->total_sum;
                
                //Getting first term total 
                $db->query("SELECT SUM(total) AS first_total_sum FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', 1);
                $db->execute();
                $first_term_total = $db->single()->first_total_sum;

                //Getting Average
                $db->query("SELECT AVG(total) AS average FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', $term_id);
                $db->execute();
                $average = $db->single()->average;
                
                $pdf ->SetFont('Times','B', 10);
                $pdf ->Cell(100,5, "TOTAL ",1,0,'R');
                $pdf ->Cell(15,5, $total, 1,0,'C');
                //FIRST TERM TOTAL
                if (($first_term_total == 0) || $first_term_total == null){ 
                    $pdf ->Cell(20,5, 'N/A', 1,0,'C');
                }else {
                    $pdf ->Cell(20,5, $first_term_total, 1,0,'C');
                }
                //TERM AVERAGE
                $pdf ->Cell(55,5,'AVERAGE = '.round(($average), 2, PHP_ROUND_HALF_UP),1,1,'L');
                $pdf ->Cell(190,5,'',1,1,'C');
                $pdf ->Cell(190,5,'',1,1,'C');
                $pdf ->Cell(190,5,'GRADES:  A1[80-100] B2[75-79] B3[70-74]  C4[65-69]  C5[60-64]  C6[50-59] D7[45-49] E8[40-44]  F9[0-39]',1,1,'C');
                $pdf ->Cell(190,5,'KEY NOTE: N/A [Not Available]',1,1,'C');
                $pdf ->ln(20);
            
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
                if($average <= 39 )
                {
                    $p_c = "This is a poor result, you can do more better";
                } 
                if (($average >= 40) || ($average >= 49)) {
                    $p_c = "This is below average, you can try harder";
                }
                if (($average >= 50) || ($average >= 59)) {
                    $p_c = "An average result, you can try harder";
                }  
                if (($average >= 60) || ($average >= 69)) {
                    $p_c = "Good result, you can do better";
                }  
                if (($average >= 70) || ($average >= 79)) {
                    $p_c = "Bravo! Do not give up";
                }  
                if (($average >= 80) || ($average >= 100)) {
                    $p_c = "Excellent! Keep the energy intact";
                }
                if ($db->execute()) {
                    if ($db->rowCount() > 0) {
                        $row = $db->resultset();
                        foreach ($row as $results) {
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
                            $pdf->Cell(53, 5, "CLASS TEACHER'S NAME", 1, 0, 'L');
                            //Getting class teacher name
                            $db->query(
                                "SELECT * FROM class_tbl AS tn
                                        JOIN staff_tbl ON staff_tbl.staff_id = tn.instructor_id
                                        WHERE tn.class_id = :class_id;"
                            );
                            $db->bind(':class_id', $class_id);
                            if ($db->execute()) {
                                if ($db->rowCount() > 0) {
                                    $result = $db->resultset();
                                    foreach ($result as $teacher) {
                                        $pdf->SetFont('Times', 'B', 9);
                                        $pdf->Cell(80, 5, $teacher->fname . " " . $teacher->sname . " " . $teacher->oname, 1, 1, 'L');
                                    }
                                } else {
                                    $pdf->SetFont('Times', 'B', 9);
                                    //$pdf ->Cell(80,5, "Teacher's not found",1,1,'L');
                                    $pdf->Cell(80, 5, "Teacher not found", 1, 1, 'L');
                                }
                            } else {
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
                            $db->query("SELECT next_term_begin FROM tbl_year_session WHERE session_id = :session_id AND term_id = :term_id;");
                            $db->bind(':session_id', $session_id);
                            $db->bind(':term_id', $term_id);                            
                            if ($db->execute()) {
                                if ($db->rowCount() > 0) {
                                    $nxt_term = $db->single()->next_term_begin;
                                    $pdf->SetFont('Times', '', 11);
                                    $pdf->Cell(80, 5, $nxt_term, 1, 1, 'L');
                                } else {
                                    $pdf->SetFont('Times', '', 11);
                                    $pdf->Cell(80, 5, 'Resumption date not scheduled', 1, 1, 'L');
                                }
                            } else {
                                die("Query failed".$db->getError());
                            }
                        }
                    } else {
                        $error = "No Teacher's comment";
                        $pdf->Cell(190, 5, $error, 1, 0, 'C');
                    }
                } else {
                    die($db->getError());
                    exit();
                }
                //Outputting the pdf file
                $pdf->SetTitle($admNo . ' (' . $session_name . ' - ' . $term_name . ')');
                //making it downloadable
                //$pdf->Output($class_name.' ('.$session_name.' - '.$term_name.')', 'D');
                $pdf->Output();
            
            } 
            else if ($term_id == 3) 
            {
                //Instanciation of inherited class
                $pdf = new PDF('P', 'mm', 'A4');
                $pdf->AliasNbPages();
                $pdf->AddPage();

                //Add image logo
                //$pdf ->Image('img/logoPdf.png', 7,7,33,34);
                $pdf->SetFont('Arial', 'B', 25);
                $pdf->Cell(190, 10, $school_name, 0, 0, 'C');
                $pdf->ln(7);
                $pdf->SetFont(
                    'Times',
                    'I',
                    12
                );
                $pdf->Cell(180, 10, $school_sections, 0, 0, 'C');
                $pdf->ln(7);
                $pdf->SetFont(
                    'Times',
                    'B',
                    14
                );
                $pdf->Cell(180, 10, $school_addr, 0, 0, 'C');
                $pdf->ln(10);
                $pdf->SetFont(
                    'Times',
                    'I',
                    12
                );
                $pdf->Cell(180, 10, "Tel: $contact", 0, 0, 'C');
                $pdf->ln(5);
                $pdf->SetFont(
                    'Times',
                    'I',
                    12
                );
                $pdf->Cell(180, 10, "Email: $email", 0, 0, 'C');
                $pdf->ln(20);
                //Adding another image for the next record
                $pdf->Image($img_logo, 7, 7, 33, 34);
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
                if (!empty($passport) || ($passport != null)) {
                    $pdf->Image(
                        $passport,
                        170,
                        30,
                        30,
                        30
                    );
                } else {
                    $pdf->Image(
                        "../uploads/student_image.jpg",
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
                $pdf->SetFont('Times', '', 10);
                $pdf->Cell(
                    40,
                    5,
                    $admNo,
                    1,
                    0,
                    'L'
                );
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
                $pdf->Cell(70, 5, $class_num, 1, 1, 'L');
                $pdf->SetFont('Times', 'B', 10);
                $pdf->Cell(40, 5, 'GENDER', 1, 0, 'L');
                $pdf->SetFont('Times', '', 10);
                $pdf->Cell(
                    40,
                    5,
                    $gender,
                    1,
                    0,
                    'L'
                );
                $pdf->SetFont('Times', 'B', 10);
                $pdf->Cell(40, 5, 'RELIGION', 1, 0, 'L');
                $pdf->SetFont('Times', '', 10);
                $pdf->Cell(70, 5, $religion, 1, 1, 'L');
                $pdf->ln(10);
                $pdf ->SetFont('Times','B', 10);
                
                // Subjects header
                $pdf ->SetFont('Times','B', 10);
                $pdf ->Cell(75,5,'SUBJECTS',1,0,'L');
                $pdf ->Cell(7,5,'CA',1,0,'C');
                $pdf ->Cell(12,5,'EXAM',1,0,'C');
                $pdf ->Cell(13,5,'TOTAL',1,0,'C');
                $pdf ->Cell(18,5,"2nd TERM",1,0,'C');
                $pdf ->Cell(17,5,'1st TERM',1,0,'C');
                $pdf ->Cell(13,5,'S-Avg',1,0,'C');
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
                $db->query(
                    "SELECT * FROM result_tbl AS rs 
                    JOIN subject_tbl ON subject_tbl.subject_id = rs.subject_id
                    WHERE rs.admNo = :admNo AND rs.session_id = :session_id AND rs.term_id = :term_id;
                    "
                );
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', $term_id);
                $db->execute();
                if($db->rowCount() > 0)
                {
                    $results = $db->resultset();
                    foreach ($results as $row)
                    {
                        $subject_id = $row->subject_id;
                        
                        $pdf ->SetFont('Times','', 10);
                        $pdf ->Cell(75,5, $row->subject_name, 1,0,'L');
                        
                        if (($row->ca == 0) || ($row->ca == null) || empty($row->ca)) {
                            $ca = "N/A";
                        } else {
                            $ca = $row->ca;
                        }

                        if (($row->exam == 0) || ($row->exam == null) || empty($row->exam)) {
                            $exam = "N/A";
                        } else {
                            $exam = $row->exam;
                        }
                        $total = $row->total;
                        $grade = $row->grade;
                        $remark = $row->remark;

                        $pdf ->Cell(7,5, $ca,1,0,'C');
                        $pdf ->Cell(12,5, $exam,1,0,'C');
                        $pdf ->Cell(13,5, $total,1,0,'C');

                        //Getting the overall total of each subject and adding to second term column
                        $db->query("SELECT * FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id AND subject_id = :subject_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', 2);
                        $db->bind(':subject_id', $subject_id);
                        $db->execute();
                        if($db->rowCount() > 0)
                        {
                            $result = $db->resultset();
                            foreach ($result as $row)
                            {
                                $pdf ->Cell(18,5, $row->total,1,0,'C');
                            }    
                        }
                        else{
                            $pdf ->Cell(18,5, 'N/A',1,0,'C');
                        }

                        //Getting the overall total of each subject and adding to first term column
                        $db->query("SELECT * FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id AND subject_id = :subject_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', 1);
                        $db->bind(':subject_id', $subject_id);
                        $db->execute();
                        if($db->rowCount() > 0)
                        {
                            $result = $db->resultset();
                            foreach ($result as $row)
                            {
                                $pdf ->Cell(17,5, $row->total,1,0,'C');
                            }    
                        }
                        else{
                            $pdf ->Cell(17,5, 'N/A', 1,0,'C');
                        }

                        //sessional average for each subjects column
                        $db->query("SELECT AVG(total) AS sess_avg FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND subject_id = :subject_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':subject_id', $subject_id);
                        $db->execute();
                        if($db->rowCount() > 0)
                        {
                            $result = $db->resultset();
                            foreach ($result as $row)
                            {
                                $pdf ->Cell(13,5, round(($row->sess_avg), 2, PHP_ROUND_HALF_UP),1,0,'C');
                            }    
                        }
                        else{
                            $pdf ->Cell(13,5, 'N/A',1,0,'C');
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
                $db->query("SELECT SUM(total) AS total_sum FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', $term_id);
                $db->execute();
                if($db->rowCount() > 0)
                {
                    $total = $db->single();
                    $pdf ->SetFont('Times','B', 10);
                    $pdf ->Cell(94,5, "TOTAL ",1,0,'R');
                    $pdf ->Cell(13,5, $total->total_sum, 1,0,'C');
                }
                else{
                    $pdf ->Cell(13,5, 'N/A', 1,0,'C');
                }
                
                //Getting Second term total
                $db->query("SELECT SUM(total) AS second_term_total FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', 2);
                $db->execute();
                if($db->rowCount() > 0)
                {
                    $total = $db->single();
                    $pdf ->Cell(18,5, $total->second_term_total, 1,0,'C');
                }
                else{
                    $pdf ->Cell(18,5, 'N/A', 1,0,'C');
                }

                //Getting Firt term total
                $db->query("SELECT SUM(total) AS first_term_total FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', 1);
                $db->execute();
                if($db->rowCount() > 0)
                {
                    $total = $db->single();
                    $pdf ->Cell(17,5, $total->first_term_total, 1,0,'C');
                }
                else{
                    $pdf ->Cell(17,5, 'N/A', 1,0,'C');
                }

                //Getting sessional total (1st + 2nd + 3rd)/3
                $db->query("SELECT AVG(total) AS sessional_avg FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id;");
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->execute();
                if($db->rowCount() > 0)
                {
                    $total = $db->single();
                    $pdf ->Cell(13,5, round(($total->sessional_avg), 2, PHP_ROUND_HALF_UP), 1,0,'C');
                }
                else{
                    $pdf ->Cell(13,5, 'N/A', 1,0,'C');
                }
                //TERM AVERAGE
                $db->query("SELECT AVG(total) AS average FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', $term_id);
                $db->execute();
                if ($db->rowCount() > 0)
                {
                    $average = $db->single()->average;
                    $pdf ->Cell(35,5,'AVERAGE = '.round(($average), 2, PHP_ROUND_HALF_UP),1,1,'C');
                }else{
                    $pdf ->Cell(35,5,'N/A', 1,1,'C');
                }
               
                $pdf ->Cell(190,5,'',1,1,'C');
                $pdf ->Cell(190,5,'',1,1,'C');
                $pdf ->Cell(190,5,'GRADES:  A1[80-100] B2[75-79] B3[70-74]  C4[65-69]  C5[60-64]  C6[50-59] D7[45-49] E8[40-44]  F9[0-39]',1,1,'C');
                $pdf ->Cell(190,5,'KEY NOTE: S-Avg [Sessional Average] GRD [Grade] REM [Remark] N/A [Not Available]',1,1,'C');
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
                $db->query("SELECT * FROM comments_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', $term_id);
                if ($db->execute()) {
                    if ($db->rowCount() > 0) {
                        $row = $db->resultset();
                        foreach ($row as $results) {
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
                            $pdf->Cell(53, 5, "CLASS TEACHER'S NAME", 1, 0, 'L');
                            //Getting class teacher name
                            $db->query(
                                "SELECT * FROM class_tbl AS tn
                                        JOIN staff_tbl ON staff_tbl.staff_id = tn.instructor_id
                                        WHERE tn.class_id = :class_id;"
                            );
                            $db->bind(':class_id', $class_id);
                            if ($db->execute()) {
                                if ($db->rowCount() > 0) {
                                    $result = $db->resultset();
                                    foreach ($result as $teacher) {
                                        $pdf->SetFont('Times', 'B', 9);
                                        $pdf->Cell(80, 5, $teacher->fname . " " . $teacher->sname . " " . $teacher->oname, 1, 1, 'L');
                                    }
                                } else {
                                    $pdf->SetFont('Times', 'B', 9);
                                    //$pdf ->Cell(80,5, "Teacher's not found",1,1,'L');
                                    $pdf->Cell(80, 5, "Teacher not found", 1, 1, 'L');
                                }
                            } else {
                                die($db->getError());
                                exit();
                            }
                            //PRINCIPAL AUTO COMMENT TODO
                            if($average <= 39 )
                            {
                                $p_c = "This is a poor result, you can do more better";
                            } 
                            if (($average >= 40) || ($average >= 49)) {
                                $p_c = "This is below average, you can try harder";
                            }
                            if (($average >= 50) || ($average >= 59)) {
                                $p_c = "An average result, you can try harder";
                            }  
                            if (($average >= 60) || ($average >= 69)) {
                                $p_c = "Good result, you can do better";
                            }  
                            if (($average >= 70) || ($average >= 79)) {
                                $p_c = "Bravo! Do not give up";
                            }  
                            if (($average >= 80) || ($average >= 100)) {
                                $p_c = "Excellent! Keep the energy intact";
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
                            $db->query("SELECT next_term_begin FROM tbl_year_session WHERE session_id = :session_id AND term_id = :term_id;");
                            $db->bind(':session_id', $session_id);
                            $db->bind(':term_id', $term_id);                            
                            if ($db->execute()) {
                                if ($db->rowCount() > 0) {
                                    $nxt_term = $db->single()->next_term_begin;
                                    $pdf->SetFont('Times', '', 11);
                                    $pdf->Cell(80, 5, $nxt_term, 1, 1, 'L');
                                } else {
                                    $pdf->SetFont('Times', '', 11);
                                    $pdf->Cell(80, 5, 'Resumption date not scheduled', 1, 1, 'L');
                                }
                            } else {
                                die("Query failed".$db->getError());
                            }
                        }
                    } else {
                        $error = "No Teacher's comment";
                        $pdf->Cell(190, 5, $error, 1, 0, 'C');
                    }
                } else {
                    die('Error '.$db->getError());
                }
                //Outputting the pdf file
                $pdf->SetTitle($admNo . ' (' . $session_name . ' - ' . $term_name . ')');
                //making it downloadable
                //$pdf->Output($class_name.' ('.$session_name.' - '.$term_name.')', 'D');
                $pdf->Output();
            } else
            {
                echo "Term is annulled!";
                exit();
            }
        } else {
            echo "No result found";
        }
    } else {
        die($db->getError());
    }
    $db->Disconect();
}

/*****************************Making Downloadable ***********************************/
if (isset($_POST['download_view_btn'])) {
    $admNo = $_POST['admNo'];
    $session_id = $_POST['session_id'];
    $term_id = $_POST['term_id'];

    // Fetch frontend informations
    $db = new Database();
    $db->query("SELECT * FROM frontend_tbl");
    if ($db->execute()) {
        if ($db->rowCount() > 0) {
            $row = $db->single();
            $school_name = $row->school_name;
            $school_sections = $row->school_sections;
            $school_addr = $row->school_addr;
            $contact = "$row->school_contact1, $row->school_contact2";
            $email = $row->email;
            $img_logo = $row->img_logo;
        }else{
            $school_name = "An-Nur Info-Tech";
            $school_sections = "Nursery, Primary, and Secondary";
            $school_addr = "No. 125 Idi-ogun Offa, Kwara State.";
            $contact = "+234 8137541749, +234 7058875762";
            $email = "wasiubello60@gmail.com";
            $img_logo = '../uploads/img/apple-touch-icon.png';
        }
    }

    $db = new Database();
    // Fetch result
    $db->query(
        "SELECT * FROM result_tbl AS rt
            JOIN class_tbl ON class_tbl.class_id = rt.class_id 
            JOIN students_tbl ON students_tbl.admNo = rt.admNo
            JOIN session_tbl ON session_tbl.session_id = rt.session_id
            JOIN term_tbl ON term_tbl.term_id = rt.term_id
            WHERE rt.admNo = :admNo AND rt.session_id = :session_id AND rt.term_id = :term_id;
        "
    );
    $db->bind(':admNo', $admNo);
    $db->bind(':session_id', $session_id);
    $db->bind(':term_id', $term_id);

    if ($db->execute()) {
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
            $term_id = $result->term_id;
            $term_name = $result->term_name;
            
            //Getting class population
            $db->query("SELECT * FROM students_tbl WHERE class_id = :class_id;");
            $db->bind(':class_id', $class_id);
            $db->execute();
            $rst = $db->single();
            $class_num = $db->rowCount(); // Getting total number of students in a class

            //Checking if term is 1 2 and 3 Term TODO
            if ($term_id == 1) {
                //Instanciation of inherited class
                $pdf = new PDF('P', 'mm', 'A4');
                $pdf->AliasNbPages();
                $pdf->AddPage();

                //Add image logo
                //$pdf ->Image('img/logoPdf.png', 7,7,33,34);
                $pdf->SetFont('Arial', 'B', 25);
                $pdf->Cell(190, 10, $school_name, 0, 0, 'C');
                $pdf->ln(7);
                $pdf->SetFont(
                    'Times',
                    'I',
                    12
                );
                $pdf->Cell(180, 10, $school_sections, 0, 0, 'C');
                $pdf->ln(7);
                $pdf->SetFont(
                    'Times',
                    'B',
                    14
                );
                $pdf->Cell(180, 10, $school_addr, 0, 0, 'C');
                $pdf->ln(10);
                $pdf->SetFont(
                    'Times',
                    'I',
                    12
                );
                $pdf->Cell(180, 10, "Tel: $contact", 0, 0, 'C');
                $pdf->ln(5);
                $pdf->SetFont(
                    'Times',
                    'I',
                    12
                );
                $pdf->Cell(180, 10, "Email: $email", 0, 0, 'C');
                $pdf->ln(20);
                //Adding another image for the next record
                $pdf->Image($img_logo, 7, 7, 33, 34);
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
                if (!empty($passport) || ($passport != null)) {
                    $pdf->Image(
                        $passport,
                        170,
                        30,
                        30,
                        30
                    );
                } else {
                    $pdf->Image(
                        "../uploads/student_image.jpg",
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
                $pdf->SetFont('Times', '', 10);
                $pdf->Cell(
                    40,
                    5,
                    $admNo,
                    1,
                    0,
                    'L'
                );
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
                $pdf->Cell(70, 5, $class_num, 1, 1, 'L');
                $pdf->SetFont('Times', 'B', 10);
                $pdf->Cell(40, 5, 'GENDER', 1, 0, 'L');
                $pdf->SetFont('Times', '', 10);
                $pdf->Cell(
                    40,
                    5,
                    $gender,
                    1,
                    0,
                    'L'
                );
                $pdf->SetFont('Times', 'B', 10);
                $pdf->Cell(40, 5, 'RELIGION', 1, 0, 'L');
                $pdf->SetFont('Times', '', 10);
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
                            "
                );
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', $term_id);

                if ($db->execute()) {
                    if ($db->rowCount() > 0) {
                        $row = $db->resultset();
                        foreach ($row as $results) {
                            $subjects = $results->subject_name;
                            //Row SUBJECTS
                            $pdf->SetFont('Times', '', 10);
                            $pdf->Cell(75, 5, $subjects, 1, 0, 'L');

                            // $ca = $results->ca;
                            // $exam = $results->exam;
                            if (($results->ca == 0) || ($results->ca == null) || empty($results->ca)) {
                                $ca = "N/A";
                            } else {
                                $ca = $results->ca;
                            }

                            if (($results->exam == 0) || ($results->exam == null) || empty($results->exam)) {
                                $exam = "N/A";
                            } else {
                                $exam = $results->exam;
                            }
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
                    } else {
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
                } else {
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
                $total = $db->single()->total_sum;
                // $total = $sql_fetch->total_sum;
                //Getting Average
                $db->query("SELECT AVG(total) AS average FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', $term_id);
                $db->execute();
                $average = $db->single()->average;
                // $average = $sql_fetch->average;
                $pdf->SetFont('Times', 'B', 10);
                $pdf->Cell(110, 5, 'TOTAL = ', 1, 0, 'R');
                $pdf->Cell(20, 5, $total, 1, 0, 'C');
                //TERM AVERAGE 
                $pdf->Cell(60, 5, 'AVERAGE = '.round(($average), 2, PHP_ROUND_HALF_UP), 1, 1, 'L');
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

                //PRINCIPAL AUTO COMMENT TODO
                if($average <= 39 )
                {
                    $p_c = "This is a poor result, you can do more better";
                } 
                if (($average >= 40) || ($average >= 49)) {
                    $p_c = "This is below average, you can try harder";
                }
                if (($average >= 50) || ($average >= 59)) {
                    $p_c = "An average result, you can try harder";
                }  
                if (($average >= 60) || ($average >= 69)) {
                    $p_c = "Good result, you can do better";
                }  
                if (($average >= 70) || ($average >= 79)) {
                    $p_c = "Bravo! Do not give up";
                }  
                if (($average >= 80) || ($average >= 100)) {
                    $p_c = "Excellent! Keep the energy intact";
                }
                if ($db->execute()) {
                    if ($db->rowCount() > 0) {
                        $row = $db->resultset();
                        foreach ($row as $results) {
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
                            $pdf->Cell(53, 5, "CLASS TEACHER'S NAME", 1, 0, 'L');
                            //Getting class teacher name
                            $db->query(
                                "SELECT * FROM class_tbl AS tn
                                        JOIN staff_tbl ON staff_tbl.staff_id = tn.instructor_id
                                        WHERE tn.class_id = :class_id;"
                            );
                            $db->bind(':class_id', $class_id);
                            if ($db->execute()) {
                                if ($db->rowCount() > 0) {
                                    $result = $db->resultset();
                                    foreach ($result as $teacher) {
                                        $pdf->SetFont('Times', 'B', 9);
                                        $pdf->Cell(80, 5, $teacher->fname . " " . $teacher->sname . " " . $teacher->oname, 1, 1, 'L');
                                    }
                                } else {
                                    $pdf->SetFont('Times', 'B', 9);
                                    //$pdf ->Cell(80,5, "Teacher's not found",1,1,'L');
                                    $pdf->Cell(80, 5, "Teacher not found", 1, 1, 'L');
                                }
                            } else {
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
                            $db->query("SELECT next_term_begin FROM tbl_year_session WHERE session_id = :session_id AND term_id = :term_id;");
                            $db->bind(':session_id', $session_id);
                            $db->bind(':term_id', $term_id);                            
                            if ($db->execute()) {
                                if ($db->rowCount() > 0) {
                                    $nxt_term = $db->single()->next_term_begin;
                                    $pdf->SetFont('Times', '', 11);
                                    $pdf->Cell(80, 5, $nxt_term, 1, 1, 'L');
                                } else {
                                    $pdf->SetFont('Times', '', 11);
                                    $pdf->Cell(80, 5, 'Resumption date not scheduled', 1, 1, 'L');
                                }
                            } else {
                                die("Query failed".$db->getError());
                            }
                        }
                    } else {
                        $error = "No Teacher's comment";
                        $pdf->Cell(190, 5, $error, 1, 0, 'C');
                    }
                } else {
                    die($db->getError());
                    exit();
                }
                //SCHOOL STAMP
                //$pdf ->Image('img/signature.jpeg', 145,245,55,28);
                $pdf->ln(30);

                //making it downloadable
                $downloadPDF = $admNo.' ('.$session_name.' - '.$term_name.')';
                $pdf->Output($downloadPDF.'.pdf', 'D');
            } 
            else if ($term_id == 2) {
                //Instanciation of inherited class
                $pdf = new PDF('P', 'mm', 'A4');
                $pdf->AliasNbPages();
                $pdf->AddPage();

                //Add image logo
                //$pdf ->Image('img/logoPdf.png', 7,7,33,34);
                $pdf->SetFont('Arial', 'B', 25);
                $pdf->Cell(190, 10, $school_name, 0, 0, 'C');
                $pdf->ln(7);
                $pdf->SetFont(
                    'Times',
                    'I',
                    12
                );
                $pdf->Cell(180, 10, $school_sections, 0, 0, 'C');
                $pdf->ln(7);
                $pdf->SetFont(
                    'Times',
                    'B',
                    14
                );
                $pdf->Cell(180, 10, $school_addr, 0, 0, 'C');
                $pdf->ln(10);
                $pdf->SetFont(
                    'Times',
                    'I',
                    12
                );
                $pdf->Cell(180, 10, "Tel: $contact", 0, 0, 'C');
                $pdf->ln(5);
                $pdf->SetFont(
                    'Times',
                    'I',
                    12
                );
                $pdf->Cell(180, 10, "Email: $email", 0, 0, 'C');
                $pdf->ln(20);
                //Adding another image for the next record
                $pdf->Image($img_logo, 7, 7, 33, 34);
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
                if (!empty($passport) || ($passport != null)) {
                    $pdf->Image(
                        $passport,
                        170,
                        30,
                        30,
                        30
                    );
                } else {
                    $pdf->Image(
                        "../uploads/student_image.jpg",
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
                $pdf->SetFont('Times', '', 10);
                $pdf->Cell(
                    40,
                    5,
                    $admNo,
                    1,
                    0,
                    'L'
                );
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
                $pdf->Cell(70, 5, $class_num, 1, 1, 'L');
                $pdf->SetFont('Times', 'B', 10);
                $pdf->Cell(40, 5, 'GENDER', 1, 0, 'L');
                $pdf->SetFont('Times', '', 10);
                $pdf->Cell(
                    40,
                    5,
                    $gender,
                    1,
                    0,
                    'L'
                );
                $pdf->SetFont('Times', 'B', 10);
                $pdf->Cell(40, 5, 'RELIGION', 1, 0, 'L');
                $pdf->SetFont('Times', '', 10);
                $pdf->Cell(70, 5, $religion, 1, 1, 'L');
                $pdf->ln(10);
                $pdf ->SetFont('Times','B', 10);
                // Subjects header
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
                $db->query(
                    "SELECT * FROM result_tbl AS rs 
                    JOIN subject_tbl ON subject_tbl.subject_id = rs.subject_id
                    WHERE rs.admNo = :admNo AND rs.session_id = :session_id AND rs.term_id = :term_id;
                    "
                );
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', $term_id);

                if ($db->execute()) {
                    if ($db->rowCount() > 0) {
                        $row = $db->resultset();
                        foreach ($row as $results) {
                            $subjects = $results->subject_name;
                            $subject_id = $results->subject_id;
                            //Row SUBJECTS
                            $pdf->SetFont('Times', '', 10);
                            $pdf->Cell(75, 5, $subjects, 1, 0, 'L');

                            // $ca = $results->ca;
                            // $exam = $results->exam;
                            if (($results->ca == 0) || ($results->ca == null) || empty($results->ca)) {
                                $ca = "N/A";
                            } else {
                                $ca = $results->ca;
                            }

                            if (($results->exam == 0) || ($results->exam == null) || empty($results->exam)) {
                                $exam = "N/A";
                            } else {
                                $exam = $results->exam;
                            }
                            $total = $results->total;
                            $grade = $results->grade;
                            $remark = $results->remark;

                            $pdf ->Cell(10,5, $ca,1,0,'C');
                            $pdf ->Cell(15,5, $exam,1,0,'C');
                            $pdf ->Cell(15,5, $total,1,0,'C');
                            
                            //Getting each subject total on first term column
                            $db->query("SELECT * FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id AND subject_id = :subject_id;");
                            $db->bind(':admNo', $admNo);
                            $db->bind(':session_id', $session_id);
                            $db->bind(':term_id', 1);
                            $db->bind(':subject_id', $subject_id);
                            $db->execute();
                            if($db->rowCount() > 0)
                            {
                                $result = $db->resultset();
                                foreach ($result as $row)
                                {
                                    $first_term_total = $row->total;
                                    if (($first_term_total == 0) || $first_term_total == null)
                                    {
                                        $pdf ->Cell(20,5, 'N/A',1,0,'C');
                                    }else{
                                        $pdf ->Cell(20,5, $first_term_total,1,0,'C');
                                    }
                                }    
                            }else{
                                $error = "N/A";
                                $pdf ->Cell(20,5, $error,1,0,'C');
                            }
                            $pdf ->Cell(25,5, $grade,1,0,'C');
                            $pdf ->Cell(30,5, $remark,1,1,'C');
                            $pdf->ln(0);
                        }
                    } else {
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
                } else {
                    die($db->getError());
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

                //Getting total 
                $db->query("SELECT SUM(total) AS total_sum FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', $term_id);
                $db->execute();
                $total = $db->single()->total_sum;
                
                //Getting first term total 
                $db->query("SELECT SUM(total) AS first_total_sum FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', 1);
                $db->execute();
                $first_term_total = $db->single()->first_total_sum;

                //Getting Average
                $db->query("SELECT AVG(total) AS average FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', $term_id);
                $db->execute();
                $average = $db->single()->average;
                
                $pdf ->SetFont('Times','B', 10);
                $pdf ->Cell(100,5, "TOTAL ",1,0,'R');
                $pdf ->Cell(15,5, $total, 1,0,'C');
                //FIRST TERM TOTAL
                if (($first_term_total == 0) || $first_term_total == null){ 
                    $pdf ->Cell(20,5, 'N/A', 1,0,'C');
                }else {
                    $pdf ->Cell(20,5, $first_term_total, 1,0,'C');
                }
                //TERM AVERAGE
                $pdf ->Cell(55,5,'AVERAGE = '.round(($average), 2, PHP_ROUND_HALF_UP),1,1,'L');
                $pdf ->Cell(190,5,'',1,1,'C');
                $pdf ->Cell(190,5,'',1,1,'C');
                $pdf ->Cell(190,5,'GRADES:  A1[80-100] B2[75-79] B3[70-74]  C4[65-69]  C5[60-64]  C6[50-59] D7[45-49] E8[40-44]  F9[0-39]',1,1,'C');
                $pdf ->Cell(190,5,'KEY NOTE: N/A [Not Available]',1,1,'C');
                $pdf ->ln(20);
            
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
                if($average <= 39 )
                {
                    $p_c = "This is a poor result, you can do more better";
                } 
                if (($average >= 40) || ($average >= 49)) {
                    $p_c = "This is below average, you can try harder";
                }
                if (($average >= 50) || ($average >= 59)) {
                    $p_c = "An average result, you can try harder";
                }  
                if (($average >= 60) || ($average >= 69)) {
                    $p_c = "Good result, you can do better";
                }  
                if (($average >= 70) || ($average >= 79)) {
                    $p_c = "Bravo! Do not give up";
                }  
                if (($average >= 80) || ($average >= 100)) {
                    $p_c = "Excellent! Keep the energy intact";
                }
                if ($db->execute()) {
                    if ($db->rowCount() > 0) {
                        $row = $db->resultset();
                        foreach ($row as $results) {
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
                            $pdf->Cell(53, 5, "CLASS TEACHER'S NAME", 1, 0, 'L');
                            //Getting class teacher name
                            $db->query(
                                "SELECT * FROM class_tbl AS tn
                                        JOIN staff_tbl ON staff_tbl.staff_id = tn.instructor_id
                                        WHERE tn.class_id = :class_id;"
                            );
                            $db->bind(':class_id', $class_id);
                            if ($db->execute()) {
                                if ($db->rowCount() > 0) {
                                    $result = $db->resultset();
                                    foreach ($result as $teacher) {
                                        $pdf->SetFont('Times', 'B', 9);
                                        $pdf->Cell(80, 5, $teacher->fname . " " . $teacher->sname . " " . $teacher->oname, 1, 1, 'L');
                                    }
                                } else {
                                    $pdf->SetFont('Times', 'B', 9);
                                    //$pdf ->Cell(80,5, "Teacher's not found",1,1,'L');
                                    $pdf->Cell(80, 5, "Teacher not found", 1, 1, 'L');
                                }
                            } else {
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
                            $db->query("SELECT next_term_begin FROM tbl_year_session WHERE session_id = :session_id AND term_id = :term_id;");
                            $db->bind(':session_id', $session_id);
                            $db->bind(':term_id', $term_id);                            
                            if ($db->execute()) {
                                if ($db->rowCount() > 0) {
                                    $nxt_term = $db->single()->next_term_begin;
                                    $pdf->SetFont('Times', '', 11);
                                    $pdf->Cell(80, 5, $nxt_term, 1, 1, 'L');
                                } else {
                                    $pdf->SetFont('Times', '', 11);
                                    $pdf->Cell(80, 5, 'Resumption date not scheduled', 1, 1, 'L');
                                }
                            } else {
                                die("Query failed".$db->getError());
                            }
                        }
                    } else {
                        $error = "No Teacher's comment";
                        $pdf->Cell(190, 5, $error, 1, 0, 'C');
                    }
                } else {
                    die($db->getError());
                    exit();
                }
                //making it downloadable
                $downloadPDF = $admNo.' ('.$session_name.' - '.$term_name.')';
                $pdf->Output($downloadPDF.'.pdf', 'D');
            
            } 
            else if ($term_id == 3) 
            {
                //Instanciation of inherited class
                $pdf = new PDF('P', 'mm', 'A4');
                $pdf->AliasNbPages();
                $pdf->AddPage();

                //Add image logo
                //$pdf ->Image('img/logoPdf.png', 7,7,33,34);
                $pdf->SetFont('Arial', 'B', 25);
                $pdf->Cell(190, 10, $school_name, 0, 0, 'C');
                $pdf->ln(7);
                $pdf->SetFont(
                    'Times',
                    'I',
                    12
                );
                $pdf->Cell(180, 10, $school_sections, 0, 0, 'C');
                $pdf->ln(7);
                $pdf->SetFont(
                    'Times',
                    'B',
                    14
                );
                $pdf->Cell(180, 10, $school_addr, 0, 0, 'C');
                $pdf->ln(10);
                $pdf->SetFont(
                    'Times',
                    'I',
                    12
                );
                $pdf->Cell(180, 10, "Tel: $contact", 0, 0, 'C');
                $pdf->ln(5);
                $pdf->SetFont(
                    'Times',
                    'I',
                    12
                );
                $pdf->Cell(180, 10, "Email: $email", 0, 0, 'C');
                $pdf->ln(20);
                //Adding another image for the next record
                $pdf->Image($img_logo, 7, 7, 33, 34);
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
                if (!empty($passport) || ($passport != null)) {
                    $pdf->Image(
                        $passport,
                        170,
                        30,
                        30,
                        30
                    );
                } else {
                    $pdf->Image(
                        "../uploads/student_image.jpg",
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
                $pdf->SetFont('Times', '', 10);
                $pdf->Cell(
                    40,
                    5,
                    $admNo,
                    1,
                    0,
                    'L'
                );
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
                $pdf->Cell(70, 5, $class_num, 1, 1, 'L');
                $pdf->SetFont('Times', 'B', 10);
                $pdf->Cell(40, 5, 'GENDER', 1, 0, 'L');
                $pdf->SetFont('Times', '', 10);
                $pdf->Cell(
                    40,
                    5,
                    $gender,
                    1,
                    0,
                    'L'
                );
                $pdf->SetFont('Times', 'B', 10);
                $pdf->Cell(40, 5, 'RELIGION', 1, 0, 'L');
                $pdf->SetFont('Times', '', 10);
                $pdf->Cell(70, 5, $religion, 1, 1, 'L');
                $pdf->ln(10);
                $pdf ->SetFont('Times','B', 10);
                
                // Subjects header
                $pdf ->SetFont('Times','B', 10);
                $pdf ->Cell(75,5,'SUBJECTS',1,0,'L');
                $pdf ->Cell(7,5,'CA',1,0,'C');
                $pdf ->Cell(12,5,'EXAM',1,0,'C');
                $pdf ->Cell(13,5,'TOTAL',1,0,'C');
                $pdf ->Cell(18,5,"2nd TERM",1,0,'C');
                $pdf ->Cell(17,5,'1st TERM',1,0,'C');
                $pdf ->Cell(13,5,'S-Avg',1,0,'C');
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
                $db->query(
                    "SELECT * FROM result_tbl AS rs 
                    JOIN subject_tbl ON subject_tbl.subject_id = rs.subject_id
                    WHERE rs.admNo = :admNo AND rs.session_id = :session_id AND rs.term_id = :term_id;
                    "
                );
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', $term_id);
                $db->execute();
                if($db->rowCount() > 0)
                {
                    $results = $db->resultset();
                    foreach ($results as $row)
                    {
                        $subject_id = $row->subject_id;

                        $pdf ->SetFont('Times','', 10);
                        $pdf ->Cell(75,5, $row->subject_name, 1,0,'L');
                        
                        if (($row->ca == 0) || ($row->ca == null) || empty($row->ca)) {
                            $ca = "N/A";
                        } else {
                            $ca = $row->ca;
                        }

                        if (($row->exam == 0) || ($row->exam == null) || empty($row->exam)) {
                            $exam = "N/A";
                        } else {
                            $exam = $row->exam;
                        }
                        $total = $row->total;
                        $grade = $row->grade;
                        $remark = $row->remark;

                        $pdf ->Cell(7,5, $ca,1,0,'C');
                        $pdf ->Cell(12,5, $exam,1,0,'C');
                        $pdf ->Cell(13,5, $total,1,0,'C');

                        //Getting the overall total of each subject and adding to second term column
                        $db->query("SELECT * FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id AND subject_id = :subject_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', 2);
                        $db->bind(':subject_id', $subject_id);
                        $db->execute();
                        if($db->rowCount() > 0)
                        {
                            $result = $db->resultset();
                            foreach ($result as $row)
                            {
                                $pdf ->Cell(18,5, $row->total,1,0,'C');
                            }    
                        }
                        else{
                            $pdf ->Cell(18,5, 'N/A',1,0,'C');
                        }

                        //Getting the overall total of each subject and adding to first term column
                        $db->query("SELECT * FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id AND subject_id = :subject_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':term_id', 1);
                        $db->bind(':subject_id', $subject_id);
                        $db->execute();
                        if($db->rowCount() > 0)
                        {
                            $result = $db->resultset();
                            foreach ($result as $row)
                            {
                                $pdf ->Cell(17,5, $row->total,1,0,'C');
                            }    
                        }
                        else{
                            $pdf ->Cell(17,5, 'N/A', 1,0,'C');
                        }

                        //sessional average for each subjects column
                        $db->query("SELECT AVG(total) AS sess_avg FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND subject_id = :subject_id;");
                        $db->bind(':admNo', $admNo);
                        $db->bind(':session_id', $session_id);
                        $db->bind(':subject_id', $subject_id);
                        $db->execute();
                        if($db->rowCount() > 0)
                        {
                            $result = $db->resultset();
                            foreach ($result as $row)
                            {
                                $pdf ->Cell(13,5, round(($row->sess_avg), 2, PHP_ROUND_HALF_UP),1,0,'C');
                            }    
                        }
                        else{
                            $pdf ->Cell(13,5, 'N/A',1,0,'C');
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
                
                    
                //Getting total 
                $db->query("SELECT SUM(total) AS total_sum FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', $term_id);
                $db->execute();
                if($db->rowCount() > 0)
                {
                    $total = $db->single();
                    $pdf ->SetFont('Times','B', 10);
                    $pdf ->Cell(94,5, "TOTAL ",1,0,'R');
                    $pdf ->Cell(13,5, $total->total_sum, 1,0,'C');
                }
                else{
                    $pdf ->Cell(13,5, 'N/A', 1,0,'C');
                }
                
                //Getting Second term total
                $db->query("SELECT SUM(total) AS second_term_total FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', 2);
                $db->execute();
                if($db->rowCount() > 0)
                {
                    $total = $db->single();
                    $pdf ->Cell(18,5, $total->second_term_total, 1,0,'C');
                }
                else{
                    $pdf ->Cell(18,5, 'N/A', 1,0,'C');
                }

                //Getting Firt term total
                $db->query("SELECT SUM(total) AS first_term_total FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', 1);
                $db->execute();
                if($db->rowCount() > 0)
                {
                    $total = $db->single();
                    $pdf ->Cell(17,5, $total->first_term_total, 1,0,'C');
                }
                else{
                    $pdf ->Cell(17,5, 'N/A', 1,0,'C');
                }

                //Getting sessional total (1st + 2nd + 3rd)/3
                $db->query("SELECT AVG(total) AS sessional_avg FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id;");
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->execute();
                if($db->rowCount() > 0)
                {
                    $total = $db->single();
                    $pdf ->Cell(13,5, round(($total->sessional_avg), 2, PHP_ROUND_HALF_UP), 1,0,'C');
                }
                else{
                    $pdf ->Cell(13,5, 'N/A', 1,0,'C');
                }
                //TERM AVERAGE
                $db->query("SELECT AVG(total) AS average FROM result_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', $term_id);
                $db->execute();
                if ($db->rowCount() > 0)
                {
                    $average = $db->single()->average;
                    $pdf ->Cell(35,5,'AVERAGE = '.round(($average), 2, PHP_ROUND_HALF_UP),1,1,'C');
                }else{
                    $pdf ->Cell(35,5,'N/A', 1,1,'C');
                }
               
                $pdf ->Cell(190,5,'',1,1,'C');
                $pdf ->Cell(190,5,'',1,1,'C');
                $pdf ->Cell(190,5,'GRADES:  A1[80-100] B2[75-79] B3[70-74]  C4[65-69]  C5[60-64]  C6[50-59] D7[45-49] E8[40-44]  F9[0-39]',1,1,'C');
                $pdf ->Cell(190,5,'KEY NOTE: S-Avg [Sessional Average] GRD [Grade] REM [Remark] N/A [Not Available]',1,1,'C');
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
                $db->query("SELECT * FROM comments_tbl WHERE admNo = :admNo AND session_id = :session_id AND term_id = :term_id;");
                $db->bind(':admNo', $admNo);
                $db->bind(':session_id', $session_id);
                $db->bind(':term_id', $term_id);
                if ($db->execute()) {
                    if ($db->rowCount() > 0) {
                        $row = $db->resultset();
                        foreach ($row as $results) {
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
                            $pdf->Cell(53, 5, "CLASS TEACHER'S NAME", 1, 0, 'L');

                            //PRINCIPAL AUTO COMMENT TODO
                            if($average <= 39 )
                            {
                                $p_c = "This is a poor result, you can do more better";
                            } 
                            if (($average >= 40) || ($average >= 49)) {
                                $p_c = "This is below average, you can try harder";
                            }
                            if (($average >= 50) || ($average >= 59)) {
                                $p_c = "An average result, you can try harder";
                            }  
                            if (($average >= 60) || ($average >= 69)) {
                                $p_c = "Good result, you can do better";
                            }  
                            if (($average >= 70) || ($average >= 79)) {
                                $p_c = "Bravo! Do not give up";
                            }  
                            if (($average >= 80) || ($average >= 100)) {
                                $p_c = "Excellent! Keep the energy intact";
                            }
                            
                            //Getting class teacher name
                            $db->query(
                                "SELECT * FROM class_tbl AS tn
                                        JOIN staff_tbl ON staff_tbl.staff_id = tn.instructor_id
                                        WHERE tn.class_id = :class_id;"
                            );
                            $db->bind(':class_id', $class_id);
                            if ($db->execute()) {
                                if ($db->rowCount() > 0) {
                                    $result = $db->resultset();
                                    foreach ($result as $teacher) {
                                        $pdf->SetFont('Times', 'B', 9);
                                        $pdf->Cell(80, 5, $teacher->fname . " " . $teacher->sname . " " . $teacher->oname, 1, 1, 'L');
                                    }
                                } else {
                                    $pdf->SetFont('Times', 'B', 9);
                                    //$pdf ->Cell(80,5, "Teacher's not found",1,1,'L');
                                    $pdf->Cell(80, 5, "Teacher not found", 1, 1, 'L');
                                }
                            } else {
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
                            $db->query("SELECT next_term_begin FROM tbl_year_session WHERE session_id = :session_id AND term_id = :term_id;");
                            $db->bind(':session_id', $session_id);
                            $db->bind(':term_id', $term_id);                            
                            if ($db->execute()) {
                                if ($db->rowCount() > 0) {
                                    $nxt_term = $db->single()->next_term_begin;
                                    $pdf->SetFont('Times', '', 11);
                                    $pdf->Cell(80, 5, $nxt_term, 1, 1, 'L');
                                } else {
                                    $pdf->SetFont('Times', '', 11);
                                    $pdf->Cell(80, 5, 'Resumption date not scheduled', 1, 1, 'L');
                                }
                            } else {
                                die("Query failed".$db->getError());
                            }
                        }
                    } else {
                        $error = "No Teacher's comment";
                        $pdf->Cell(190, 5, $error, 1, 0, 'C');
                    }
                } else {
                    die('Error '.$db->getError());
                }
                //making it downloadable
                $downloadPDF = $admNo.' ('.$session_name.' - '.$term_name.')';
                $pdf->Output($downloadPDF.'.pdf', 'D');
            } else
            {
                echo "Term is annulled!";
                exit();
            }
        } else {
            echo "No result found";
        }
    } else {
        die($db->getError());
    }
    $db->Disconect();
}
/*****************************Making Downloadable ***********************************/
