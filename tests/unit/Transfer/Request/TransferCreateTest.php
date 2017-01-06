<?php

namespace PagarMe\SdkTest\Transfer\Request;

use PagarMe\Sdk\Transfer\Request\TransferCreate;
use PagarMe\Sdk\Request;

class TransferCreateTest extends \PHPUnit_Framework_TestCase
{
    const PATH         = 'transfers';

    public function recipientData()
    {
        return [
            [500, uniqid('re'), null],
            [1337, uniqid('re'), 1234567],
            [999, uniqid('re'), 8888888],
            [1000001, uniqid('re'), 1010101],
        ];
    }

    /**
     * @dataProvider recipientData
     * @test
     */
    public function mustPayloadBeCorrect($amount, $recipientId, $bankAccountId)
    {
        $recipientMock = $this->getMockBuilder('PagarMe\Sdk\Recipient\Recipient')
            ->disableOriginalConstructor()
            ->getMock();

        $recipientMock->method('getId')->willReturn($recipientId);

        $transferCreate = new TransferCreate(
            $amount,
            $recipientMock,
            $bankAccountId
        );

        $this->assertEquals(
            [
                'amount'          => $amount,
                'recipient_id'    => $recipientId,
                'bank_account_id' => $bankAccountId
            ],
            $transferCreate->getPayload()
        );

        $this->assertEquals(
            Request::HTTP_POST,
            $transferCreate->getMethod()
        );

        $this->assertEquals(
            self::PATH,
            $transferCreate->getPath()
        );
    }
}
