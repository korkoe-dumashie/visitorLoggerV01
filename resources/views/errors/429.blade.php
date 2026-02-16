{{-- @extends('errors::minimal')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('message', __('Too Many Requests')) --}}


@extends('errors.layout')

@section('title', 'Cool-down Active!')
@section('message', 'Whoa, slow down! You’ve used too many moves. Wait a few seconds and try again.')
