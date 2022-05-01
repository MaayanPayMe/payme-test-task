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
    static string $sellerPayMeId = "MPL14985-68544Z1G-SPV5WK2K-0WJWHC7N";

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
     * @return Application|Factory|View
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validateSale($request);

        try {
            $newSale = new Sale([], $request->productName, $request->price, $request->currency);
            $paymentUrl = $this->getPaymentPage($newSale);
            $newSale->save();

            return view("sales.paymentPage", compact("paymentUrl"));
        } catch (Exception $exception) {
            $errorMsg = $exception->getMessage();

            return view("sales.errorPage", compact("errorMsg"));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return string
     */
    public function show(int $id): string
    {
        return response(Sale::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return void
     * @throws ValidationException
     */
    public function update(Request $request, int $id)
    {
        $this->validateSale($request);
        $sale = Sale::findOrFail($id);
        $sale->edit($request->productName, $request->price, $request->currency);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroy(int $id)
    {
        Sale::findOrFail($id)->delete();
    }

    /**
     * @throws ValidationException
     */
    private function validateSale(Request $request) {
        $this->validate($request, [
            "productName" => "required",
            "price"       => "required|numeric|between:100,9999.99",
            "currency"    => "required|size:3",
        ]);
    }

    /**
     * @throws Exception
     */
    private function getPaymentPage(Sale $sale)
    {
        $response = Http::post("https://preprod.paymeservice.com/api/generate-sale", [
            "seller_payme_id" => $this::$sellerPayMeId,
            "sale_price"      => $sale->getPrice(),
            "currency"        => $sale->getCurrency(),
            "product_name"    => $sale->getProductName(),
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
