@extends('layouts.app')

@section('content')
<div class="text-center">
    <h3>Scan untuk Self Order</h3>
    <div class="my-4">
        {!! QrCode::size(250)->generate($url) !!}
    </div>
    <p><strong>Link: </strong> <a href="{{ $url }}" target="_blank">{{ $url }}</a></p>
</div>
@endsection
