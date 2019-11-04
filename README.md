# PHP API клиент для JustClick

## Требования
* PHP >= 7.1
* [guzzlehttp/guzzle](https://github.com/guzzle/guzzle/)
* [symfony/http-foundation](https://github.com/symfony/http-foundation/)


## Установка
```sh
composer require semivan/justclick-api-client
```


## Использование
```php
$client = new \JustClick\JustClickClient($login, $secretKey);
```


### Получить список продуктов
```php
$products = $client->product()->getList();
```


### Получить заказ по номеру
```php
$order = $client->order()->get(1234567890);
```


### Найти заказы за указанный период
```php
$orders = $client->order()->findByPeriod('2019-11-01 00:00:00', date('Y-m-d H:i:s'));
```


### Найти заказы по email
```php
$orders = $client->order()->findByEmail('client@email.com');
```


### Создать заказ
```php
$orderBuilder = $client->order()->orderBuilder()
    ->setAddress('Address')
    ->setCity('City')
    ->setComment('Comment')
    ->setCountry('Country')
    ->setEmail('client@email.com')
    ->setFirstName('FirstName')
    ->setLastName('LastName')
    ->setMiddleName('MiddleName')
    ->setPhone('+77777777777')
    ->setPostalcode('000000')
    ->setRegion('Region')
    ->setTag('Tag')
    ->setUtmTags('source', 'medium', 'campaign', 'content', 'term')
    ->addProduct('code1', 1000)
    ->addProduct('code2', 2000);

$order = $client->order()->create($orderBuilder);
```


### Обновить статус заказа
```php
$client->order()->updateStatus(1234567890, 'cancel');
```


### Удалить заказ
```php
$client->order()->delete(1234567890);
```


## Получение заказа при оповещении скрипта
```php
// Создание заказа
$order = $client->callback()->newOrder();

// Отмена заказа
$order = $client->callback()->cancelOrder();

// Оплата заказа
$order = $client->callback()->paidOrder();

// Предоплата заказа
$order = $client->callback()->prepaidOrder();
```