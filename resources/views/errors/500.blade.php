@extends('errors.errors')

@section('code', '500')
@section('title', 'Erreur serveur')
@section('message')
    <p>Nous rencontrons un problème technique avec notre serveur.</p>
    <p>Notre équipe a été informée et travaille à la résolution du problème. Veuillez réessayer dans quelques instants.</p>
@endsection
