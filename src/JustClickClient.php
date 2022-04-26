<?php

namespace JustClick;

use JustClick\Operation\CallbackOperation;
use JustClick\Operation\OrderOperation;
use JustClick\Operation\ProductOperation;

class JustClickClient
{
    /**
     * @var Api $api
     */
    private $api;

    /**
     * @var CallbackOperation $callbackOperation
     */
    private $callbackOperation;

    /**
     * @var ProductOperation $productOperation
     */
    private $productOperation;

    /**
     * @var OrderOperation $orderOperation
     */
    private $orderOperation;

    /**
     * @param string $login     Логин
     * @param string $secretKey Секретный ключ
     */
    public function __construct(string $login, string $secretKey)
    {
        $this->api               = new Api($login, $secretKey);
        $this->productOperation  = new ProductOperation($this->api);
        $this->orderOperation    = new OrderOperation($this->api);
        $this->callbackOperation = new CallbackOperation($this->api);
    }

    /**
     * @return CallbackOperation
     */
    public function callback(): CallbackOperation
    {
        return $this->callbackOperation;
    }

    /**
     * @return ProductOperation
     */
    public function product(): ProductOperation
    {
        return $this->productOperation;
    }

    /**
     * @return OrderOperation
     */
    public function order(): OrderOperation
    {
        return $this->orderOperation;
    }
}
