@extends('layouts.app')
@section('content-header')
    <h4 class="py-3 breadcrumb-wrapper mb-4">
        Matriks CPL - CPMK
    </h4>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="matriks_ce" class="table table-bordered">
                        <thead>
                            <th></th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- Page JS -->
    <script src="{{ asset('js/form-layouts.js') }}"></script>
@endpush
