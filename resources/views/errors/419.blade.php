@extends('errors.errors')

@section('code', '419')
@section('title', 'Session expirée')
@section('message')
    <p>Votre session a expiré. Cela peut se produire pour des raisons de sécurité après une période d'inactivité.</p>
    <p>Veuillez actualiser la page et réessayer. Si vous tentiez de soumettre un formulaire, vous devrez peut-être saisir à nouveau vos informations.</p>
@endsection
