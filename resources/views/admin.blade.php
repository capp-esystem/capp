@extends('layouts.app')

@section('content')
<dashboard></dashboard>
@endsection

@push('scripts')
<script src="{{ asset('js/admin.js') }}"></script>
@endpush