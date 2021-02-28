<?php
declare(strict_types=1);

namespace SMSolver\Payload;


class Request
{
    /** @var array<string|array> $requestFields */
    private array $requestFields;

    /** @var array<string, string> $headers */
    private array $headers;

    /**
     * Request constructor.
     * @param array<string|array> $requestFields
     * @param array<string, string> $headers
     */
    public function __construct(array $requestFields, array $headers = [])
    {
        $this->requestFields = $requestFields;
        $this->headers = $headers;
    }

    /**
     * @param string $parameterName
     * @return string|array<string>|null
     */
    public function getParameter(string $parameterName)
    {
        return $this->hasParameter($parameterName) ? $this->requestFields[$parameterName] : null;
    }

    public function getIntParameter(string $parameterName): ?int
    {
        return $this->hasParameter($parameterName) ?
            (int)$this->getParameter($parameterName) : null;
    }

    public function getStringParameter(string $parameterName): ?string
    {
        if ($this->hasParameter($parameterName)) {
            $parameter = $this->getParameter($parameterName);
            return is_string($parameter) ? $parameter : null;
        } else {
            return null;
        }
    }

    /**
     * @param string $parameterName
     * @return array<array|scalar>|null
     */
    public function getArrayParameter(string $parameterName): ?array
    {
        if ($this->hasParameter($parameterName)) {
            $parameter = $this->getParameter($parameterName);
            return is_array($parameter) ? $parameter : null;
        } else {
            return null;
        }
    }

    public function hasParameter(string $parameterName): bool
    {
        return isset($this->requestFields[$parameterName]) && !empty($this->requestFields[$parameterName]);
    }

    public function getAction(): ?string
    {
        return $this->getStringParameter('action');
    }

    public function getTrimmedParameter(string $parameterName): string
    {
        return trim($this->getStringParameter($parameterName) ?? '');
    }

    // Headers

    public function getHeader(string $headerName): ?string
    {
        return $this->hasHeader($headerName) ? $this->headers[$headerName] : null;
    }

    public function hasHeader(string $headerName): bool
    {
        return isset($this->headers[$headerName]);
    }
}