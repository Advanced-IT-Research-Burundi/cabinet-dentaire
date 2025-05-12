@extends('errors.errors')

@section('code', '404')
@section('title', 'Page introuvable')
@section('message')
    <p>Désolé, la page que vous recherchez n'existe pas ou a été déplacée.</p>
    <p>Veuillez vérifier l'URL ou utiliser les liens ci-dessous pour vous rediriger.</p>
@endsection
