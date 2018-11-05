@extends('layouts.app')

@section('content')
<main-app></main-app>
@endsection

@push('scripts')
<script src="{{ asset('js/student.js') }}"></script>
@endpush