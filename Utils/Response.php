<?php
namespace Utils;

class Response {

    private mixed  $data = [];
    private string $status;
    private string $message;
    private int    $httpResponseCode;

    private Array $response;

    public static string $STATUS_OK      = 'ok';
    public static string $STATUS_WARNING = 'warning';
    public static string $STATUS_ERROR   = 'error';

    public static int $HTTP_STATUS_OK           = 200;
    public static int $HTTP_STATUS_BAD_REQUEST  = 400;
    public static int $HTTP_STATUS_UNAUTHORIZED = 401;
    public static int $HTTP_STATUS_FORBIDDEN    = 403;
    public static int $HTTP_STATUS_NOT_FOUND    = 404;

    public static int $HTTP_STATUS_SERVER_ERROR = 500;


    public function __construct(string $status = 'error', string $message = 'Unknown error occured', int $httpResponseCode = 400) {

        $this->setStatus($status);
        $this->setMessage($message);
        $this->setHttpResponseCode($httpResponseCode);
    }

    public function respond() {

        $this->prepareResponse();

        echo json_encode($this->response, JSON_UNESCAPED_UNICODE);
    }

    private function prepareResponse() {

        http_response_code($this->httpResponseCode);

        $this->response = [
            'status'  => $this->status,
            'message' => $this->message,
            'data'    => $this->wrapData($this->data)
        ];
    }
    
    private function wrapData(mixed $data): array {
        // if data is not already an array put it in one
        if(gettype($data) != 'array' && gettype($data) != 'object') {
            return [$data];
        } else {
            return $data;
        }
    }

    protected function getResponse(): Array {
        return $this->response;
    }

    protected function setResponse(Array $response) {
        $this->response = $response;
    }

    public function getData(): array|object {
        return $this->data;
    }

    public function setData(mixed $data) {
        $this->data = $data;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function setStatus(string $status) {
        $this->status = $status;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function setMessage(string $message) {
        $this->message = $message;
    }

    public function getHttpResponseCode(): int {
        return $this->httpResponseCode;
    }

    public function setHttpResponseCode(int $httpResponseCode) {
        $this->httpResponseCode = $httpResponseCode;
    }
}