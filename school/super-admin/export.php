<?php

require '../database/Database.php';
require '../assets/phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

if (isset($_POST['export_btn'])) {
  $db = new Database();

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
  $db->Disconect();
}