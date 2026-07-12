<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
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

    public function pdf(Request $request): View
    {
        [$from, $to, $groupBy] = $this->range($request);
        $data = $this->service->financialReport($from, $to, $groupBy);

        return view('reports.pdf', $data);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
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
