<?php

namespace JustClick\Operation;

use JustClick\Api;
use JustClick\Response;

class AbstractOperation
{
	/**
	 * @var Api $api
	 */
	protected $api;

	/**
	 * @var Response $lastResponse Последний ответ
	 */
	private $lastResponse;

	/**
	 * @param Api $api
	 */
	public function __construct(Api $api)
	{
		$this->api = $api;
	}

	/**
	 * @return Response|null Поледний ответ
	 */
	public function getLastResponse(): ?Response
	{
		return $this->lastResponse;
	}

	/**
	 * Отправить запрос
	 *
	 * @param string $method
	 * @param array  $params
	 * @return Response
	 */
	protected function request(string $method, array $params = []): Response
	{
		return $this->lastResponse = $this->api->request($method, $params);
	}
}