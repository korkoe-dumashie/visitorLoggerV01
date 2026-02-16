{{-- @extends('errors::minimal')

@section('title', __('Too Many Requests'))
@section('code', '419')
@section('message', __('Too Many Requests')) --}}
@extends('errors.layout')

@section('title', 'Time`s Up!')
@section('message', 'Your session has expired like a forgotten potion. Refresh or log in again to continue your quest.')
