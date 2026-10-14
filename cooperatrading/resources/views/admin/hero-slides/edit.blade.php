@extends('layouts.admin')

@section('title', 'Edit Hero Slide')

@section('header', 'Edit Hero Slide')
@section('subheader', $slide->title)

@section('content')
<div class="max-w-3xl">
    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
        @include('admin.hero-slides._form')
    </div>

    <form id="delete-slide-form" action="{{ route('admin.hero-slides.destroy', $slide) }}" method="POST" class="hidden" onsubmit="return confirm('Delete this slide permanently? Tip: you can hide it from the home page instead.');">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection
