<?php

require_once("config.php");

class SpamScore{
	/*
	 * int $score | Spam Score
   	 */
	private $score;
	
	function __construct()
	{
		$this->score = 0;
	}
	
	/**
	 * Reads XML File to Array
	 * @param String $file | XML file path
	 * @return array | Array of no-no words => score
	 */
	private function readXML($file)
	{
		return simplexml_load_file($file);
	}
	
	/**
	 * Processes email text and gives sum total
	 * @param String $text | Email Text
	 * @return int $score | Total Score
	 */
	 public function scoreText($text)
	 {
		$noNoWords = $this->readXML(SPAMWORDSFILE);
		foreach($noNoWords as $word){
			$this->score += $word->score * substr_count(strtoupper($text), strtoupper($word->palabra));
		}
		return $this->score;
	 }
	 
	 /**
	  * Determines Whether email can be sent.
	  */
	  public function shouldSendEmail()
	  {
		  return $this->score <= NOSENDTHRESHOLD;
	  }
}