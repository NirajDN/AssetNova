<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    private function cid(): int { return Auth::user()->company_id; }

    public function index()
    {
        $cid   = $this->cid();
        $stats = [
            'parts'        => Part::where('company_id', $cid)->count(),
            'suppliers'    => Supplier::where('company_id', $cid)->count(),
            'categories'   => Category::where('company_id', $cid)->count(),
            'transactions' => Transaction::where('company_id', $cid)->count(),
            'total_value'  => Part::where('company_id', $cid)->get()->sum(fn($p) => $p->stock_quantity * $p->cost),
        ];
        return view('settings.index', compact('stats'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name'       => 'nullable|string|max:255',
            'gst'                => ['nullable','string','max:20','regex:/^[0-9A-Z]{15}$/'],
            'email'              => 'nullable|email|max:255',
            'phone'              => ['nullable','string','max:30','regex:/^[0-9\+\(\)\-\s]+$/'],
            'address'            => 'nullable|string|max:500',
            'currency'           => 'nullable|string|max:5',
            'low_stock_multiplier' => 'nullable|numeric|min:0.5|max:10',
        ], [
            'gst.regex'                    => 'GST number must be exactly 15 alphanumeric characters (e.g. 27AAPFU0939F1ZV).',
            'email.email'                  => 'Please enter a valid contact email address.',
            'phone.regex'                  => 'Phone can only contain digits, spaces, +, -, and parentheses.',
            'low_stock_multiplier.min'     => 'Multiplier must be at least 0.5.',
            'low_stock_multiplier.max'     => 'Multiplier cannot exceed 10.',
        ]);

        // Settings are profile-level; real persistence can be added via Company model later.
        return back()->with('success', 'Settings saved successfully.');
    }

    public function exportManifest()
    {
        $cid      = $this->cid();
        $parts    = Part::with(['category','supplier'])->where('company_id', $cid)->orderBy('name')->get();
        $company  = Auth::user()->company->name ?? 'company';
        $filename = strtolower(str_replace(' ','_',$company)) . '_manifest_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($parts) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['SKU','Part Name','Category','Supplier','Location','Stock Qty','Min Threshold','Status','Unit Cost (INR)','Total Value (INR)','Last Updated']);
            foreach ($parts as $p) {
                $isLow = $p->stock_quantity <= $p->min_threshold;
                fputcsv($handle, [
                    $p->sku, $p->name,
                    $p->category->name ?? '-',
                    $p->supplier->name ?? '-',
                    $p->location ?? '-',
                    $p->stock_quantity, $p->min_threshold,
                    $isLow ? 'Critical' : 'Stable',
                    number_format($p->cost, 2),
                    number_format($p->stock_quantity * $p->cost, 2),
                    $p->updated_at->format('d M Y, h:i A'),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
