<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::query();

        if ($request->search) {
            $query->search($request->search);
        }
        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->department) {
            $query->where('department', $request->department);
        }

        $assets = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $stats = Asset::selectRaw('status, count(*) as total')
            ->groupBy('status')->pluck('total', 'status');

        $typeCounts = Asset::selectRaw('type, count(*) as total')
            ->groupBy('type')->pluck('total', 'type');

        return view('assets.index', compact('assets', 'stats', 'typeCounts'));
    }

    public function create()
    {
        return view('assets.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type'           => 'required|in:' . implode(',', array_keys(Asset::TYPES)),
            'brand'          => 'required|string|max:100',
            'model'          => 'nullable|string|max:100',
            'serial_no'      => 'nullable|string|max:100',
            'status'         => 'required|in:' . implode(',', array_keys(Asset::STATUSES)),
            'department'     => 'nullable|string|max:100',
            'assigned_to'    => 'nullable|string|max:150',
            'old_user'       => 'nullable|string|max:150',
            'date_purchased' => 'nullable|date',
            'date_deployed'  => 'nullable|date',
            'purchase_cost'  => 'nullable|numeric|min:0',
            'supplier'       => 'nullable|string|max:150',
            'specs'          => 'nullable|string',
            'remarks'        => 'nullable|string',
        ]);

        // Generate sticker no using new dept-based convention
        $data['sticker_no'] = Asset::generateStickerNo($data['type'], $data['department'] ?? null);

        // Generate QR data string
        $data['qr_data'] = Asset::generateQrData(
            $data['sticker_no'],
            $data['type'],
            $data['brand'],
            $data['department'] ?? null
        );

        $asset = Asset::create($data);

        return redirect()->route('assets.show', $asset)
            ->with('success', "Asset {$asset->sticker_no} created successfully.");
    }

    public function show(Asset $asset)
    {
        return view('assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    public function update(Request $request, Asset $asset)
    {
        $data = $request->validate([
            'type'           => 'required|in:' . implode(',', array_keys(Asset::TYPES)),
            'brand'          => 'required|string|max:100',
            'model'          => 'nullable|string|max:100',
            'serial_no'      => 'nullable|string|max:100',
            'status'         => 'required|in:' . implode(',', array_keys(Asset::STATUSES)),
            'department'     => 'nullable|string|max:100',
            'assigned_to'    => 'nullable|string|max:150',
            'old_user'       => 'nullable|string|max:150',
            'date_purchased' => 'nullable|date',
            'date_deployed'  => 'nullable|date',
            'purchase_cost'  => 'nullable|numeric|min:0',
            'supplier'       => 'nullable|string|max:150',
            'specs'          => 'nullable|string',
            'remarks'        => 'nullable|string',
        ]);

        // Regenerate QR data if brand/dept changed
        $data['qr_data'] = Asset::generateQrData(
            $asset->sticker_no,
            $data['type'],
            $data['brand'],
            $data['department'] ?? null
        );

        $asset->update($data);

        return redirect()->route('assets.show', $asset)
            ->with('success', "Asset {$asset->sticker_no} updated successfully.");
    }

    public function destroy(Asset $asset)
    {
        $stickerNo = $asset->sticker_no;
        $asset->delete();
        return redirect()->route('assets.index')
            ->with('success', "Asset {$stickerNo} deleted.");
    }

    public function barcode(Asset $asset)
    {
        return view('assets.barcode', compact('asset'));
    }

    public function scan()
    {
        return view('assets.scan');
    }

    public function lookup(Request $request)
    {
        $code = $request->get('code');

        // Support scanning sticker_no directly or JSON QR data
        $asset = null;

        // Try parsing as JSON (QR code format)
        $decoded = json_decode($code, true);
        if ($decoded && isset($decoded['sticker'])) {
            $asset = Asset::where('sticker_no', $decoded['sticker'])->first();
        }

        // Fallback: direct sticker_no match
        if (!$asset) {
            $asset = Asset::where('sticker_no', $code)
                ->orWhere('sticker_no', strtoupper($code))
                ->first();
        }

        if (!$asset) {
            return response()->json(['found' => false, 'message' => 'Asset not found.'], 404);
        }

        return response()->json([
            'found' => true,
            'asset' => [
                'id'            => $asset->id,
                'sticker_no'    => $asset->sticker_no,
                'type'          => $asset->getTypeLabel(),
                'type_code'     => $asset->getTypeCode(),
                'dept_code'     => $asset->getDeptCode(),
                'brand'         => $asset->brand,
                'model'         => $asset->model,
                'status'        => $asset->status,
                'status_label'  => $asset->getStatusLabel(),
                'department'    => $asset->department,
                'assigned_to'   => $asset->assigned_to,
                'date_deployed' => $asset->date_deployed ? $asset->date_deployed->format('M d, Y') : null,
                'url'           => route('assets.show', $asset),
            ]
        ]);
    }

    public function bulkBarcode(Request $request)
    {
        $ids = $request->get('ids', []);
        $assets = Asset::whereIn('id', $ids)->get();
        return view('assets.barcode-bulk', compact('assets'));
    }
}