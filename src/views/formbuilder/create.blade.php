@extends('layouts.app')

@section('content')
<div class="container">
    <create-component :formid="{{$id}}"></create-component>
</div>
@endsection
