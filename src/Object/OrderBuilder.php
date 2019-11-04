<?php

namespace JustClick\Object;

use JustClick\JustClickException;

class OrderBuilder
{
	/**
	 * @var array $params Параметры запроса
	 */
	private $params = [];

	/**
	 * @param string $firstName Имя
	 * @return self
	 */ 
	public function setFirstName(string $firstName): self
	{
		$this->params['bill_first_name'] = $firstName;

		return $this;
	}

	/**
	 * @param string $lastName Фамилия
	 * @return self
	 */ 
	public function setLastName(string $lastName): self
	{
		$this->params['bill_surname'] = $lastName;

		return $this;
	}

	/**
	 * @param string $middleName Отчество
	 * @return self
	 */ 
	public function setMiddleName(string $middleName): self
	{
		$this->params['bill_otchestvo'] = $middleName;

		return $this;
	}

	/**
	 * @param string $email Email адрес
	 * @return self
	 */ 
	public function setEmail(string $email): self
	{
		$this->params['bill_email'] = $email;

		return $this;
	}

	/**
	 * @param string $phone Телефон
	 * @return self
	 */ 
	public function setPhone(string $phone): self
	{
		$this->params['bill_phone'] = $phone;

		return $this;
	}

	/**
	 * @param string $city Город
	 * @return self
	 */ 
	public function setCity(string $city): self
	{
		$this->params['bill_city'] = $city;

		return $this;
	}

	/**
	 * @param string $country Страна
	 * @return self
	 */ 
	public function setCountry(string $country): self
	{
		$this->params['bill_country'] = $country;

		return $this;
	}

	/**
	 * @param string $address Адрес
	 * @return self
	 */ 
	public function setAddress(string $address): self
	{
		$this->params['bill_address'] = $address;

		return $this;
	}

	/**
	 * @param string $region Регион
	 * @return self
	 */ 
	public function setRegion(string $region): self
	{
		$this->params['bill_region'] = $region;

		return $this;
	}

	/**
	 * @param string $postalcode Почтовый индекс
	 * @return self
	 */ 
	public function setPostalcode(string $postalcode): self
	{
		$this->params['bill_postal_code'] = $postalcode;

		return $this;
	}

	/**
	 * @param string $ip IP покупателя
	 * @return self
	 */ 
	public function setIp(string $ip): self
	{
		$this->params['bill_ip'] = $ip;

		return $this;
	}

	/**
	 * @param int $createdAt Дата создания счета
	 * @return self
	 */ 
	public function setCreatedAt(int $createdAt): self
	{
		$this->params['bill_created'] = $createdAt;

		return $this;
	}

	/**
	 * @param string $comment Комментарий к счету
	 * @return self
	 */ 
	public function setComment(string $comment): self
	{
		$this->params['bill_comment'] = $comment;

		return $this;
	}

	/**
	 * @param string $domain Домен заказа
	 * @return self
	 */ 
	public function setDomain(string $domain): self
	{
		$this->params['bill_domain'] = $domain;

		return $this;
	}

	/**
	 * @param string $tag Тэг
	 * @return self
	 */ 
	public function setTag(string $tag): self
	{
		$this->params['bill_tag'] = $tag;

		return $this;
	}

	/**
	 * @param string $kupon Использованный купон
	 * @return self
	 */ 
	public function setKupon(string $kupon): self
	{
		$this->params['bill_kupon'] = $kupon;

		return $this;
	}

	/**
	 * @param int $cancelOrderTime Время автоматической отмены заказа
	 * 0 - отключить автоотмену
	 * 1 - время берется из настроек продукта
	 * timestamp - счет отменится именно в это время
	 * @return self
	 */
	public function setCancelOrderTime(int $cancelOrderTime): self
	{
		$this->params['bill_timer_kill'] = in_array($cancelOrderTime, [0, 1]) ? boolval($cancelOrderTime) : $cancelOrderTime;

		return $this;
	}

	/**
	 * Установить UTM-метки
	 *
	 * @param string $source   utm_source
	 * @param string $medium   utm_medium
	 * @param string $campaign utm_campaign
	 * @param string $content  utm_content
	 * @param string $term     utm_term
	 * @return self
	 */
	public function setUtmTags(string $source = null, string $medium = null, string $campaign = null, string $content = null, string $term = null): self
	{
		$this->params['utm'] = array_filter([
			'utm_source'   => $source,
			'utm_medium'   => $medium,
			'utm_campaign' => $campaign,
			'utm_content'  => $content,
			'utm_term'     => $term,
		]);

		return $this;
	}

	/**
	 * Установить парнерские UTM-метки
	 *
	 * @param string $source   utm_source
	 * @param string $medium   utm_medium
	 * @param string $campaign utm_campaign
	 * @param string $content  utm_content
	 * @param string $term     utm_term
	 * @return self
	 */
	public function setAffUtmTags(string $source = null, string $medium = null, string $campaign = null, string $content = null, string $term = null): self
	{
		$this->params['utm'] = array_filter([
			'aff_source'   => $source,
			'aff_medium'   => $medium,
			'aff_campaign' => $campaign,
			'aff_content'  => $content,
			'aff_term'     => $term,
		]);

		return $this;
	}

	/**
	 * Добавить продукт
	 * 
	 * @param string $code Код продукта
	 * @param float  $sum  Сумма продукта
	 * @return self
	 */ 
	public function addProduct(string $code, float $sum = null): self
	{
		$product['good_name'] = $code;

		if (!is_null($sum)) {
			$product['good_sum'] = $sum;
		}

		$this->params['goods'][] = $product;

		return $this;
	}

	/**
	 * @return array Параметры запроса
	 */ 
	public function getParams(): array
	{
		if (empty($this->params['bill_email'])) {
			throw new JustClickException('Не указан email клиента');
		}

		if (empty($this->params['goods'])) {
			throw new JustClickException('Список продуктов пуст');
		}

		$this->params['bill_created'] = $this->params['bill_created'] ?? time();

		return $this->params;
	}
}