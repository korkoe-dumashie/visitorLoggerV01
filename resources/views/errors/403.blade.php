{{-- @extends('errors::minimal')

@section('title', __('No Entry – VIP Only'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))
 --}}

@extends('errors.layout')

@section('title', 'No Entry – VIP Only')
@section('message', 'You don’t have permission to view this page. Contact an admin if you think this is a mistake.')
