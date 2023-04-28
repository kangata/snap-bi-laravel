<?php

namespace QuetzalStudio\SnapBi\Tests\Feature;

use Illuminate\Support\Facades\Http;
use QuetzalStudio\SnapBi\Amount;
use QuetzalStudio\SnapBi\Config;
use QuetzalStudio\SnapBi\IntrabankTransfer\Payload;
use QuetzalStudio\SnapBi\IntrabankTransfer\Transfer;
use QuetzalStudio\SnapBi\Tests\TestCase;

class IntrabankTransferTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Http::fake([
            'http://localhost.test/api-test/v1.0/access-token/b2b' => Http::response([
                'responseCode' => '2007300',
                'responseMessage' => 'Sucessful',
                'accessToken' => 'nd8vzUzOHlbKf82Hcn5VP22SdO56WKAoQC7mExbTfd68tPBzQ84Ocv',
                'tokenType' => 'Bearer',
                'expiresIn' => '900',
            ]),
            'http://localhost.test/api-test/v1.0/transfer-intrabank' => Http::response([
                'responseCode' => '2001700',
                'responseMessage' => 'Sucessful',
                'beneficiaryAccountNo' => '0613008761',
                'amount' => [
                    'value' => '100.00',
                    'currency' => 'IDR',
                ],
                'referenceNo' => microtime(),
                'additionalInfo' => [
                    'economicActivity' => '',
                    'transactionPurpose' => '',
                ],
                'partnerReferenceNo' => 'TEST1682707741',
                'sourceAccountNo' => '0611116411',
                'transactionDate' => '2023-04-28T18:51:00+07:00',
            ]),
        ]);
    }

    public function testIntrabankTransfer()
    {
        Config::load('test')->debug(true);

        $transfer = new Transfer(
            origin: 'http://localhost.test',
            channelId: '95051',
            externalId: time(),
        );

        $resp = $transfer->send(new Payload(
            partnerReferenceNo: 'TEST1682707741',
            amount: new Amount(100),
            beneficiaryAccountNo: '0613008761',
            sourceAccountNo: '0611116411',
            transactionDate: '2023-04-28T18:51:00+07:00',
            beneficiaryEmail: 'test@localhost.test',
            remark: 'Test',
            currency: 'IDR',
            additionalInfo: [
                'economicActivity' => '',
                'transactionPurpose' => '',
            ],
        ));

        $this->assertTrue($resp->status() == 200);
    }
}
