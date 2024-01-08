<?php

namespace App\Services\Fpdf;

use App\Models\Entreprise;
use App\Models\FunctionalUnit;
use App\Models\SalesInvoice;
use Codedge\Fpdf\Fpdf\Fpdf;

class InvoicePdf extends Fpdf{
    protected $entreprise;
    protected $functionalUnit;
    protected $invoice;
    //
    public function __construct($entreprise, $functionalUnit, $invoice)
    {
        parent::__construct();
        $this->entreprise = $entreprise;
        $this->functionalUnit = $functionalUnit;
        $this->invoice = $invoice;
    }

    // Page header
    public function Header()
    {
        // Logo
        $folderPath = public_path() . '/assets/img/logo/entreprise/' . $this->entreprise->url_logo . '.png';
        $this->Image($folderPath,1,0,60);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Line Break
        $this->Cell(0,15,'',0,1,'C');
        $this->Ln();
    }

    function createTable($header,$data){
        $width=[10, 80, 30, 40, 40];
        $this->SetFillColor(3,161,252);
        $this->SetTextColor(255,255,255);
        $this->SetDrawColor(3,161,252);
        for($i=0;$i<count($header);$i++)
        {
          $this->Cell($width[$i],7,$header[$i],1,0,'C',true);
        }

        $this->Ln();
        $this->SetFillColor(215,244,247);
        $this->SetTextColor(0,0,0);
        $fill=true;
        foreach($data as $row)
        {
            $this->SetXY(10, 112);
            $this->MultiCell($width[0],30,$row[0],1,0,'L',$fill);

            $this->SetXY(20, 112);
            $this->MultiCell($width[1],30,$row[1],1,0,'L',$fill);

            $this->SetXY(100, 112);
            $this->MultiCell($width[2],7,$row[2],1,0,'L',$fill);

            $this->SetXY(130, 112);
            $this->MultiCell($width[3],7,$row[3],1,0,'L',$fill);

            $this->SetXY(170, 112);
            $this->MultiCell($width[4],7,$row[4],1,0,'L',$fill);
            $this->Ln();
            $fill=!$fill;
        }
    }

    // Page footer
    /*public function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }*/
}