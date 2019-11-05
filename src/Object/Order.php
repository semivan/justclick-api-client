<?php

namespace JustClick\Object;

class Order
{
	/**
	 * @var int $id Номер счета
	 */
	private $id;

	/**
	 * @var string $firstName Имя
	 */
	private $firstName;

	/**
	 * @var string $lastName Фамилия
	 */
	private $lastName;

	/**
	 * @var string $middleName Отчество
	 */
	private $middleName;

	/**
	 * @var string $email Email адрес
	 */
	private $email;

	/**
	 * @var string $phone Телефон
	 */
	private $phone;

	/**
	 * @var string $city Город
	 */
	private $city;

	/**
	 * @var string $country Страна
	 */
	private $country;

	/**
	 * @var string $address Адрес
	 */
	private $address;

	/**
	 * @var string $region Регион
	 */
	private $region;

	/**
	 * @var string $postalcode Почтовый индекс
	 */
	private $postalcode;

	/**
	 * @var int $createdAt Дата создания счета
	 */
	private $createdAt;

	/**
	 * @var string $payStatus Статус счета
	 */
	private $payStatus;

	/**
	 * @var int $paidAt Дата оплаты счета
	 */
	private $paidAt;

	/**
	 * @var string $payway Способ оплаты
	 */
	private $payway;

	/**
	 * @var string $comment Комментарий к счету
	 */
	private $comment;

	/**
	 * @var string $domain Домен заказа
	 */
	private $domain;

	/**
	 * @var string $link Ссылка на страницу оплаты счета
	 */
	private $link;

	/**
	 * @var float $price Стоимость продукта
	 */
	private $price;

	/**
	 * @var float $lastPaymentSum Сумма последней оплаты
	 */
	private $lastPaymentSum;

	/**
	 * @var float $prepaymentSum Сумма предоплаты
	 */
	private $prepaymentSum;

	/**
	 * @var bool $isRecurrent Признак рекуррентного счета
	 */
	private $isRecurrent;

	/**
	 * @var float $sumToPay Осталось к оплате
	 */
	private $sumToPay;

	/**
	 * @var bool $prepaymentEnabled Разрешены ли предоплаты
	 */
	private $prepaymentEnabled;
	
	/**
	 * @var float $prepaymentMinSum Минимальная сумма предоплаты
	 */
	private $prepaymentMinSum;
	
	/**
	 * @var string $tag Тэг
	 */
	private $tag;

	/**
	 * @var string $kupon Использованный купон
	 */
	private $kupon;

	/**
	 * @var string $utmSource utm_source
	 */
	private $utmSource;

	/**
	 * @var string $utmMedium utm_medium
	 */
	private $utmMedium;

	/**
	 * @var string $utmCampaign utm_campaign
	 */
	private $utmCampaign;

	/**
	 * @var string $utmContent utm_content
	 */
	private $utmContent;

	/**
	 * @var string $utmTerm utm_term
	 */
	private $utmTerm;

	/**
	 * @var OrderItem[] $items Элементы заказа
	 */
	private $items = [];

	public function __construct(array $properties = [])
	{
		foreach ($properties as $property => $value) {
			if (property_exists($this, $property)) {
				$this->$property = $value;
			}
		}
	}

	/**
	 * @return int Номер счета
	 */ 
	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @return string Имя
	 */ 
	public function getFirstName(): ?string
	{
		return $this->firstName;
	}

	/**
	 * @return string Фамилия
	 */ 
	public function getLastName(): ?string
	{
		return $this->lastName;
	}

	/**
	 * @return string Отчество
	 */ 
	public function getMiddleName(): ?string
	{
		return $this->middleName;
	}

	/**
	 * @return string Email адрес
	 */ 
	public function getEmail(): ?string
	{
		return $this->email;
	}

	/**
	 * @return string Телефон
	 */ 
	public function getPhone(): ?string
	{
		return $this->phone;
	}

	/**
	 * @return string Город
	 */ 
	public function getCity(): ?string
	{
		return $this->city;
	}

	/**
	 * @return string Страна
	 */ 
	public function getCountry(): ?string
	{
		return $this->country;
	}

	/**
	 * @return string Адрес
	 */ 
	public function getAddress(): ?string
	{
		return $this->address;
	}

	/**
	 * @return string Регион
	 */ 
	public function getRegion(): ?string
	{
		return $this->region;
	}

	/**
	 * @return string Почтовый индекс
	 */ 
	public function getPostalcode(): ?string
	{
		return $this->postalcode;
	}

	/**
	 * @param boolean $toString Конвертировать в дату?
	 * @param string  $format   Формат даты
	 * @return int|string|null Дата создания счета
	 */
	public function getCreatedAt(bool $toString = true, string $format = 'Y-m-d H:i:s')
	{
		if (!$this->createdAt) {
			return null;
		}

		return $toString ? date($format, $this->createdAt) : $this->createdAt;
	}

	/**
	 * @return string Статус счета
	 */ 
	public function getPayStatus(): ?string
	{
		return $this->payStatus;
	}

	/**
	 * @param boolean $toString Конвертировать в дату?
	 * @param string  $format   Формат даты
	 * @return int|string|null Дата оплаты счета
	 */
	public function getPaidAt(bool $toString = true, string $format = 'Y-m-d H:i:s')
	{
		if (!$this->paidAt) {
			return null;
		}

		return $toString ? date($format, $this->paidAt) : $this->paidAt;
	}

	/**
	 * @return string Способ оплаты
	 */ 
	public function getPayway(): ?string
	{
		return $this->payway;
	}

	/**
	 * @return string Комментарий к счету
	 */ 
	public function getComment(): ?string
	{
		return $this->comment;
	}

	/**
	 * @return string Домен заказа
	 */ 
	public function getDomain(): ?string
	{
		return $this->domain;
	}

	/**
	 * @return string Ссылка на страницу оплаты счета
	 */ 
	public function getLink(): ?string
	{
		return $this->link;
	}

	/**
	 * @return float Стоимость продукта
	 */ 
	public function getPrice(): ?float
	{
		return $this->price;
	}

	/**
	 * @return float Сумма последней оплаты
	 */ 
	public function getLastPaymentSum(): ?float
	{
		return $this->lastPaymentSum;
	}

	/**
	 * @return float Сумма предоплаты
	 */ 
	public function getPrepaymentSum(): ?float
	{
		return $this->prepaymentSum;
	}

	/**
	 * @return bool Признак рекуррентного счета
	 */ 
	public function getIsRecurrent(): ?bool
	{
		return $this->isRecurrent;
	}

	/**
	 * @return float Осталось к оплате
	 */ 
	public function getSumToPay(): ?float
	{
		return $this->sumToPay;
	}

	/**
	 * @return bool Разрешены ли предоплаты
	 */ 
	public function getPrepaymentEnabled(): ?bool
	{
		return $this->prepaymentEnabled;
	}

	/**
	 * @return float Минимальная сумма предоплаты
	 */ 
	public function getPrepaymentMinSum(): ?float
	{
		return $this->prepaymentMinSum;
	}

	/**
	 * @return string Тэг
	 */ 
	public function getTag(): ?string
	{
		return $this->tag;
	}

	/**
	 * @return string Использованный купон
	 */ 
	public function getKupon(): ?string
	{
		return $this->kupon;
	}

	/**
	 * @return string utm_source
	 */ 
	public function getUtmSource(): ?string
	{
		return $this->utmSource;
	}

	/**
	 * @return string utm_medium
	 */ 
	public function getUtmMedium(): ?string
	{
		return $this->utmMedium;
	}

	/**
	 * @return string utm_campaign
	 */ 
	public function getUtmCampaign(): ?string
	{
		return $this->utmCampaign;
	}

	/**
	 * @return string utm_content
	 */ 
	public function getUtmContent(): ?string
	{
		return $this->utmContent;
	}

	/**
	 * @return string utm_term
	 */ 
	public function getUtmTerm(): ?string
	{
		return $this->utmTerm;
	}

	/**
	 * @return OrderItem[] Элементы заказа
	 */ 
	public function getItems(): array
	{
		return $this->items;
	}
}