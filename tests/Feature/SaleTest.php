<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SaleTest extends TestCase
{
    use DatabaseTransactions;

    public function test_get()
    {
        $this->get('/')->assertStatus(200)->assertViewIs("sales.index");
    }

    public function test_get_index()
    {
        $this->get('/sales')->assertStatus(200)->assertViewIs("sales.index");
    }

    public function test_get_create()
    {
        $this->get('/sales/create')->assertStatus(200)->assertViewIs("sales.newSalePage");
    }

    public function test_good_post_store()
    {
        $this->post('/sales', [
            "productName" => "test",
            "price"       => "555",
            "currency"    => "ILS",
        ])->assertStatus(200)->assertViewIs("sales.paymentPage");
    }

    public function test_wrong_validation_post_store()
    {
        $this->post('/sales', [
            "productName" => "test",
            "price"       => "99",
            "currency"    => "ILS",
        ])->assertStatus(302);
    }
}
