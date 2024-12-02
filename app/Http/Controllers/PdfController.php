<?php
namespace App\Http\Controllers;

use App\Models\PdfData;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PdfController extends Controller {
  public function generatePdf(Request $request) {
    $data = $request->only([
      'title',
      'rechnungNr',
      'kundenNr',
      'datum',
      'monat',
      'dienstleistungDatum',
      'stunden',
      'stundenlohn',
      'summe',
      'zzglMwst',
      'gesamtbetrag',
      'verwendungszweck',
    ]);

    // Save data to the database
    $pdfData = PdfData::create($data);

    // Generate PDF
    $pdf = Pdf::loadView('pdf.document', $data);
    return $pdf->download('document.pdf');
  }

  public function index() {
    $pdfData = PdfData::all();
    return Inertia::render('PdfData/Index', ['pdfData' => $pdfData]);
  }
}