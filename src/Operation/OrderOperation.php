<?php

namespace JustClick\Operation;

use JustClick\JustClickException;
use JustClick\Object\Order;
use JustClick\Object\OrderBuilder;
use JustClick\Object\OrderItem;
use JustClick\Object\Partner;
use JustClick\Object\Product;

class OrderOperation extends AbstractOperation
{
    /**
     * Заполнение заказа
     *
     * @param array $data Массив из ответа API
     * @return Order
     */
    private function buildOrder(array $data = []): Order
    {
        foreach (($data['items'] ?? []) as $item) {
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
            'firstName'         => $data['first_name']      ?? null,
            'lastName'          => $data['last_name']       ?? null,
            'middleName'        => $data['middle_name']     ?? null,
            'email'             => $data['email']           ?? null,
            'phone'             => $data['phone']           ?? null,
            'city'              => $data['city']            ?? null,
            'country'           => $data['country']         ?? null,
            'address'           => $data['address']         ?? null,
            'region'            => $data['region']          ?? null,
            'postalcode'        => $data['postalcode']      ?? null,
            'payStatus'         => $data['pay_status']      ?? null,
            'payway'            => $data['payway']          ?? null,
            'comment'           => $data['comment']         ?? null,
            'domain'            => $data['domain']          ?? null,
            'link'              => $data['link']            ?? null,
            'tag'               => $data['tag']             ?? null,
            'kupon'             => $data['kupon']           ?? null,
            'utmSource'         => $data['utm']['source']   ?? null,
            'utmMedium'         => $data['utm']['medium']   ?? null,
            'utmCampaign'       => $data['utm']['campaign'] ?? null,
            'utmContent'        => $data['utm']['content']  ?? null,
            'utmTerm'           => $data['utm']['term']     ?? null,
            'id'                => isset($data['id'])                 ? intval($data['id']) : null,
            'createdAt'         => isset($data['created'])            ? intval($data['created']) : null,
            'paidAt'            => isset($data['paid'])               ? intval($data['paid']) : null,
            'price'             => isset($data['price'])              ? floatval($data['price']) : null,
            'lastPaymentSum'    => isset($data['last_payment_sum'])   ? floatval($data['last_payment_sum']) : null,
            'prepaymentSum'     => isset($data['prepayment_sum'])     ? floatval($data['prepayment_sum']) : null,
            'isRecurrent'       => isset($data['is_recurrent'])       ? boolval($data['is_recurrent']) : null,
            'sumToPay'          => isset($data['bill_sum_topay'])     ? floatval($data['bill_sum_topay']) : null,
            'prepaymentEnabled' => isset($data['prepayment_enabled']) ? boolval($data['prepayment_enabled']) : null,
            'prepaymentMinSum'  => isset($data['prepayment_minsum'])  ? floatval($data['prepayment_minsum']) : null,
            'items'             => $items ?? [],
        ]);
    }

    /**
     * Получить заказ по номеру
     *
     * @param integer $orderId Номер заказа
     * @return Order|null
     */
    public function get(int $orderId): ?Order
    {
        $response = $this->request('getOrderDetails', [
            'bill_id'   => $orderId,
            'good_info' => true,
        ]);

        if (!$response->isSuccess() AND !in_array($response->getErrorCode(), [400])) {
            throw new JustClickException($response->getError(), $response->getErrorCode() ?? 0);
        }

        return $response->getData() ? $this->buildOrder($response->getData()) : null;
    }
    
    /**
     * Найти заказы за указанный период
     * 
     * @param string      $dateFrom Дата с
     * @param string|null $dateTo   Дата по (null - заказы только за день $dateFrom)
     * @param bool        $paid     Выбрать только оплаченные заказы
     * @param array       $goods    ID продуктов
     * @return Order[]
     */
    public function findByPeriod(string $dateFrom, string $dateTo = null, bool $paid = false, array $goods = []): array
    {
        try {
            $dateTimeFrom = new \DateTime($dateFrom);
            $dateTimeTo   = new \DateTime($dateTo ?? $dateFrom);
        } catch (\Exception $e) {
            throw new JustClickException('Неверно указана дата');
        }

        if ($dateTimeFrom > $dateTimeTo) {
            throw new JustClickException('Начальная дата больше конечной');
        }

        $diffDays = $dateTimeFrom->diff($dateTimeTo)->days;

        if ($diffDays > 30) {
            $dateTimeTo = new \DateTime($dateFrom .' +30 day');
        }

        $response = $this->request('getOrdersWithGoods', [
            'begin_date' => $dateTimeFrom->format('d.m.Y'),
            'end_date'   => $dateTimeTo->format('d.m.Y'),
            'paid'       => $paid,
            'goods'      => $goods,
        ]);

        if (!$response->isSuccess()) {
            throw new JustClickException($response->getError(), $response->getErrorCode() ?? 0);
        }

        foreach ($response->getData() as $order) {
            $orders[] = $this->buildOrder($order);            
        }

        if ($diffDays > 30) {
            $dateFrom = (new \DateTime($dateFrom .' +31 day'))->format('d.m.Y');
            $orders   = array_merge($orders, $this->findByPeriod($dateFrom, $dateTo, $paid, $goods));
        }

        return $orders ?? [];
    }

    /**
     * Найти заказы по email
     *
     * @param string $email     Email rkbtynf
     * @param string $payStatus Статус заказа (paid — оплачен, waiting — ожидается, cancel — отменен, null - все)
     * @return Order[]
     */
    public function findByEmail(string $email, string $payStatus = null): array
    {
        $params['email'] = $email;

        if ($payStatus) {
            $params['pay_status'] = $payStatus;
        }

        $response = $this->request('GetBills', $params);

        if (!$response->isSuccess()) {
            throw new JustClickException($response->getError(), $response->getErrorCode() ?? 0);
        }

        foreach ($response->getData() as $orderId => $data) {
            $orders[] = $this->get($orderId);            
        }

        return $orders ?? [];
    }

    /**
     * @return OrderBuilder
     */
    public function orderBuilder(): OrderBuilder
    {
        return new OrderBuilder();
    }

    /**
     * Создать заказ
     *
     * @param OrderBuilder $orderBuilder
     * @return Order|null
     */
    public function create(OrderBuilder $orderBuilder): ?Order
    {
        $response = $this->request('CreateOrder', $orderBuilder->getParams());

        if (!$response->isSuccess() AND !in_array($response->getErrorCode(), [601])) {
            throw new JustClickException($response->getError(), $response->getErrorCode() ?? 0);
        }

        $data    = $response->getData();
        $orderId = intval($data['bill_id'] ?? $data['result']['bill_id']);

        return $this->get($orderId);
    }

    /**
     * Обновить статус заказа
     *
     * @param integer $orderId Номер заказа
     * @param string  $status  Статус заказа (sent — заказ отправлен по почте, paid — поступила оплата по заказу, return — покупатель вернул заказ, cancel — заказ отменен)
     * @param integer $date    Время отправки заказа по почте или оплаты в секундах (Unix)
     * @param string  $rpo     Номер почтового отправления
     * @return void
     */
    public function updateStatus(int $orderId, string $status, int $date = null, string $rpo = null)
    {
        if (!in_array($status, ['sent', 'paid', 'return', 'cancel'])) {
            throw new JustClickException('Указан неверный статус заказа');
        }

        if ($status === 'sent' AND !$rpo) {
            throw new JustClickException('Не указан номер почтового отправления');
        }

        $params = [
            'bill_id' => $orderId,
            'status'  => $status,
            'date'    => $date ?? time(),
        ];

        if ($status === 'sent') {
            $params['rpo'] = $rpo;
        }

        $response = $this->request('UpdateOrderStatus', $params);

        if (!$response->isSuccess()) {
            throw new JustClickException($response->getError(), $response->getErrorCode() ?? 0);
        }
    }

    /**
     * Удалить заказ
     *
     * @param integer $orderId Номер заказа
     * @return void
     */
    public function delete(int $orderId)
    {
        $response = $this->request('DeleteOrder', [
            'bill_id' => $orderId,
        ]);

        if (!$response->isSuccess()) {
            throw new JustClickException($response->getError(), $response->getErrorCode() ?? 0);
        }
    }
}