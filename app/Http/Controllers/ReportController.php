<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use App\Services\ActivityLogger;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as ResponseFactory;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function __construct(protected ReportService $service) {}

    protected function range(Request $request): array
    {
        $from = $request->filled('from')
            ? Carbon::parse($request->get('from'))->startOfDay()
            : now()->startOfMonth();
        $to = $request->filled('to')
            ? Carbon::parse($request->get('to'))->endOfDay()
            : now()->endOfDay();
        $groupBy = in_array($request->get('group_by'), ['day', 'month', 'year']) ? $request->get('group_by') : 'month';

        return [$from, $to, $groupBy];
    }

    public function index(Request $request): View
    {
        [$from, $to, $groupBy] = $this->range($request);
        $data = $this->service->financialReport($from, $to, $groupBy);

        return view('reports.index', $data);
    }

    public function pdf(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        [$from, $to, $groupBy] = $this->range($request);
        $data = $this->service->financialReport($from, $to, $groupBy);

        return Pdf::loadView('reports.pdf', $data)
            ->setPaper('a4', 'portrait')
            ->download('laporan-bisnis-'.$from->format('Ymd').'-'.$to->format('Ymd').'.pdf');
    }

    public function finance(Request $request): View
    {
        [$from, $to] = $this->range($request);
        $data = $this->service->financeAdvanced($from, $to);

        return view('reports.finance', $data);
    }

    public function operational(Request $request): View
    {
        [$from, $to] = $this->range($request);

        return view('reports.operational', $this->service->operationalReport($from, $to));
    }

    public function operationalPdf(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        [$from, $to] = $this->range($request);
        $data = $this->service->operationalReport($from, $to);

        return Pdf::loadView('reports.operational-pdf', $data)
            ->setPaper('a4', 'portrait')
            ->download('laporan-operasional-'.$from->format('Ymd').'-'.$to->format('Ymd').'.pdf');
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        ActivityLogger::log('report_exported', null, 'Export laporan pembayaran.');
        [$from, $to] = $this->range($request);
        $payments = $this->service->recentPayments($from, $to, 100000);

        $filename = 'laporan-pembayaran-'.$from->format('Ymd').'-'.$to->format('Ymd').'.csv';

        return ResponseFactory::streamDownload(function () use ($payments) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, ['No. Invoice', 'Tanggal', 'Pasien', 'Metode', 'Subtotal', 'Diskon', 'Pajak', 'Total', 'Dibayar', 'Status']);
            foreach ($payments as $p) {
                fputcsv($out, [
                    $p->invoice_number,
                    $p->created_at?->format('Y-m-d H:i'),
                    $p->patient?->name ?? $p->appointment?->patient?->name ?? '-',
                    $p->payment_method,
                    $p->subtotal,
                    $p->discount,
                    $p->tax,
                    $p->amount,
                    $p->paid_amount,
                    $p->status,
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
