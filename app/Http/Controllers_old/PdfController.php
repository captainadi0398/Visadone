<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Mail;

class PdfController extends Controller
{
    //
    public function generatePdf()
    {
        // Generate the PDF content or prepare the data to be displayed in PDF format.
        $pdfContent = "<h1>Hello, this is a PDF!</h1><p>PDF content goes here.</p>";

        // Initialize the PDF library (dompdf in this case)
        $dompdf = new Dompdf();

        // Load the PDF content
        $dompdf->loadHtml($pdfContent);

        // (Optional) Set paper size and orientation (e.g., A4 portrait)
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        // Output the PDF to the user in a new tab
        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="example.pdf"');
    }

    public function sendPdf(){

    }
}
