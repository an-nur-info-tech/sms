<?php
if(isset($_POST['view_class_btn']))
{
    //Include Library
    //require_once('includes/dbcon.php');
    require_once('includes/fpdf8/fpdf.php');
     

    class PDF extends FPDF
    {
    // Page header
    function Header()
    {
        // Logo
        $this->Image('img/logoPdf.png',10,6,30);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(30,10,'Title',1,0,'C');
        // Line break
        $this->Ln(20);
    }
    
    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
    }
    
    // Instanciation of inherited class
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Times','',12);
    for($i=1;$i<=40;$i++)
        $pdf->Cell(0,10,'Printing line number '.$i,0,1);
    $pdf->Output();
    

    /*   
    //Make a FPDF object
    $pdf = new FPDF('P', 'mm', 'A4');
    
    //Set title
    $pdf ->SetTitle('SUCCESS SCHOOLS SOKOTO');
    
    //Add Page
    $pdf ->AddPage();

    //Add image logo
    $pdf ->Image('img/logoPdf.png', 9,6,35,30);
    //Page Heading
    $pdf ->SetFont('times', 'B', 27);
    $pdf ->Cell(210,6, 'SUCCESS SCHOOLS SOKOTO', 0, 0,'C');
    $pdf ->ln();
    $pdf ->SetFont('Times','B', 14);
    $pdf ->Cell(200,10,'Report sheet for first term session 2020/2021',0,1,'C');
    //Add image logo
    $pdf ->Image('img/logoPdf.png', 160,50,40,50);
    $pdf ->ln(15);
    
    
    
    $pdf ->SetFont('times', 'B', 15);
    $pdf ->Cell(120, 10, 'Name: IBRAHIM BELLO ', 1, 1);
    $pdf ->Cell(120, 10, 'CLASS: SS 2 YELLOW ', 1, 1);
    $pdf ->Cell(120, 10, 'GENDER: IBRAHIM BELLO ', 1, 1);
    $pdf ->Cell(120, 10, 'POSITION:  ', 1, 1);
    
    $pdf ->Ln();
    //Add content
    //Using Cell

    //Add content using html cell
    //$html = "<h1 class='text-danger'>Hello world</h1>";
    //$pdf ->writeHTMLCell(0,0,'','', $html, 1,0);

    //Add content
    
    //Output
    $pdf ->Output();
  
    
    /*class PDF extends FPDF{
        function footer(){
            $this-> SetY(-20);
            $this-> SetFont('Arial', 'I', 8);
            $this->Cell(0,5,'Page'.$this->PageNo().'/{nb}',0,1,'C');
            $this-> SetY(-20);
            $this->Cell(0,7,'(Date printed: '.date('d-m-Y').')',0,0,'R');
        }

       
    }
    
    //$image = $result['img'];
    //Create PDF
    $pdf = new PDF('P', 'mm', 'A4');
    $pdf -> AliasNbPages();
    $pdf -> AddPage();
    
    $title = "SUCCESS SCHOOLS SOKOTO";
    $pdf ->SetTitle($title);
    $pdf -> Output();
*/
        
}
