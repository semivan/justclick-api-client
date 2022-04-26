<?php

namespace JustClick\Operation;

use JustClick\Object\Order;
use JustClick\Object\OrderItem;
use JustClick\Object\Partner;
use JustClick\Object\Product;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class CallbackOperation
{
    /**
     * @var ParameterBag
     */
    private $request;
    
    public function __construct()
    {
        $this->request = Request::createFromGlobals()->request;
    }

    /**
     * Заполнение заказа
     *
     * @return Order
     */
    private function buildOrder(): Order
    {
        $request = $this->request;

        foreach (($request->get('items', [])) as $item) {
            $partners = [];

            foreach (($item['partners'] ?? []) as $partner) {
                $partners[] = new Partner([
                    'id'    => isset($partner['partner_id']) ? intval($partner['partner_id']) : null,
                    'name'  => $partner['partner_name'] ?? null,
                    'level' => isset($partner['partner_lvl']) ? intval($partner['partner_lvl']) : null,
                    'fee'   => isset($partner['partner_fee']) ? floatval($partner['partner_fee']) : null,
                ]);
            }

            $items[] = new OrderItem([
                'sum'     => isset($item['sum']) ? floatval($item['sum']) : null,
                'product' => new Product([
                    'code'  => $item['id']           ?? null,
                    'title' => $item['title']        ?? null,
                    'price' => isset($item['price']) ? floatval($item['price']) : null,
                ]),
                'partners' => $partners,
            ]);
        }

        return new Order([
            'firstName'         => $request->get('first_name'),
            'lastName'          => $request->get('last_name'),
            'middleName'        => $request->get('middle_name'),
            'email'             => $request->get('email'),
            'phone'             => $request->get('phone'),
            'city'              => $request->get('city'),
            'country'           => $request->get('country'),
            'address'           => $request->get('address'),
            'region'            => $request->get('region'),
            'postalcode'        => $request->get('postalcode'),
            'payStatus'         => $request->get('pay_status'),
            'payway'            => $request->get('payway'),
            'comment'           => $request->get('comment'),
            'domain'            => $request->get('domain'),
            'link'              => $request->get('link'),
            'tag'               => $request->get('tag'),
            'kupon'             => $request->get('kupon'),
            'utmSource'         => $request->get('utm')['source']   ?? null,
            'utmMedium'         => $request->get('utm')['medium']   ?? null,
            'utmCampaign'       => $request->get('utm')['campaign'] ?? null,
            'utmContent'        => $request->get('utm')['content']  ?? null,
            'utmTerm'           => $request->get('utm')['term']     ?? null,
            'id'                => !is_null($request->get('id'))                 ? intval($request->get('id')) : null,
            'createdAt'         => !is_null($request->get('created'))            ? intval($request->get('created')) : null,
            'paidAt'            => !is_null($request->get('paid'))               ? intval($request->get('paid')) : null,
            'price'             => !is_null($request->get('price'))              ? floatval($request->get('price')) : null,
            'lastPaymentSum'    => !is_null($request->get('last_payment_sum'))   ? floatval($request->get('last_payment_sum')) : null,
            'prepaymentSum'     => !is_null($request->get('prepayment_sum'))     ? floatval($request->get('prepayment_sum')) : null,
            'isRecurrent'       => !is_null($request->get('is_recurrent'))       ? boolval($request->get('is_recurrent')) : null,
            'sumToPay'          => !is_null($request->get('bill_sum_topay'))     ? floatval($request->get('bill_sum_topay')) : null,
            'prepaymentEnabled' => !is_null($request->get('prepayment_enabled')) ? boolval($request->get('prepayment_enabled')) : null,
            'prepaymentMinSum'  => !is_null($request->get('prepayment_minsum'))  ? floatval($request->get('prepayment_minsum')) : null,
            'items'             => $items,
        ]);
    }

    /**
     * @return Order|null Создание заказа
     */
    public function newOrder(): ?Order
    {
        if ($this->request->get('status') !== 'new_order' OR !$this->request->get('id')) {
            return null;
        }

        return $this->buildOrder();
    }

    /**
     * @return Order|null Отмена заказа
     */
    public function cancelOrder(): ?Order
    {
        if ($this->request->get('status') !== 'cancel_order' OR !$this->request->get('id')) {
            return null;
        }

        return $this->buildOrder();
    }

    /**
     * @return Order|null Оплата заказа
     */
    public function payment(): ?Order
    {
        if (!$this->request->get('last_payment_sum') OR !$this->request->get('id')) {
            return null;
        }

        return $this->buildOrder();
    }

    /**
     * @return Order|null Предоплата заказа
     */
    public function prepayment(): ?Order
    {
        if (!$this->request->get('prepayment_sum') OR !$this->request->get('id')) {
            return null;
        }

        return $this->buildOrder();
    }

    /**
     * @return Order|null Возврат средств
     */
    public function moneyback(): ?Order
    {
        if ($this->request->get('status') !== 'moneyback' OR !$this->request->get('id')) {
            return null;
        }

        return $this->buildOrder();
    }
}