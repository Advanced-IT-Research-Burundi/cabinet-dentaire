@extends('layouts.app')

@section('title', 'Comptabilité')

@section('content')
<div id="compta-app" class="compta-mount"></div>
@endsection

@push('styles')
<style>
    .compta-mount {
        min-height: calc(100vh - 180px);
        margin: -1rem -12px 0;
    }
    @media (min-width: 768px) {
        .compta-mount {
            margin-left: -15px;
            margin-right: -15px;
        }
    }
</style>
@endpush

@push('scripts')
    @vite('resources/js/compta/src/main.js')
@endpush
