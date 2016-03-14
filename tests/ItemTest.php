<?php

use BibleBowl\Item;
use BibleBowl\Program;

class ItemTest extends TestCase
{
    /**
     * @test
     */
    public function generatesSeasonalRegistrationDescription()
    {
        foreach(Program::all() as $program) {
            $item = new Item([
                'sku' => $program->sku
            ]);
            $this->assertEquals($program->name.' Seasonal Registration', $item->name());
        }
    }
}
