<?php

namespace Rate;
use App\Services\RateService;
use Tests\UnitTestCase;

/**
 * Class UnitTest
 */
class RateTest extends UnitTestCase
{
    public function testTestCase()
    {
        $service = new RateService();

        $this->assertEquals(
            $service->getTransactions(),
            []
        );

        $fakeData = [
            '0' => [
                'accountID' => 1,
                'amount'    => 2
            ],
            '1' => [
                'accountID' => 1,
                'amount'    => 1
            ]
        ];

        $service->calculate($fakeData);

        $this->assertEquals(
            $service->getTransactions(),
            [
                '1' => [
                    'dayDepositsSum' => 3,
                    'rates' => 0.03
                ]
            ]
        );

        $fakeData2 = [
            '0' => [
                'accountID' => 1,
                'amount'    => 2
            ],
            '1' => [
                'accountID' => 1,
                'amount'    => 1
            ],
            '2' => [
                'accountID' => 2,
                'amount'    => 10
            ]
        ];
        $service->calculate($fakeData2);

        $this->assertEquals(
            $service->getTransactions(),
            [
                '1' => [
                    'dayDepositsSum' => 3,
                    'rates' => 0.03
                ],
                '2' => [
                    'dayDepositsSum' => 10,
                    'rates' => 0.1
                ]
            ]
        );

    }
}