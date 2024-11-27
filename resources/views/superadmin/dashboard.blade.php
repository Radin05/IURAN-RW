@extends('layouts.navbar')

@section('title', 'Super Admin Dashboard')

@section('content')
<h1>Super Admin Dashboard</h1>

@if($errors->has('access'))
    <div>{{ $errors->first('access') }}</div>
@endif

<p>Selamat datang, {{ Auth::user()->name }}</p>
<p>Fitur khusus untuk {{ Auth::user()->name }}:</p>
@endsection
