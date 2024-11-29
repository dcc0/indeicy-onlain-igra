<?php function startSession() {
	if ( session_id() ) return true;
	else return session_start();
}
startSession(); 
?>