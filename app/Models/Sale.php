<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $product_name
 * @property int $price
 * @property string $currency
 */
class Sale extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string, double, string>
     */
    protected $fillable = [
        'product_name',
        'price',
        'currency',
    ];

    /**
     * @return string
     */
    public function getProductName(): string
    {
        return $this->product_name;
    }

    /**
     * @param string $product_name
     */
    public function setProductName(string $product_name): void
    {
        $this->product_name = $product_name;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @param array $attributes
     * @param string|null $product_name
     * @param int|null $price
     * @param string|null $currency
     */
    public function __construct(array $attributes = [], string $product_name = null, int $price = null, string $currency = null)
    {
        parent::__construct($attributes);
        $this->product_name = $product_name;
        $this->price = $price;
        $this->currency = $currency;
    }

    public function edit(string $productName, int $price, string $currency)
    {
        $this->setProductName($productName);
        $this->setPrice($price);
        $this->setCurrency($currency);
        $this->save();
    }
}
