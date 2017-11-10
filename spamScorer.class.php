<?php

require_once("config.php");

class SpamScore{
	/**
	 * int $score | Spam Score
   	 */
	private $score;
	
	/**
	 * boolean $countmultiple | Whether or not to count multiple occurences of a string
	 */ 
	 private $countmultiple;
	 
	function __construct($countmultiple = true)
	{
		$this->score = 0;
		$this->setCountMultiple($countmultiple);
	}
	
	/**
	 * @param boolean $countmultiple | Whether or not to count multiple occurences of a string
	 */
	public function setCountMultiple($countmultiple)
	{
		$this->countmultiple = $countmultiple;
	}
	
	/**
	 * @return boolean $this->countmultiple
	 */ 
	public function getCountMultiple()
	{
		return $this->countmultiple;
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
		foreach ($noNoWords as $word) {
			if ($this->getCountMultiple()) { 
				$this->score += $word->score * substr_count(strtoupper($text), strtoupper($word->palabra));
			} else {
				if (substr_count(strtoupper($text), strtoupper($word->palabra))) {
					$this->score += $word->score;
				}
			}
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