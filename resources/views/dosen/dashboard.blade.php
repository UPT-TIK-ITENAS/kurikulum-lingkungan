@extends('layouts.app')
@section('content-header')
    Dashboards
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="col-xl-12 col-12">
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- Page JS -->
    <script src="{{ asset('js/form-layouts.js') }}"></script>
    <script src="{{ asset('vendor/libs/chartjs/chartjs.js') }}"></script>
@endpush
