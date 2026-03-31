@extends('layouts.app')

@section('title', __('nav_panel'))

@push('styles')
    @include('dashboard.partials.styles')
@endpush

@section('content')
<div class="panel-wrap">

    {{-- Üst Kısım ve Promosyon --}}
    @include('dashboard.partials.header')

    {{-- İstatistik Kartları --}}
    @include('dashboard.partials.stats')

    {{-- Sekmeler Alanı --}}
    <div class="tab-section">
        <div class="tab-nav-wrapper">
            <nav class="tab-nav">
                <button class="tab-btn active" onclick="switchTab(event, 'tab-history')">{{ __('tab_studio') }}</button>
                <button class="tab-btn" onclick="switchTab(event, 'tab-transactions')">{{ __('tab_transactions') }}</button>
                <button class="tab-btn" onclick="switchTab(event, 'tab-domains')">{{ __('tab_domains') }}</button>
                <button class="tab-btn" onclick="switchTab(event, 'tab-credits')">{{ __('tab_credits') }}</button>
            </nav>
        </div>

        {{-- Sekme İçerikleri --}}
        @include('dashboard.tabs.history')
        @include('dashboard.tabs.transactions')
        @include('dashboard.tabs.domains')
        @include('dashboard.tabs.credits')
    </div>

</div>

<div id="tab-index" class="tab-content">
    
    <div id="index-ios-menu" class="index-preview-hidden">
        <button class="close-preview-btn" onclick="closeIndexMenu()">&times;</button>
        
        <div class="preview-content">
            <img id="index-preview-img" src="" alt="Seçilen Görsel">
            <div class="preview-details">
                <h3>Görsel Detayları</h3>
                <p id="index-preview-desc">Açıklama yükleniyor...</p>
            </div>
        </div>
    </div>

</div>

{{-- Modallar (Açılır Pencereler) --}}
@include('dashboard.modals.tryon')

@endsection

@push('scripts')
    @include('dashboard.partials.scripts')
@endpush