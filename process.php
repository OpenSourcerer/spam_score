<?php
/**
 * Spam Score
 * Problem: Users keep sending out spam emails through your web application!
 * Solution: Use a spam scorer.  If users have more than n% spam score,
 * don't allow it to be sent!
 */

$scorer = new SpamScorer();
$text = $_POST['text'];

$scorer->scoreText($text);
if ($scorer->shouldSendEmail()) {
	// Send Email
}else{
	// Display Error for our Potty Mouths!
}