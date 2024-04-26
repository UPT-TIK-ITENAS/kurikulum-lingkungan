@extends('layouts.app')
@section('content-header')
    Dashboards
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="col-xl-12 col-12">
                <div class="card">
                    <div class="card-body">
                        <center>
                            <h3>Selamat Datang di
                                Sistem Kurikulum Itenas !</h3>
                        </center>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- Page JS -->
    <script src="{{ asset('js/form-layouts.js') }}"></script>
    <script src="{{ asset('vendor/libs/chartjs/chartjs.js') }}"></script>
@endpush
