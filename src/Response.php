<?php

namespace JustClick;

class Response
{
	/**
	 * @var bool $success
	 */
	private $success;

	/**
	 * @var array $data Массив с результатом
	 */
	private $data;

	/**
	 * @var string $error Сообщение об ошибке
	 */
	private $error;

	/**
	 * @var int $errorCode Код ошибки
	 */
	private $errorCode;

	public function __construct(bool $success, array $data = [], string $error = null, int $errorCode = null)
	{
		$this->success   = $success;
		$this->data      = $data;
		$this->error     = $error;
		$this->errorCode = $errorCode;
	}

	/**
	 * @return boolean Успешен ли запрос
	 */
	public function isSuccess(): bool
	{
		return $this->success;
	}

	/**
	 * @return array Массив с результатом
	 */
	public function getData(): array
	{
		return $this->data;
	}

	/**
	 * @return string|null Сообщение об ошибке
	 */
	public function getError(): ?string
	{
		return $this->error;
	}

	/**
	 * @return int Код ошибки
	 */ 
	public function getErrorCode(): ?int
	{
		return $this->errorCode;
	}
}