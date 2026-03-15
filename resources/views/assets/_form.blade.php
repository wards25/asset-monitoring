@php
    $asset = $asset ?? null;
    $val = function($field, $default = '') use ($asset) {
        return old($field, ($asset ? $asset->$field : $default));
    };
    $dateVal = function($field) use ($asset) {
        $old = old($field);
        if ($old) return $old;
        if ($asset && $asset->$field) return $asset->$field->format('Y-m-d');
        return '';
    };
@endphp

<div class="form-card" style="border-radius:12px 12px 0 0;">
    <div class="form-card-header">
        <div class="form-card-title">ASSET INFORMATION</div>
    </div>
    <div class="form-card-body">
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Asset Type <span>*</span></label>
                <select name="type" class="form-select" required>
                    <option value="">Select type…</option>
                    @foreach(\App\Models\Asset::TYPES as $k => $v)
                    <option value="{{ $k }}" {{ $val('type') == $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
                @error('type')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Status <span>*</span></label>
                <select name="status" class="form-select" required>
                    @foreach(\App\Models\Asset::STATUSES as $k => $v)
                    @php $currentStatus = old('status', ($asset ? $asset->status : 'new')); @endphp
                    <option value="{{ $k }}" {{ $currentStatus == $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
                @error('status')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Brand <span>*</span></label>
                <input type="text" name="brand" class="form-input" value="{{ $val('brand') }}" placeholder="e.g. Dell, HP, Logitech" required>
                @error('brand')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Model</label>
                <input type="text" name="model" class="form-input" value="{{ $val('model') }}" placeholder="e.g. OptiPlex 7090">
            </div>

            <div class="form-group">
                <label class="form-label">Serial Number</label>
                <input type="text" name="serial_no" class="form-input" value="{{ $val('serial_no') }}" placeholder="Manufacturer serial no.">
            </div>

            <div class="form-group">
                <label class="form-label">Supplier / Vendor</label>
                <input type="text" name="supplier" class="form-input" value="{{ $val('supplier') }}" placeholder="e.g. DataBlitz, PC Express">
            </div>

            <div class="form-group">
                <label class="form-label">Purchase Cost (₱)</label>
                <input type="number" name="purchase_cost" class="form-input" step="0.01" min="0" value="{{ $val('purchase_cost') }}" placeholder="0.00">
            </div>

            <div class="form-group">
                <label class="form-label">Date Purchased</label>
                <input type="date" name="date_purchased" class="form-input" value="{{ $dateVal('date_purchased') }}">
            </div>
        </div>
    </div>
</div>

<div class="form-card" style="border-radius:0;border-top:none;">
    <div class="form-card-header">
        <div class="form-card-title">DEPLOYMENT INFORMATION</div>
    </div>
    <div class="form-card-body">
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Department</label>
                <select name="department" class="form-select">
                    <option value="">— Not Assigned —</option>
                    @foreach(\App\Models\Asset::DEPARTMENTS as $d)
                    <option value="{{ $d }}" {{ $val('department') == $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Assigned To (Current User)</label>
                <input type="text" name="assigned_to" class="form-input" value="{{ $val('assigned_to') }}" placeholder="Full name of current user">
            </div>

            <div class="form-group">
                <label class="form-label">Old / Previous User</label>
                <input type="text" name="old_user" class="form-input" value="{{ $val('old_user') }}" placeholder="Full name of previous user">
            </div>

            <div class="form-group">
                <label class="form-label">Date Deployed</label>
                <input type="date" name="date_deployed" class="form-input" value="{{ $dateVal('date_deployed') }}">
            </div>

            <div class="form-group full">
                <label class="form-label">Technical Specs</label>
                <textarea name="specs" class="form-textarea" placeholder="e.g. Intel Core i5-11400, 8GB RAM, 256GB SSD, Windows 11 Pro">{{ $val('specs') }}</textarea>
            </div>

            <div class="form-group full">
                <label class="form-label">Remarks / Notes</label>
                <textarea name="remarks" class="form-textarea" placeholder="Any issues, conditions, or notes about this asset…">{{ $val('remarks') }}</textarea>
            </div>
        </div>
    </div>
</div>