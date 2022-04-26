<?php

namespace JustClick;

use GuzzleHttp\Client as HttpClient;
use function GuzzleHttp\json_decode;

class Api
{
    const ERROR_CODES = [
        // Общее
        1   => 'Не передана хешь-подпись запроса',
        2   => 'Не переданы параметры запроса',
        3   => 'Ошибочные параметры запроса',
        4   => 'Хешь-подпись к запросу неверна',
        5   => 'Не передан или не найден логин в системе JustClick',
        6   => 'Для указанного ip доступ запрещён',
        7   => 'Аккаунт отключен',
        8   => 'Лимит запросов по API с данного адреса исчерпан. Попробуйте позже. Как правило связано с тем, что функции API отключены для аккаунта.',
        // Добавление контакта
        100 => 'В переданных параметрах отсутствует e-mail контакта',
        101 => 'Ошибка добавления пользователя в группу',
        102 => 'Контакт уже есть во всех переданных группах',
        103 => 'В запросе передана несуществующая группа',
        104 => 'Добавление в эту группу невозможно.',
        // Работа с заказами
        200 => 'Заказ с указанным номером не существует',
        201 => 'Передан неверный статус заказа',
        202 => 'Во время оплаты заказа произошла ошибка',
        203 => 'Не передан номер заказа',
        // Удаление и изменение статуса заказа
        302 => 'В запросе передан не существующий номер заказа',
        303 => 'Такого статуса заказа нет в системе',
        // Получение списка купленных продуктов по email клиента
        400 => 'Заказ с таким номером не существует',
        // Получение списка групп контактов по email клиента
        500 => 'Контакт с таким и-мейлом не существует',
        501 => 'Контакт не состоит ни в одной группе',
        // Создание заказа
        600 => 'Передан не правильный и-мейл клиента',
        601 => 'Такой заказ уже существует. (в result->bill_id будет передан его номер)',
        602 => 'Не удалось создать заказ',
        603 => 'В заказе отсутсвуют товары',
        604 => 'В вашем магазине нет продукта с таким id (будет возвращён id этого продукта)',
        605 => 'Не хватает данных для доставки продукта (отсутсвует адресс или имя)',
        // Получение всех продуктов
        700 => 'В магазине отсутствуют продукты',
        // Добавление контакта
        800 => 'Указанная группа контактов не найдена (не существует)',
        801 => 'Контакт с указанным email не найден (не существует)',
        // Получение информации о заказе
        400 => 'Заказ с таким номером не существует',
    ];

    /**
     * @var string $login Логин
     */
    private $login;

    /**
     * @var string $secretKey Секретный ключ
     */
    private $secretKey;

    /**
     * @var HttpClient $httpClient
     */
    private $httpClient;

    /**
     * @param string $login     Логин
     * @param string $secretKey Секретный ключ
     */
    public function __construct(string $login, string $secretKey)
    {
        $this->login      = $login;
        $this->secretKey  = $secretKey;
        $this->httpClient = new HttpClient([
            'base_uri'        => "https://$login.justclick.ru/api/",
            'allow_redirects' => false
        ]);
    }
    
    /**
     * Отправить запрос
     *
     * @param string $method Вызываемый метод
     * @param array  $params Параметры запроса
     * @return Response
     */
    public function request(string $method, array $params = []): Response
    {
        // Создание подписи к передаваемым данным
        $params['hash'] = md5(http_build_query($params) .'::'. $this->login .'::'. $this->secretKey);

        $data = [
            'body' => http_build_query($params),
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ];

        try {
            $response   = $this->httpClient->post($method, $data);
            $statusCode = $response->getStatusCode();
            $message    = $response->getReasonPhrase();
        } catch (\Exception $e) {
            $statusCode = $e->getCode();
            $message    = $e->getMessage();
        }

        if (!in_array($statusCode, [200, 302])) {
            return new Response(false, [], "$message ($statusCode)", $statusCode);
        }

        $content = json_decode($response->getBody()->getContents(), true);
        
        // Проверка полученной подписи к ответу
        $hash = md5($content['error_code'] .'::'. $content['error_text'] .'::'. $this->secretKey);
        if ($hash != $content['hash']) {
            return new Response(false, [], 'Подпись к ответу не верна');
        }
        
        if ($content['error_code'] != 0) {
            return new Response(false, $content, "{$content['error_text']} ({$content['error_code']})", intval($content['error_code']));
        }
        
        return new Response(true, $content['result'] ?? []);
    }
}
