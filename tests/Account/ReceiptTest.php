<?php


class ReceiptsTest extends TestCase
{
    use \Helpers\ActingAsQuizmaster;

    public function setUp()
    {
        parent::setUp();

        $this->setupAsQuizmaster();
    }

    /**
     * @test
     */
    public function canViewReceipt()
    {
        $this
            ->visit('/account/receipts')
            ->click('#1000')
            ->assertResponseOk();
    }
}
