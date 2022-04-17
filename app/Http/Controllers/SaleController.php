<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('Sales.newSalePage');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'productName' => 'required',
            'price' => 'required|numeric|between:0,9999.99',
            'currency' => 'required',
        ]);

        $sale = new Sale;
        $sale->product_name = $request->productName;
        $sale->price = $request->price;
        $sale->currency = $request->currency;
        $sale->save();

        return redirect('/');
    }
}
