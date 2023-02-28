@extends('web.layout')
@section('content')
<!-- Site Content -->
@php $r =   'web.blogs.blog' . $final_theme['blog']; @endphp
@include($r)
@endsection
