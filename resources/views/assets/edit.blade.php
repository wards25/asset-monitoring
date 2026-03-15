@extends('layouts.app')
@section('title', 'Edit Asset — AssetTrack')
@section('breadcrumb', 'Assets / Edit / ' . $asset->sticker_no)

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Edit Asset</div>
        <div class="page-subtitle">
            <span class="sticker-no">{{ $asset->sticker_no }}</span>
            &nbsp;&mdash;&nbsp;{{ $asset->brand }} {{ $asset->model }}
        </div>
    </div>
    <div class="page-actions">
        <a href="{{ route('assets.show', $asset) }}" class="btn btn-outline">← View</a>
        <a href="{{ route('assets.barcode', $asset) }}" class="btn btn-outline">Print Barcode</a>
    </div>
</div>

<form method="POST" action="{{ route('assets.update', $asset) }}">
    @csrf @method('PUT')
    @include('assets._form')
    <div class="form-footer" style="margin-top:0;border-radius:0 0 12px 12px;">
        <a href="{{ route('assets.show', $asset) }}" class="btn btn-outline">Cancel</a>
        <button type="submit" class="btn btn-primary">
            <svg viewBox="0 0 20 20" fill="none"><path d="M4 10l4 4 8-8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Update Asset
        </button>
    </div>
</form>
@endsection