<?php

namespace JustClick\Object;

class Product
{
    /**
     * @var int $id ID продукта
     */
    private $id;

    /**
     * @var string $code Код продукта
     */
    private $code;

    /**
     * @var string $title Заголовок продукта
     */
    private $title;

    /**
     * @var int $type Тип продукта
     * 1 - цифровой
     * 2 - физический
     * 3 - с плавающей ценой
     */
    private $type;

    /**
     * @var float $price Цена продукта
     */
    private $price;

    public function __construct(array $properties = [])
    {
        foreach ($properties as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    /**
     * @return int ID продукта
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string Код продукта
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @return string Заголовок продукта
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return int Тип продукта
     * 1 - цифровой
     * 2 - физический
     * 3 - с плавающей ценой
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @return float Цена продукта
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }
}