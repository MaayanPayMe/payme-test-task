@extends('layout.app')

@section('content')
    <h1>All Sales</h1>
    <ul>
        @foreach($sales as $sale)
            <li>
                Product name: {{ $sale->product_name }} <br/>
                Price: {{ $sale->price }} <br/>
                Currency: {{ $sale->currency }} <br/>
                Created at: {{ $sale->created_at }}
            </li>
        @endforeach
    </ul>
@show
