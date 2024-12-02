<?php
namespace App\Http\Controllers;

use App\Models\PdfData;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PdfController extends Controller {
  public function generatePdf(Request $request, $id = null) {
    if ($id) {
      $pdfData = PdfData::findOrFail($id);
      $data = $pdfData->toArray();
    } else {
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

      $pdfData = PdfData::create($data);
    }

    $pdf = Pdf::loadView('pdf.document', $data);
    return $pdf->download('document.pdf');
  }

  public function index() {
    $pdfData = PdfData::all();
    return Inertia::render('PdfData/Index', ['pdfData' => $pdfData]);
  }
}