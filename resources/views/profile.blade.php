@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Profile</h1>

<p><strong>Name:</strong> {{ auth()->user()->name }}</p>
<p><strong>Email:</strong> {{ auth()->user()->email }}</p>

@endsection
