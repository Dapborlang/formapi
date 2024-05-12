@extends('layouts.app')

@section('content')
<div class="container">
    <index-component :formid="{{$id}}"></index-component>
</div>
@endsection
