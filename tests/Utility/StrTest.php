<?php

use DreamCodeFramework\Utility\Str;

class StrTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function pop_removes_part_of_the_string_when_using_a_dalid_delimiter()
    {
        $string = 'popped string';
        $this->assertEquals('popped', Str::pop($string));
        $this->assertEquals('string', $string);
    }

    /**
     * @test
     */
    public function pop_respects_the_passed_delimiter_and_uses_it_to_pop()
    {
        $string = 'popped.string';
        $this->assertEquals('popped', Str::pop($string, '.'));
        $this->assertEquals('string', $string);
    }

    /**
     * @test
     */
    public function pop_does_not_do_anything_to_the_string_when_it_does_not_contain_the_given_delimiter()
    {
        $string = 'missing delimiter';
        $this->assertNull(Str::pop($string, '.'));
        $this->assertEquals('missing delimiter', $string);
    }
}
