<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

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
     * @param Request $request
     * @return Application
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "productName" => "required",
            "price"       => "required|numeric|between:100,9999.99",
            "currency"    => "required",
        ]);

        $productName = $request->productName;
        $price = $request->price;
        $currency = $request->currency;

        try {
            $paymentUrl = $this->getPaymentPage($productName, $price, $currency);
            $this->createNewSale($productName, $price, $currency);

            return view("sales.paymentPage", compact("paymentUrl"));
        } catch (Exception $exception) {
            $errorMsg = $exception->getMessage();

            return view("sales.errorPage", compact("errorMsg"));
        }
    }

    private function createNewSale($productName, $price, $currency)
    {
        $sale = new Sale;
        $sale->product_name = $productName;
        $sale->price = $price;
        $sale->currency = $currency;
        $sale->save();
    }

    /**
     * @throws Exception
     */
    private function getPaymentPage($productName, $price, $currency)
    {
        $response = Http::post("https://preprod.paymeservice.com/api/generate-sale", [
            "seller_payme_id" => "MPL14985-68544Z1G-SPV5WK2K-0WJWHC7N",
            "sale_price"      => $price,
            "currency"        => $currency,
            "product_name"    => $productName,
            "installments"    => "1",
            "language"        => "en"
        ]);

        $decodeResponse = json_decode($response);

        if (!$decodeResponse->status_code) {
            return $decodeResponse->sale_url;
        }

        throw new Exception($decodeResponse->status_error_details);
    }
}
