@extends('layouts.app')

@section('title', 'Results')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Import XML</div>

                    <div class="card-body">
                        <a href="{{ route('import.index')  }}" class="button">Start over</a>
                        <br />
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="success text-center">Inserted</th>
                                <th class="info text-center">Updated</th>
                                <th class="danger text-center">Failed</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="success text-center">{{ $log->inserted }}</td>
                                <td class="info text-center">{{ $log->updated }}</td>
                                <td class="danger text-center">{{ $log->failed }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($log->failed > 0)
                    <div class="card">
                        @if($log->completed != true)
                            <div class="card-header">The import has failed </div>
                        @endif
                        <div class="card-header">Error report</div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-right">Store number</th>
                                    <th class="text-left">Tag</th>
                                    <th class="text-left">Message</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($log->errors as $error)
                                        <tr>
                                            <td class="success text-right">{{ $error->store_number }}</td>
                                            <td class="text-left">{{ $error->column_name }}</td>
                                            <td class="text-left">{{ $error->description }}</td>
                                        </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    @endif
@endsection
