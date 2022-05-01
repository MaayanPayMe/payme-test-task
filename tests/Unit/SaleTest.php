<?php

namespace Tests\Unit;

use App\Http\Controllers\SaleController;
use App\Models\Sale;
use http\Exception;
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
    public function testSuccessEditSale()
    {
        $method = $this->getPrivateMethods(Sale::class, "edit");
        $method->invokeArgs(app()->make(Sale::class), ["test", "555", "ILS"]);
        $this->assertTrue(true);
    }

    /**
     * Test getPaymentPage function
     */

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function testSuccessGetPaymentPage()
    {
        $method = $this->getPrivateMethods(SaleController::class, "getPaymentPage");
        $result = $method->invokeArgs(app()->make(SaleController::class), [new Sale([], "test", "555", "ILS")]);
        $this->assertNotEmpty($result);
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function testFailedPriceGetPaymentPage()
    {
        $this->expectExceptionMessage("Invalid price, out of min-max bounds");
        $method = $this->getPrivateMethods(SaleController::class, "getPaymentPage");
        $method->invokeArgs(app()->make(SaleController::class), [new Sale([], 'test', '99', 'ILS')]);
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function testNoProductNameGetPaymentPage()
    {
        $this->expectExceptionMessage("Invalid description");
        $method = $this->getPrivateMethods(SaleController::class, "getPaymentPage");
        $method->invokeArgs(app()->make(SaleController::class), [new Sale([], "", "555", "ILS")]);
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function testNoCurrencyGetPaymentPage()
    {
        $this->expectExceptionMessage("Required parameter is missing");
        $method = $this->getPrivateMethods(SaleController::class, "getPaymentPage");
        $method->invokeArgs(app()->make(SaleController::class), [new Sale([], "test", "555", "")]);
    }

    /**
     * Test validateSale function
     */

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function testSaleSuccessValidateSale()
    {
        $method = $this->getPrivateMethods(SaleController::class, "validateSale");

        $saleReq = Request::create("", "POST", [
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
    public function testSaleSmallPriceValidateSale()
    {
        $this->expectException(ValidationException::class);
        $method = $this->getPrivateMethods(SaleController::class, "validateSale");

        $saleReq = Request::create("", "POST", [
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
    public function testSaleLargePriceValidateSale()
    {
        $this->expectException(ValidationException::class);
        $method = $this->getPrivateMethods(SaleController::class, "validateSale");

        $saleReq = Request::create("", "POST", [
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
    public function testSaleFailedPriceTypeValidateSale()
    {
        $this->expectException(ValidationException::class);
        $method = $this->getPrivateMethods(SaleController::class, "validateSale");

        $saleReq = Request::create("", "POST", [
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
    public function testSaleWithoutCurrencyValidateSale()
    {
        $this->expectException(ValidationException::class);
        $method = $this->getPrivateMethods(SaleController::class, "validateSale");

        $saleReq = Request::create("", "POST", [
            "price"       => "555",
            "productName" => "test_validate"
        ]);

        $method->invokeArgs(app()->make(SaleController::class), [$saleReq]);
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function testSaleWithoutProductNameValidateSale()
    {
        $this->expectException(ValidationException::class);
        $method = $this->getPrivateMethods(SaleController::class, "validateSale");

        $saleReq = Request::create("", "POST", [
            "price"    => "555",
            "currency" => "ILS"
        ]);

        $method->invokeArgs(app()->make(SaleController::class), [$saleReq]);
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function testSaleWithoutPriceValidateSale()
    {
        $this->expectException(ValidationException::class);
        $method = $this->getPrivateMethods(SaleController::class, "validateSale");

        $saleReq = Request::create("", "POST", [
            "currency"    => "ILS",
            "productName" => "test_validate"
        ]);

        $method->invokeArgs(app()->make(SaleController::class), [$saleReq]);
    }
}
