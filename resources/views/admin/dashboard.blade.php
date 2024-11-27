@extends('layouts.navbar')

@section('title', 'Admin Dashboard')

@section('content')
<h1>Admin Dashboard</h1>

@if($errors->has('access'))
    <div>{{ $errors->first('access') }}</div>
@endif

<p>Selamat datang, {{ Auth::user()->name }}</p>
<p>Fitur khusus untuk {{ Auth::user()->name }}:</p>
<ul>
    {{-- <li><a href="{{ route('tambah.user') }}">Kelola User</a></li>
    <li><a href="{{ route('user.cashes') }}">Kelola Uang Kas</a></li> --}}
</ul>
@endsection
