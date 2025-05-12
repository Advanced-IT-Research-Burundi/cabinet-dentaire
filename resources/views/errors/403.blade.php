@extends('errors.errors')

@section('code', '403')
@section('title', 'Accès refusé')
@section('message')
    <p>Vous n'avez pas les permissions nécessaires pour accéder à cette page.</p>
    <p>Si vous pensez qu'il s'agit d'une erreur, veuillez contacter l'administrateur du système.</p>
@endsection
