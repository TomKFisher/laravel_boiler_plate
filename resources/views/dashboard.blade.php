@extends('layouts.blank')

@push('stylesheets')
@endpush

@section('page_header')
@endsection

@section('main_container')
    {{ Widget::group('dashboard')->display() }}
@endsection

@push('scripts')
@endpush