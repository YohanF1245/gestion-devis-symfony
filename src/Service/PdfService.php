<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Response;

class PdfService
{
    private $domPdf;

    public function __construct(){
        $this->domPdf = new DomPdf();

        $pdfOptions = new Options();

        $pdfOptions->set('defaultFont', 'Garamond');
        $pdfOptions->set('isRemoteEnabled', true);

        $this->domPdf->setOptions($pdfOptions);
    }

    public function showPdfFile($html)
    {
        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        $this->domPdf->stream("devis.pdf", ['Attachment' =>false]);
    }
    
    public function generateBinaryPDF($html) {
        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        $this->domPdf->output();
    }
}
?>