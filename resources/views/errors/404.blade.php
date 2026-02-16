{{-- @extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', __('Not Found')) --}}


{{-- resources/views/errors/404.blade.php --}}
@extends('errors.layout')

@section('title', 'Lost in the Void')
@section('message', 'The page you’re looking for has vanished into another dimension. Try searching or go back.')
