<?php

namespace JustClick\Operation;

use JustClick\JustClickException;
use JustClick\Object\Product;

class ProductOperation extends AbstractOperation
{
    /**
     * Получить список всех продуктов
     *
     * @return Product[]
     */
    public function getList(): array
    {
        $response = $this->request('GetAllGoods');

        if (!$response->isSuccess()) {
            throw new JustClickException($response->getError(), $response->getErrorCode() ?? 0);
        }

        foreach ($response->getData() as $product) {
            $products[] = new Product([
                'code'  => $product['good_name']  ?? null,
                'title' => $product['good_title'] ?? null,
                'id'    => isset($product['good_id'])   ? intval($product['good_id'])    : null,
                'type'  => isset($product['good_type']) ? intval($product['good_type'])  : null,
                'price' => isset($product['good_sum'])  ? floatval($product['good_sum']) : null,
            ]);
        }

        return $products ?? [];
    }
}