<?php

declare(strict_types=1);

namespace App\v1\model;

/**
 * Responsible for return an stardard consistency json response to the client
 */
class Response
{
    /** @var bool $success Success flag */
    private bool $success;
    /** @var int $httpStatusCode Status Http Code */
    private int $httpStatusCode;
    /** @var array $messages Array that can have more than one message held */
    private array $messages = array();
    /** @var string $data Contains the data that we'll return */
    private string $data;
    /** @var bool $isCacheEnabled Check if is necessary cache the response */
    private bool $isCacheEnabled = false;
    /** @var array $responseData Array that contains the headers of response requisiton */
    private array $responseData = array();

    /**
     * Set the success flag
     *
     * @param boolean $success
     * @return void
     */
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    /**
     * Set the httpStatusCode
     *
     * @param int $httpStatusCode
     * @return void
     */
    public function setHttpStatusCode(int $httpStatusCode): void
    {
        $this->httpStatusCode = $httpStatusCode;
    }

    /**
     * Adding message to the array messages
     *
     * @param string $message
     * @return void
     */
    public function addMessages(string $message): void
    {
        array_push($this->messages, $message);
    }

    /**
     * Set the data that will be returned for the user
     *
     * @param array $data
     * @return void
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * Set the cache state
     *
     * @param boolean $isCacheEnabled
     * @return void
     */
    public function setIsCacheEnabled(bool $isCacheEnabled): void
    {
        $this->isCacheEnabled = $isCacheEnabled;
    }

    /**
     * Set the header of http request for cache control
     *
     * @return void
     */
    private function cacheControl(): void
    {
        if ($this->isCacheEnabled) {
            header('Cache-control: max-age=60');
            return;
        }
        header('Cache-control: no-cache, no-store');
        return;
    }

    /**
     * Send data
     *
     * @return void
     */
    public function send(): void
    {
        header('Content-type: application/json;charset=utf-8');

        $this->cacheControl();

        if (!is_bool($this->success) || !is_numeric($this->httpStatusCode)) {
            http_response_code(500);

            $this->responseData['statusCode'] = 500;
            $this->responseData['success'] = false;
            $this->addMessages("Response creation error");
            $this->responseData['messages'] = $this->messages;
        } else {
            http_response_code($this->httpStatusCode);
            $this->responseData['statusCode'] = $this->httpStatusCode;
            $this->responseData['success'] = $this->success;
            $this->responseData['messages'] = $this->messages;
        }

        echo json_encode($this->responseData);
    }
}
