<?php

namespace shekili\epoint;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class Epoint
{
    protected Client $client;

    public function __construct(protected array $config)
    {
        $this->client = new Client(['timeout' => 15]);
    }


    public function createPayment(array $data): array
    {
        $data = array_merge([
            'language'             => $this->config['language'] ?? 'az',
            'currency'             => $this->config['currency'] ?? 'AZN',
            'success_redirect_url' => $this->config['success_url'] ?? null,
            'error_redirect_url'   => $this->config['error_url'] ?? null,
            'description'          => $this->config['description'] ?? null,
        ], $data);

        return $this->sendRequest($data, $this->config['api_url']);
    }


    public function getStatus(string $transactionId): array
    {
        $data = [
            'transaction'   => $transactionId,
        ];

        return $this->sendRequest($data, $this->config['status_url']);
    }

    public function sendRequest(array $data, $url = null): array
    {
        $data = array_merge([
            'public_key' => $this->config['public_key'] ?? null,
        ], $data);

        // dd($data, $url);
        try {
            $dataBase64 = base64_encode(json_encode($data, JSON_THROW_ON_ERROR));
            $signature  = $this->generateSignature($dataBase64);

            $response = Http::timeout(30)
                ->retry(2, 100)
                ->asForm()
                ->post($url, [
                    'data'      => $dataBase64,
                    'signature' => $signature,
                ]);

            $result = $response->successful() ? $response->json() : [];

            return array_merge($result ?? [], [
                'http_code' => $response->status(),
                'raw_body'  => $response->body(),
            ]);
        } catch (\Throwable $e) {
            return [
                'status'  => 'error',
                'message' => $e->getMessage()
            ];
        }
    }


    protected function generateSignature(string $data): string
    {
        return base64_encode(sha1($this->config['private_key'] . $data . $this->config['private_key'], true));
    }
}
