@extends('layouts.app')

@section('content')
<div class="container">
    <edit-component :formid="{{$fid}}" :id="{{$id}}"></edit-component>
</div>
@endsection
