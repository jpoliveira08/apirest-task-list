<?php

declare(strict_types=1);

namespace App\v1;

/**
 * Responsible for return an stardard consistency json response to the client
 */
class Response
{
    // There is some part of response, data that we want to return to the user
    private string $success;
    private string $httpStatusCode;
    private array $messages = array();
    private $data;
    private bool $toCache = false;
    private array $responseData = array();
    
    public function setSuccess(string $success): void
    {
        $this->success = $success;
    }

    public function setHttpStatusCode(string $httpStatusCode): void
    {
        $this->httpStatusCode = $httpStatusCode;
    }

    public function addMessages(string $message): void
    {
        array_push($this->messages, $message);
    }
    
    public function setData(string $data): void
    {
        $this->data = $data;
    }

    public function toCache(bool $toCache): void
    {
        $this->toCache = $toCache;
    }
}