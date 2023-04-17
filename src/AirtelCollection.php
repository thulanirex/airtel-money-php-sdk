<?php

namespace Thulani\AirtelMoneyPhpSdk;

use GuzzleHttp\Exception\BadResponseException;

class AirtelCollection extends AirtelService
{
    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    public function initiateUssdPush($amount, $phoneNumber, $transactionId = null, $transactionRef = null, $currency = null, $country = null, $callback = null)
    {
        $defaultParams = [
            'country' => $this->country,
            'currency' => $this->currency
        ];

        try {
            $response = $this->client->request('POST', '/merchant/v1/payments/', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Country' => $defaultParams['country'],
                    'X-Currency' => $defaultParams['currency'],
                    'Authorization' => 'Bearer ' . $this->token,
                ],
                'json' => [
                    'reference' => $transactionRef ?? random_bytes(8),
                    'subscriber' => [
                        'country' => $country ?? $defaultParams['country'],
                        'currency' => $currency ?? $defaultParams['currency'],
                        'msisdn' => $phoneNumber,
                    ],
                    'transaction' => [
                        'amount' => $amount,
                        'country' => $country ?? $defaultParams['country'],
                        'currency' => $currency ?? $defaultParams['currency'],
                        'id' => $transactionId ?? random_bytes(8),
                    ],
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            return $callback ? $callback($result) : $result;
        } catch (BadResponseException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function requestRefund($transactionId, $country = null, $currency = null, $callback = null)
    {
        try {
            $response = $this->client->request('POST', '/standard/v1/payments/refund', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Country' => $country ?? $this->country,
                    'X-Currency' => $currency ?? $this->currency,
                    'Authorization' => 'Bearer ' . $this->token,
                ],
                'json' => [
                    'transaction' => [
                        'airtel_money_id' => $transactionId,
                    ],
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            return $callback ? $callback($result) : $result;
        } catch (BadResponseException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function queryTransactionStatus($transactionId, $country = null, $currency = null, $callback = null)
    {
        try {
            $response = $this->client->request('GET', "/standard/v1/payments/{$transactionId}", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Country' => $country ?? $this->country,
                    'X-Currency' => $currency ?? $this->currency,
                    'Authorization' => 'Bearer ' . $this->token,
                ],
                'json' => [
                    'transaction' => [
                        'airtel_money_id' => $transactionId,
                    ],
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            return $callback ? $callback($result) : $result;
        } catch (BadResponseException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function processReconciliation($payload, $callback = null)
    {
        $data = json_decode($payload, true);
        return $callback
            ? call_user_func_array($callback, $data['transaction'])
            : $data['transaction'];
    }
}

