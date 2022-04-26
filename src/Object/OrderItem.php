<?php

namespace JustClick\Object;

class OrderItem
{
    /**
     * @var Product $product Продукт
     */
    private $product;

    /**
     * @var float $sum Стоимость по факту
     */
    private $sum;

    /**
     * @var string $pinCode Отправленный пин-код
     */
    private $pinCode;

    /**
     * @var Partner[] $partners Партнеры
     */
    private $partners = [];

    public function __construct(array $properties = [])
    {
        foreach ($properties as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    /**
     * @return Product Продукт
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @return float Стоимость по факту
     */
    public function getSum(): ?float
    {
        return $this->sum;
    }

    /**
     * @return string Отправленный пин-код
     */
    public function getPinCode(): ?string
    {
        return $this->pinCode;
    }

    /**
     * @return Partner[] Партнеры
     */
    public function getPartners(): array
    {
        return $this->partners;
    }
}