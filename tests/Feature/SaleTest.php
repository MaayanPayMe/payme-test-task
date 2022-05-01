<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SaleTest extends TestCase
{
    use DatabaseTransactions;

    public function testGet()
    {
        $this->get("/")->assertOK()->assertViewIs("sales.index");
    }

    public function testGetIndex()
    {
        $this->get("/sales")->assertOK()->assertViewIs("sales.index");
    }

    public function testGetCreate()
    {
        $this->get("/sales/create")->assertOK()->assertViewIs("sales.newSalePage");
    }

    public function testSuccessPostStore()
    {
        $this->post("/sales", [
            "productName" => "test",
            "price"       => "555",
            "currency"    => "ILS",
        ])->assertOK()->assertViewIs("sales.paymentPage");
    }

    public function testFailedValidationPostStore()
    {
        $this->post("/sales", [
            "productName" => "test",
            "price"       => "99",
            "currency"    => "ILS",
        ])->assertStatus(302);
    }
}
