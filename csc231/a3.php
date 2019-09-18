<?php
require_once( "class.hangman.php" );
print( "<div class='container'><div class='p-3 mb-2 bg-warning text-dark'>HANGMAN</div>" );
print( "Hangman  by <b>Khanafi</b><br /><hr />" );
print( "<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>" );
session_start();

if( isset( $_POST[ "new" ] ) )
{
	session_destroy();
	print( "<script language='javascript'>window.location='a3.php'</script>" );
}

if( isset( $_SESSION[ "hangman" ] ) )
{
	$oGame = $_SESSION[ "hangman" ];
} else {
	$_SESSION[ "hangman" ] = new Hangman();
	$oGame = $_SESSION[ "hangman" ];
}

if( isset( $_POST[ "submit" ] ) )
{
	if( strlen( $_POST[ "word" ] ) == 0 )
	{
		$oGame->checkLetter( $_POST[ "guess" ] );
	} else {
		$oGame->setGuessWord( $_POST[ "word" ] );
	}
}

print( "Current word: <span class='text-success'> " );
$oGame->printWord();
print( "</span><br /><hr />" );

if( $oGame->iStatus != 0 )
{
	print( "Current status: <img src='" . $oGame->getStatus() . "' class='img-fluid' alt='Responsive image' />" );
	print( "<br /><hr />" );
}

if( sizeof( $oGame->aTriedLetters ) != 0 )
{
	print( "Tried letters: <span class='text-danger'> " );
	for( $i = 0; $i < sizeof( $oGame->aTriedLetters ); $i++ )
	{
		print( $oGame->aTriedLetters[ current( $oGame->aTriedLetters ) ] . ", " );
		next( $oGame->aTriedLetters );
	}
	print( "</span><br /><hr />" );
}

if( $oGame->checkDead() )
{
	session_destroy();
	print( "<h3>You lost!</h3>" );
	print( "The word was '" . $oGame->sWord . "'<br />" );
	print( "<a href='a3.php'>Play again</a>" );
	exit();
}

if( $oGame->wordGuessed() )
{
	session_destroy();
	print( "<h3>You won!</h3>" );
	print( "You guessed the word '" . $oGame->sWord . "' " );
	print( "in <b>" . $oGame->iGuesses . "</b> guesses<br />" );
	print( "<a href='a3.php' class='btn btn-primary'>Play again</a>" );
	exit();
}

$_SESSION[ "hangman" ] = $oGame;

print( "<form method='POST' action='a3.php'>" );
print( "Guess letter: " );
print( "<select name='guess' class='form-control col-1'>" );
for( $i = 97; $i < 123; $i++ )
{
	if( array_key_exists( chr( $i ), $oGame->aTriedLetters ) )
	{
		print( "<option disabled>" . chr( $i ) ."</option>" );
	} else {
		print( "<option>" . chr( $i ) . "</option>" );
	}
}
print( "</select>" );
print( "<br />" );
print( "Guess word: <input type='text' name='word' class='input-group-text'/><br />" );
print( "<input type='submit' name='submit' value='Guess'  class='btn btn-primary'/>&nbsp;" );
print( "<input type='submit' name='new' value='New game'  class='btn btn-primary'/>" );
print( "</form></div>" );