<?php

namespace JustClick\Object;

class Partner
{
    /**
     * @var int $id ID партнера
     */
    private $id;

    /**
     * @var string $pinCode Логин партнера
     */
    private $name;

    /**
     * @var int $level Уровень партнера
     */
    private $level;

    /**
     * @var float $fee Сумма партнерских начислений
     */
    private $fee;

    public function __construct(array $properties = [])
    {
        foreach ($properties as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    /**
     * @return int ID партнера
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string Логин партнера
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return int Уровень партнера
     */
    public function getLevel(): ?int
    {
        return $this->level;
    }

    /**
     * @return float Сумма партнерских начислений
     */
    public function getFee(): ?float
    {
        return $this->fee;
    }
}