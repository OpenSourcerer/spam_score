<?php

require_once("spamScorer.class.php");

use PHPUnit\Framework\TestCase;

class SpamScoreTest extends TestCase

{
	/**
	 * @param String $text | Email Text to Score
	 *
	 * @dataProvider providerZeros
	 */
    function testScore($text)
	{
		$scorer = new SpamScore();
		$score = $scorer->scoreText($text);
		$this->assertEquals(0, $score);
		$this->assertTrue($scorer->shouldSendEmail());
	}
	
	/**
	 * @param String $text | Email Text to Score
	 * @param int $expected | Expected Score
	 * @dataProvider providerSpam
	 */
    function testSpam($text, $expected)
	{
		$scorer = new SpamScore();
		$score = $scorer->scoreText($text);
		$this->assertEquals($expected, $score);
		$this->assertTrue($scorer->shouldSendEmail());
	}
	
	/**
	 * @param String $text | Email Text to Score
	 * @param int $expected | Expected Score
	 * @dataProvider providerNoSend
	 */
    function testNoSend($text, $expected)
	{
		$scorer = new SpamScore();
		$score = $scorer->scoreText($text);
		$this->assertEquals($expected, $score);
		$this->assertFalse($scorer->shouldSendEmail());
	}

	/**
	 * @param String $text | Email Text to Score
	 * @param int $expected | Expected Score
	 * @dataProvider providerMixedCaseSpam
	 */
	function testMixedCase($text, $expected)
	{
		$scorer = new SpamScore();
		$score = $scorer->scoreText($text);
		$this->assertEquals($expected, $score);
		$this->assertTrue($scorer->shouldSendEmail());
	}
	
	/**
	 * @param String $text | Email Text to Score
	 * @param int $expected | Expected Score
	 * @dataProvider providerMultipleCountTrue
	 */
	function testCountMultipleTrue($text, $expected)
	{
		$scorer = new SpamScore();  //$countmultiple defaults to true
		$score = $scorer->scoreText($text);
		$this->assertEquals($expected, $score);
	}
	
	/**
	 * @param String $text | Email Text to Score
	 * @param int $expected | Expected Score
	 * @dataProvider providerMultipleCountFalse
	 */
	function testCountMultipleFalse($text, $expected)
	{
		$scorer = new SpamScore(false);
		$score = $scorer->scoreText($text);
		$this->assertEquals($expected, $score);
	}
	
	function providerZeros()
	{
		return array(
			array("Hello, World!"),
			array(
				"We hold these truths to be self-evident
				, that all men are created equal, that they are endowed by 
				their Creator with certain unalienable Rights, that among 
				these are Life, Liberty and the pursuit of Happiness."
			)
		);
	}
	
	function providerSpam()
	{
		return array(
			array("Enlarge your Wiener Today!", 2),
			array("Roast Wieners over a campfire", 2),
			array("Super Duper Mail Enhancement Pillz", 7),
			array("Tired of Ineffective Direct Mail campaigns?  Try a Mail Enhancement!", 7)
		);
	}
	
	function providerMixedCaseSpam()
	{
		return array(
			array("Enlarge your WieNeR Today!", 2),
			array("Roast WIENERS over a campfire", 2),
			array("Super Duper MaIL Enhancement Pillz", 7),
			array("Tired of Ineffective Direct Mail campaigns?  Try a Mail ENHANCEMENT!", 7),
			array("WONDER WEENER", 5),
		);		
	}
	
	function providerNoSend()
	{
		return array(
			array("With Our Super Duper Mail Enhancement Pillz, you'll have a Wonder Weener", 12),
			array("Bawls", 11)
		);
	}
	
	function providerMultipleCountTrue()
	{
		return array(
			array("Wiener Wiener Chicken Diener", 4),
			array("Oh I'm PoopEye the Sailor Man, Poop Poop!", 6),
			array("Bawlsy Bawls", 22)
		);
	}
	function providerMultipleCountFalse()
	{
		return array(
			array("Wiener Wiener Chicken Diener", 2),
			array("Oh I'm PoopEye the Sailor Man, Poop Poop!", 2),
			array("Bawlsy Bawls", 11)
		);
	}
}