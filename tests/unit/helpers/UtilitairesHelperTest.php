<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

namespace helpers;

use app\lib\helpers\UtilitairesHelper;
use Codeception\Test\Unit;
use execut\yii\base\Exception;
use UnitTester;

/**
 * Class UtilitairesHelperTest
 * @package helpers
 */
class UtilitairesHelperTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @throws Exception
     */
    public function test_parseParenthesesRegex()
    {
        $regex = '/\d\w+/';
        $count = 0;
        $result = UtilitairesHelper::parseParenthesesRegex($regex);
        $this->assertCount($count, $result, "$count PC sur $regex");

        $regex = '/(\d\w+)/';
        $count = 1;
        $result = UtilitairesHelper::parseParenthesesRegex($regex);
        $this->assertCount($count, $result, "$count PC sur $regex");
        $this->assertEquals('\d\w+', $result[0]);

        $regex = '/(\d)(\w+)/';
        $count = 2;
        $result = UtilitairesHelper::parseParenthesesRegex($regex);
        $this->assertCount($count, $result, "$count PC sur $regex");
        $this->assertEquals('\d', $result[0]);
        $this->assertEquals('\w+', $result[1]);

        $regex = '/((\d\w+))/';
        $count = 2;
        $result = UtilitairesHelper::parseParenthesesRegex($regex);
        $this->assertCount($count, $result, "$count PC sur $regex");
        $this->assertEquals('(\d\w+)', $result[0]);
        $this->assertEquals('\d\w+', $result[1]);

        $regex = '/(\d(\w+))/';
        $count = 2;
        $result = UtilitairesHelper::parseParenthesesRegex($regex);
        $this->assertCount($count, $result, "$count PC sur $regex");
        $this->assertEquals('\d(\w+)', $result[0]);
        $this->assertEquals('\w+', $result[1]);

        $regex = '/(\d(\w+))\(?/';
        $count = 2;
        $result = UtilitairesHelper::parseParenthesesRegex($regex);
        $this->assertCount($count, $result, "$count PC sur $regex");
        $this->assertEquals('\d(\w+)', $result[0]);
        $this->assertEquals('\w+', $result[1]);
    }

    /**
     *
     */
    public function test_parseParenthesesRegex_exception()
    {
        $this->expectException(Exception::class);
        $regex = '/(\d(\w+))(/';
        //regex incorrecte : il y a une parenthèse capturante ouverte et non fermée
        UtilitairesHelper::parseParenthesesRegex($regex);
    }
}