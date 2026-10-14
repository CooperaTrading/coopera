@extends('layouts.admin')

@section('title', 'New Hero Slide')

@section('header', 'New Hero Slide')
@section('subheader', 'Add a new slide to the home page hero carousel')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
        @include('admin.hero-slides._form')
    </div>
</div>
@endsection
