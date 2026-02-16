{{-- @extends('errors::minimal')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message', __('Unauthorized')) --}}


{{-- resources/views/errors/404.blade.php --}}
@extends('errors.layout')

@section('title', 'Locked Gate')
@section('message', 'You need the right key to enter this area. Log in or request access!')
