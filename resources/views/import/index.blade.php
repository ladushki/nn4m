@extends('layouts.app')

@section('title', 'Import Xml')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Import XML</div>

                    <div class="card-body">
                    {!! form($form) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
