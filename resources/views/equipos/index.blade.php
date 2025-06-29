@extends('layouts.app')

@section('content')
    <div id="app">
        <equipos-index></equipos-index>
    </div>
@endsection

@section('scripts')
    <script src="{{ mix('js/app.js') }}"></script>
@endsection