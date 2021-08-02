<?php

namespace Litlife\EnotIoPayments\Responses;

use GuzzleHttp\Psr7\Response;
use RuntimeException;

class JsonResponse
{
    protected $response;
    protected $contents;
    protected $json;

    /**
     * Construct response
     *
     * @param Response $response
     * @throws \Exception
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
        $this->contents = $this->response->getBody()->getContents();
        $this->json = json_decode($this->contents, true);
    }

    /**
     * Returns true if the response is successful
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->getStatus() == 'success';
    }

    /**
     * Getting the response status
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->json['status'];
    }

    /**
     * Getting the error message if the response is incorrect
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getErrorMessage(): ?string
    {
        if (!$this->isError())
            throw new RuntimeException('The response status must be an error');

        return $this->json['message'] ?? '';
    }

    /**
     * Returns true if the response is error
     *
     * @return bool
     */
    public function isError(): bool
    {
        return $this->getStatus() == 'error';
    }

    /**
     * Returns the entire json of the response
     *
     * @return array
     */
    public function getJson(): array
    {
        return $this->json;
    }
}
