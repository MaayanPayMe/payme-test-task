@extends('layout.app')

@section('content')
    <h1>Payment Page</h1>

    <a href={{route('sales.index')}}>
        <button>Back</button>
    </a>
    <br/>
    <iframe width="600"
            height="450"
            src={{ $paymentUrl }}>
    </iframe>
@show
