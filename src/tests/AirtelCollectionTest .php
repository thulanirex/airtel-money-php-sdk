<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Thulani\AirtelMoneyPhpSdk\AirtelCollection;
use Thulani\AirtelMoneyPhpSdk\AirtelMoneyApi;

class AirtelCollectionTest extends TestCase
{
    protected $api;

    protected $collection;

    public function setUp(): void
    {
        parent::setUp();

        // Set up the Airtel Money API client with configuration values
        $config = [
            'client_id' => 'CLIENT_ID',
            'client_secret' => 'CLIENT_SECRET',
            'public_key' => 'PUBLIC_KEY',
            'env' => 'staging',
            'country' => 'ZM',
            'currency' => 'ZMW'
        ];

        $this->api = new AirtelMoneyApi($config);

        // Set up the Airtel Collection service with the API client
        $this->collection = new AirtelCollection($this->api);
    }

    public function testInitiateUssdPush()
    {
        // Set up test data
        $amount = '100';
        $phoneNumber = '123456789';
        $transactionRef = 'TEST_REF';
        $country = 'ZM';
        $currency = 'ZMW';

        // Call the initiateUssdPush method and capture the response
        $response = $this->collection->initiateUssdPush($amount, $phoneNumber, null, $transactionRef, $currency, $country);

        // Assert that the response contains the expected data
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('status_description', $response);
        $this->assertArrayHasKey('reference', $response);
    }

    public function testRequestRefund()
    {
        // Set up test data
        $transactionId = 'TRANSACTION_ID';
        $country = 'ZM';
        $currency = 'ZMW';

        // Call the requestRefund method and capture the response
        $response = $this->collection->requestRefund($transactionId, $country, $currency);

        // Assert that the response contains the expected data
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('status_description', $response);
    }

    public function testQueryTransactionStatus()
    {
        // Set up test data
        $transactionId = 'TRANSACTION_ID';
        $country = 'ZM';
        $currency = 'ZMW';

        // Call the queryTransactionStatus method and capture the response
        $response = $this->collection->queryTransactionStatus($transactionId, $country, $currency);

        // Assert that the response contains the expected data
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('status_description', $response);
    }

    public function testProcessReconciliation()
    {
        // Set up test data
        $payload = '{"transaction": {"airtel_money_id": "TRANSACTION_ID", "reference": "REFERENCE"}}';

        // Call the processReconciliation method and capture the response
        $response = $this->collection->processReconciliation($payload, function ($transaction) {
            return $transaction['airtel_money_id'];
        });

        // Assert that the response contains the expected data
        $this->assertEquals('TRANSACTION_ID', $response);
    }
}
