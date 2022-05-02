<?php

namespace Tests\Unit;

use App\Http\Controllers\SaleController;
use App\Models\Sale;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Http\Request;
use ReflectionException;
use ReflectionMethod;
use Tests\CreatesApplication;
use Illuminate\Validation\ValidationException;

class SaleTest extends TestCase
{
    use CreatesApplication, AccessiblePrivateMethods, DatabaseTransactions;

    private $saleClass;
    private ReflectionMethod $getPaymentPageMethod;
    private ReflectionMethod $validateSaleMethod;

    /**
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->saleClass = app()->make(SaleController::class);
        $this->getPaymentPageMethod = $this->getPrivateMethods(SaleController::class, "getPaymentPage");
        $this->validateSaleMethod = $this->getPrivateMethods(SaleController::class, "validateSale");

    }

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
     */
    public function testSuccessGetPaymentPage()
    {
        $result = $this->getPaymentPageMethod->invokeArgs($this->saleClass, [new Sale([], "test", "555", "ILS")]);
        $this->assertNotEmpty($result);
    }

    /**
     * @throws ReflectionException
     */
    public function testFailedPriceGetPaymentPage()
    {
        $this->expectExceptionMessage("Invalid price, out of min-max bounds");
        $this->getPaymentPageMethod->invokeArgs($this->saleClass, [new Sale([], 'test', '99', 'ILS')]);
    }

    /**
     * @throws ReflectionException
     */
    public function testNoProductNameGetPaymentPage()
    {
        $this->expectExceptionMessage("Invalid description");
        $this->getPaymentPageMethod->invokeArgs($this->saleClass, [new Sale([], "", "555", "ILS")]);
    }

    /**
     * @throws ReflectionException
     */
    public function testNoCurrencyGetPaymentPage()
    {
        $this->expectExceptionMessage("Required parameter is missing");
        $this->getPaymentPageMethod->invokeArgs($this->saleClass, [new Sale([], "test", "555", "")]);
    }

    /**
     * Test validateSale function
     */

    /**
     * @throws ReflectionException
     */
    public function testSaleSuccessValidateSale()
    {
        $method = $this->getPrivateMethods(SaleController::class, "validateSale");

        $saleReq = Request::create("", "POST", [
            "price"       => "555",
            "currency"    => "ILS",
            "productName" => "test_validate"
        ]);

        $method->invokeArgs($this->saleClass, [$saleReq]);
        $this->assertTrue(true);
    }

    /**
     * @throws ReflectionException
     */
    public function testSaleSmallPriceValidateSale()
    {
        $this->expectException(ValidationException::class);

        $saleReq = Request::create("", "POST", [
            "price"       => "99",
            "currency"    => "ILS",
            "productName" => "test_validate"
        ]);

        $this->validateSaleMethod->invokeArgs($this->saleClass, [$saleReq]);
    }

    /**
     * @throws ReflectionException
     */
    public function testSaleLargePriceValidateSale()
    {
        $this->expectException(ValidationException::class);

        $saleReq = Request::create("", "POST", [
            "price"       => "1000000",
            "currency"    => "ILS",
            "productName" => "test_validate"
        ]);

        $this->validateSaleMethod->invokeArgs($this->saleClass, [$saleReq]);
    }

    /**
     * @throws ReflectionException
     */
    public function testSaleFailedPriceTypeValidateSale()
    {
        $this->expectException(ValidationException::class);

        $saleReq = Request::create("", "POST", [
            "price"       => "abc",
            "currency"    => "ILS",
            "productName" => "test_validate"
        ]);

        $this->validateSaleMethod->invokeArgs($this->saleClass, [$saleReq]);
    }

    /**
     * @throws ReflectionException
     */
    public function testSaleWithoutCurrencyValidateSale()
    {
        $this->expectException(ValidationException::class);

        $saleReq = Request::create("", "POST", [
            "price"       => "555",
            "productName" => "test_validate"
        ]);

        $this->validateSaleMethod->invokeArgs($this->saleClass, [$saleReq]);
    }

    /**
     * @throws ReflectionException
     */
    public function testSaleWithoutProductNameValidateSale()
    {
        $this->expectException(ValidationException::class);

        $saleReq = Request::create("", "POST", [
            "price"    => "555",
            "currency" => "ILS"
        ]);

        $this->validateSaleMethod->invokeArgs($this->saleClass, [$saleReq]);
    }

    /**
     * @throws ReflectionException
     */
    public function testSaleWithoutPriceValidateSale()
    {
        $this->expectException(ValidationException::class);

        $saleReq = Request::create("", "POST", [
            "currency"    => "ILS",
            "productName" => "test_validate"
        ]);

        $this->validateSaleMethod->invokeArgs($this->saleClass, [$saleReq]);
    }
}
