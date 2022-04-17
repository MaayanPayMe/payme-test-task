<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $sales = Sale::all();

        return view("sales.index", compact("sales"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view("sales.newSalePage");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "productName" => "required",
            "price"       => "required|numeric|between:100,9999.99",
            "currency"    => "required",
        ]);

        $sale = new Sale;
        $sale->product_name = $request->productName;
        $sale->price = $request->price;
        $sale->currency = $request->currency;
        $sale->save();

        $response = Http::post("https://preprod.paymeservice.com/api/generate-sale", [
            "seller_payme_id" => "MPL14985-68544Z1G-SPV5WK2K-0WJWHC7N",
            "sale_price"      => $request->price,
            "currency"        => $request->currency,
            "product_name"    => $request->productName,
            "installments"    => "1",
            "language"        => "en"
        ]);

        $paymentUrl = json_decode($response->getBody())->sale_url;

        return view("sales.paymentPage", compact("paymentUrl"));
    }
}
