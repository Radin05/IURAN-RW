@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<h1>User Dashboard</h1>

@if($errors->has('access'))
    <div>{{ $errors->first('access') }}</div>
@endif

<p>Selamat datang, {{ Auth::user()->name }}!</p>
<p>Fitur Anda:</p>
<ul>

</ul>
@endsection
