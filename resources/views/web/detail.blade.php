@extends('web.layout')
@section('content')
@php $r =   'web.details.detail' . $final_theme['detail']; @endphp
@include($r)
@endsection
