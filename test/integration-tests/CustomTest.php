<?php

class CustomTest extends PhpRack_Test
{

    public function testCustomAssertionsAreValid()
    {
        $this->assert->fail("This test is just failed, always");
    }

}