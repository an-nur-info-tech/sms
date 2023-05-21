<?php

require '../database/Database.php';
require '../assets/phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;


$db = new Database();

// STUDENT RECORD EXPORT
if (isset($_POST['export_btn'])) {

  $select_class = $_POST['select_class'];
  $f_format = $_POST['f_format'];

  if($select_class == "1")
  {
    $db->query("SELECT * FROM students_tbl;");
    if($db->execute())
    {
      if($db->rowCount() > 0)
      {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'CLASS NAME');
        $sheet->setCellValue('B1', 'ADM NO.');
        $sheet->setCellValue('C1', 'SURNAME');
        $sheet->setCellValue('D1', 'LAST NAME');
        $sheet->setCellValue('E1', 'OTHER NAME');
        $sheet->setCellValue('F1', 'NATIONALITY');
        $sheet->setCellValue('G1', 'STATE');
        $sheet->setCellValue('H1', 'LGA');
        $sheet->setCellValue('I1', 'GENDER');
        $sheet->setCellValue('J1', 'DOB');
        $sheet->setCellValue('K1', 'RELIGION');
  
        $count = 2;
        $data = $db->resultset();
        $filename = "All students Record";
        foreach($data as $row)
        {
          $sheet->setCellValue('A'.$count, $row->class_name);
          $sheet->setCellValue('B'.$count, $row->admNo);
          $sheet->setCellValue('C'.$count, $row->sname);
          $sheet->setCellValue('D'.$count, $row->lname);
          $sheet->setCellValue('E'.$count, $row->oname);
          $sheet->setCellValue('F'.$count, $row->nationality);
          $sheet->setCellValue('G'.$count, $row->student_state);
          $sheet->setCellValue('H'.$count, $row->lga);
          $sheet->setCellValue('I'.$count, $row->gender);
          $sheet->setCellValue('J'.$count, $row->dob);
          $sheet->setCellValue('K'.$count, $row->religion);
          $count++;
        }
  
        if($f_format == "xlsx")
        {
          $writer = new Xlsx($spreadsheet);
          $file_to_save = $filename.'.'.$f_format;
        }
        elseif($f_format == "xls")
        {
          $writer = new Xls($spreadsheet);
          $file_to_save = $filename.'.'.$f_format;
        }
        elseif($f_format == "csv")
        {
          $writer = new Csv($spreadsheet);
          $file_to_save = $filename.'.'.$f_format;
        }
        //$writer->save($file_to_save);
        //Define header information
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //header('Content-Disposition: attachment; filename="'.urlencode($file_to_save).'"');
        header('Content-Disposition: attachment; filename="'.$file_to_save.'"');
        if($writer->save('php://output')){
          header('Location: student-view-page');
        }
      }else{
          $_SESSION['errorMsg'] = true;
          $_SESSION['errorTitle'] = "Error";
          $_SESSION['sessionMsg'] = "No record found!";
          $_SESSION['sessionIcon'] = "error";
          $_SESSION['location'] = "student-view-page";
          die($db->getError());
      }
    }else{
      die($db->getError());
    }
  }
  else
  {
    $db->query("SELECT * FROM students_tbl WHERE class_name = :class_name;");
    $db->bind(':class_name', $select_class);
    if($db->execute())
    {
      if($db->rowCount() > 0)
      {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'CLASS NAME');
        $sheet->setCellValue('B1', 'ADM NO.');
        $sheet->setCellValue('C1', 'SURNAME');
        $sheet->setCellValue('D1', 'LAST NAME');
        $sheet->setCellValue('E1', 'OTHER NAME');
        $sheet->setCellValue('F1', 'NATIONALITY');
        $sheet->setCellValue('G1', 'STATE');
        $sheet->setCellValue('H1', 'LGA');
        $sheet->setCellValue('I1', 'GENDER');
        $sheet->setCellValue('J1', 'DOB');
        $sheet->setCellValue('K1', 'RELIGION');
  
        $count = 2;
        $data = $db->resultset();
        $filename = $select_class." Record";
        foreach($data as $row)
        {
          $sheet->setCellValue('A'.$count, $row->class_name);
          $sheet->setCellValue('B'.$count, $row->admNo);
          $sheet->setCellValue('C'.$count, $row->sname);
          $sheet->setCellValue('D'.$count, $row->lname);
          $sheet->setCellValue('E'.$count, $row->oname);
          $sheet->setCellValue('F'.$count, $row->nationality);
          $sheet->setCellValue('G'.$count, $row->student_state);
          $sheet->setCellValue('H'.$count, $row->lga);
          $sheet->setCellValue('I'.$count, $row->gender);
          $sheet->setCellValue('J'.$count, $row->dob);
          $sheet->setCellValue('K'.$count, $row->religion);
          $count++;
        }
  
        if($f_format == "xlsx")
        {
          $writer = new Xlsx($spreadsheet);
          $file_to_save = $filename.'.'.$f_format;
        }
        elseif($f_format == "xls")
        {
          $writer = new Xls($spreadsheet);
          $file_to_save = $filename.'.'.$f_format;
        }
        elseif($f_format == "csv")
        {
          $writer = new Csv($spreadsheet);
          $file_to_save = $filename.'.'.$f_format;
        }
        //$writer->save($file_to_save);
        //Define header information
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //header('Content-Disposition: attachment; filename="'.urlencode($file_to_save).'"');
        header('Content-Disposition: attachment; filename="'.$file_to_save.'"');
        if($writer->save('php://output')){
          header('Location: student-view-page');
        }
      }else{
          $_SESSION['errorMsg'] = true;
          $_SESSION['errorTitle'] = "Error";
          $_SESSION['sessionMsg'] = "No record found!";
          $_SESSION['sessionIcon'] = "error";
          $_SESSION['location'] = "student-view-page";
          die($db->getError());
      }
    }else{
      die($db->getError());
    }
  }
}

// SUBJECT EXPORT
if (isset($_POST['subjectXportBtn']))
{
  // $select_class = $_POST['select_class']; 
  $class_id = $_POST['select_class'];
  $subject_id = $_POST['subject_id'];
  $session_id = $_POST['session_id'];
  $term_id = $_POST['term_id'];

  // Get class name and subject name
  $db->query(
    "SELECT * FROM class_subject_tbl AS csn
    JOIN class_tbl ON class_tbl.class_id = csn.class_id
    JOIN subject_tbl ON subject_tbl.subject_id = csn.subject_id
    WHERE csn.subject_id = :subject_id;"
  );
  $db->bind(':subject_id', $subject_id);
  if ($db->execute())
  {
    if ($db->rowCount() > 0)
    {
      $data = $db->single();
      $cls_name = $data->class_name;
      $subject_name = $data->subject_name;
    }else{
      $cls_name = "Class";
      $subject_name = "Subject";
    }
  }
  
  

  $db->query("SELECT class_name FROM class_tbl WHERE class_id = :class_id;");
  $db->bind(':class_id', $class_id);
  if($db->execute())
  {
    if ($db->rowCount() > 0)
    {
      $result = $db->single();
      $class_name = $result->class_name;

      $db->query("SELECT admNo FROM students_tbl WHERE class_name = :class_name;");
      $db->bind(':class_name', $class_name);
      if($db->execute())
      {
        if ($db->rowCount() > 0)
        {
          $count = 2;
          $filename = "$cls_name $subject_name";

          $spreadsheet = new Spreadsheet();
          $sheet = $spreadsheet->getActiveSheet();

          $sheet->setCellValue('A1', 'CLASS ID');
          $sheet->setCellValue('B1', 'SESSION ID');
          $sheet->setCellValue('C1', 'TERM ID');
          $sheet->setCellValue('D1', 'SUBJECT ID');
          $sheet->setCellValue('E1', 'ADM. NO');
          $sheet->setCellValue('F1', 'C.A');
          $sheet->setCellValue('G1', 'EXAM');
    
          
          $data = $db->resultset();
          foreach($data as $row)
          {
            $sheet->setCellValue('A'.$count, $class_id);
            $sheet->setCellValue('B'.$count, $session_id);
            $sheet->setCellValue('C'.$count, $term_id);
            $sheet->setCellValue('D'.$count, $subject_id);
            $sheet->setCellValue('E'.$count, $row->admNo);
            $sheet->setCellValue('F'.$count, '');
            $sheet->setCellValue('G'.$count, '');

            $count++;
          }

          $writer = new Xlsx($spreadsheet);
          $file_to_save = $filename.'.xlsx';
          
          header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          //header('Content-Disposition: attachment; filename="'.urlencode($file_to_save).'"');
          header('Content-Disposition: attachment; filename="'.$file_to_save.'"');
          if($writer->save('php://output')){
            header('Location: excel-upload');
          }
        }
        else 
        {
          echo "No record found";
        }
      }
    }
    else 
    {
      echo "No class found";
    }
  }
}
$db->Disconect();