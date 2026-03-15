@extends('layouts.app')
@section('title', 'Add Asset — AssetTrack')
@section('breadcrumb', 'Assets / Add New')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Add New Asset</div>
        <div class="page-subtitle">Sticker number and barcode will be auto-generated</div>
    </div>
    <a href="{{ route('assets.index') }}" class="btn btn-outline">← Back</a>
</div>

<form method="POST" action="{{ route('assets.store') }}">
    @csrf
    @include('assets._form')
    <div class="form-footer" style="margin-top:0;border-radius:0 0 12px 12px;">
        <a href="{{ route('assets.index') }}" class="btn btn-outline">Cancel</a>
        <button type="submit" class="btn btn-primary">
            <svg viewBox="0 0 20 20" fill="none"><path d="M4 10l4 4 8-8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Create Asset
        </button>
    </div>
</form>
@endsection