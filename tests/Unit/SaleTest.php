<?php

namespace Tests\Unit;

use App\Http\Controllers\SaleController;
use App\Models\Sale;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Http\Request;
use ReflectionException;
use Tests\CreatesApplication;
use Illuminate\Validation\ValidationException;
use  Illuminate\Database\QueryException;

class SaleTest extends TestCase
{
    use CreatesApplication, AccessiblePrivateMethods, DatabaseTransactions;

    /**
     * Test createNewSale function
     */

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function test_good_createNewSale()
    {
        $method = $this->getPrivateMethods(SaleController::class, "createNewSale");
        $method->invokeArgs(app()->make(SaleController::class), ['test', '555', 'ILS']);
        $this->assertTrue(true);
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function test_wrong_price_createNewSale()
    {
        $this->expectException(QueryException::class);
        $method = $this->getPrivateMethods(SaleController::class, "createNewSale");
        $method->invokeArgs(app()->make(SaleController::class), ['test', 'aaaa', 'ILS']);
        $this->assertTrue(true);
    }

    /**
     * Test getPaymentPage function
     */

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function test_good_getPaymentPage()
    {
        $method = $this->getPrivateMethods(SaleController::class, "getPaymentPage");
        $result = $method->invokeArgs(app()->make(SaleController::class), ['test', '555', 'ILS']);
        $this->assertNotEmpty($result);
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function test_wrong_price_getPaymentPage()
    {
        $this->expectExceptionCode(0);
        $method = $this->getPrivateMethods(SaleController::class, "getPaymentPage");
        $method->invokeArgs(app()->make(SaleController::class), ['test', '99', 'ILS']);
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function test_no_product_name_getPaymentPage()
    {
        $this->expectExceptionCode(0);
        $method = $this->getPrivateMethods(SaleController::class, "getPaymentPage");
        $method->invokeArgs(app()->make(SaleController::class), ['', '99', 'ILS']);
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function test_no_currency_getPaymentPage()
    {
        $this->expectExceptionCode(0);
        $method = $this->getPrivateMethods(SaleController::class, "getPaymentPage");
        $method->invokeArgs(app()->make(SaleController::class), ['test', '99', '']);
    }

    /**
     * Test validateSale function
     */

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function test_sale_good_validateSale()
    {
        $method = $this->getPrivateMethods(SaleController::class, "validateSale");

        $saleReq = Request::create('', 'POST', [
            "price"       => "555",
            "currency"    => "ILS",
            "productName" => "test_validate"
        ]);

        $method->invokeArgs(app()->make(SaleController::class), [$saleReq]);
        $this->assertTrue(true);
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function test_sale_small_price_validateSale()
    {
        $this->expectException(ValidationException::class);
        $this->withoutExceptionHandling((array)ValidationException::class);
        $method = $this->getPrivateMethods(SaleController::class, "validateSale");

        $saleReq = Request::create('', 'POST', [
            "price"       => "99",
            "currency"    => "ILS",
            "productName" => "test_validate"
        ]);

        $method->invokeArgs(app()->make(SaleController::class), [$saleReq]);
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function test_sale_large_price_validateSale()
    {
        $this->expectException(ValidationException::class);
        $this->withoutExceptionHandling((array)ValidationException::class);
        $method = $this->getPrivateMethods(SaleController::class, "validateSale");

        $saleReq = Request::create('', 'POST', [
            "price"       => "1000000",
            "currency"    => "ILS",
            "productName" => "test_validate"
        ]);

        $method->invokeArgs(app()->make(SaleController::class), [$saleReq]);
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function test_sale_not_a_number_price_validateSale()
    {
        $this->expectException(ValidationException::class);
        $this->withoutExceptionHandling((array)ValidationException::class);
        $method = $this->getPrivateMethods(SaleController::class, "validateSale");

        $saleReq = Request::create('', 'POST', [
            "price"       => "abc",
            "currency"    => "ILS",
            "productName" => "test_validate"
        ]);

        $method->invokeArgs(app()->make(SaleController::class), [$saleReq]);
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function test_sale_without_currency_validateSale()
    {
        $this->expectException(ValidationException::class);
        $this->withoutExceptionHandling((array)ValidationException::class);
        $method = $this->getPrivateMethods(SaleController::class, "validateSale");

        $saleReq = Request::create('', 'POST', [
            "price"       => "555",
            "productName" => "test_validate"
        ]);

        $method->invokeArgs(app()->make(SaleController::class), [$saleReq]);
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function test_sale_without_product_name_validateSale()
    {
        $this->expectException(ValidationException::class);
        $this->withoutExceptionHandling((array)ValidationException::class);
        $method = $this->getPrivateMethods(SaleController::class, "validateSale");

        $saleReq = Request::create('', 'POST', [
            "price"    => "555",
            "currency" => "ILS"
        ]);

        $method->invokeArgs(app()->make(SaleController::class), [$saleReq]);
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function test_sale_without_price_validateSale()
    {
        $this->expectException(ValidationException::class);
        $this->withoutExceptionHandling((array)ValidationException::class);
        $method = $this->getPrivateMethods(SaleController::class, "validateSale");

        $saleReq = Request::create('', 'POST', [
            "currency"    => "ILS",
            "productName" => "test_validate"
        ]);

        $method->invokeArgs(app()->make(SaleController::class), [$saleReq]);
    }
}
