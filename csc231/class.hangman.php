<?php

ob_start();

class Hangman
{
	//The word the user has to guess in order to win the game
	var $sWord;
	
	//The word the user guessed in the 'guess word' field
	var $sWordGuess;
	
	//The length of the hangman word
	var $iWordLength;
	
	//letters that were already tried
	var $aTriedLetters = array();
	
	//array of letters the word contains
	var $aLetters = array();
	
	//array of status-images
	var $aImages = array();
	
	//array of keys which are guessed
	var $aGuessedKeys = array();
	
	//game status
	var $iStatus;
	
	//the number of guesses the user made
	var $iGuesses;
	
	
	//initialize the game variables
	
	function Hangman()
	{
		//chose a random word from the $words array
		$words=array();
		$fp=fopen('hangwords.txt', 'r');
		while (!feof($fp))
		{
    	$line=fgets($fp);

    	//process line however you like
    	$line=trim($line);

    	//add to array
    	$words[]=$line;

		}
		fclose($fp);
		
		$this->sWord = $words[ rand( 0, sizeof( $words ) ) ];
		
		//define the word's length
		$this->iWordLength = strlen( $this->sWord );
		
		//fill the array with the individual letters of the word
		for( $i = 0; $i < $this->iWordLength; $i++ )
		{
			$this->aLetters[ $i ] = substr( $this->sWord, $i, 1 );
		}
		
		//fill the array with the status images, located in the 'images' folder
		for( $i = 1; $i <= 11; $i++ )
		{
			$this->aImages[ $i ] = "images/$i.jpeg";
		}
		
		//other variables
		$this->iStatus = 0;
		$this->iGuesses = 0;
	}
	
	//check if the letter '$sLetter' is in the word, if so, add it to the guessed keys
	function checkLetter( $sLetter )
	{
		$aTemp = array_values( $this->aLetters );
		$iCount = 0;
		//for each value in the '$aTemp' array, check if the value equals '$sLetter'
		for( $i = 0; $i < sizeof( $aTemp ); $i++ )
		{
			if( $aTemp[ $i ] == $sLetter )
			{
				//if it is, add the letter to the guessed keys and add one to '$iCount'
				$this->aGuessedKeys[ $i ] = $i;
				$iCount++;
			}
		}
		
		//if the letter wasn't in the word, update the hangman status
		if( $iCount == 0 )
		{
			$this->iStatus++;
		}
		
		//add the letter to the tried letters and update the amount of guesses
		$this->aTriedLetters[ $sLetter ] = $sLetter;
		$this->iGuesses++;
		
		return;
	}
	
	//if the user guessed a word, set the class's property to '$sWord'
	function setGuessWord( $sWord )
	{
		$this->sWordGuess = $sWord;
		
		//check if '$sWord' is the same as the word that needs to be guessed
		if( $this->sWordGuess != $this->sWord )
		{
			//if it isn't, update the status
			$this->iStatus++;
		}
		
		//update the guesses
		$this->iGuesses++;
		
		return true;
	}
	
	//check if the word is guessed already
	function wordGuessed()
	{
		if( sizeof( $this->aGuessedKeys ) == $this->iWordLength 
		    || $this->sWordGuess == $this->sWord )
		{
			return true;
		} else {
			return false;
		}
	}
	
	//check if the user is dead
	function checkDead()
	{
		if( $this->iStatus == 11 )
		{
			return true;
		} else {
			return false;
		}
	}
	
	//return the relative URL of the status image
	function getStatus()
	{
		return $this->aImages[ $this->iStatus ];
	}
	
	//print the word
	function printWord()
	{
		for( $i = 0; $i < $this->iWordLength; $i++ )
		{
			//if the letter was guessed
			if( array_key_exists( $i, $this->aGuessedKeys ) )
			{
				//print the letter
				print( substr( $this->sWord, $i, 1 ) . "&nbsp;" );
			} else {
				//print a underscore( _ )
				print( "_&nbsp;" );
			}
		}
		
		return;
	}
}

?>