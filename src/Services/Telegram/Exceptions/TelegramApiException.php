<?php

namespace Alihesari\Larasap\Services\Telegram\Exceptions;

class TelegramApiException extends \Exception
{
    protected $httpCode;
    protected $errorCode;
    protected $parameters;

    public function __construct($message, $httpCode = 0, $errorCode = null, $parameters = null)
    {
        parent::__construct($message);
        $this->httpCode = $httpCode;
        $this->errorCode = $errorCode;
        $this->parameters = $parameters;
    }

    public function getHttpCode()
    {
        return $this->httpCode;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function getParameters()
    {
        return $this->parameters;
    }
} 