@extends('layout.app')

@section('content')
    <h1>Sales</h1>
    <a href={{route('sales.create')}}>
        <button>Create new sale</button>
    </a>
    <h3>All Sales details</h3>
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
