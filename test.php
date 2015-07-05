<?php

	include 'TelegramNotifier.php';
	
	$apiKey = '---insert-your-API-Key-here---';
	
	try {
		(new TelegramNotifier($apiKey))->sendMessage('Hello world');
	} catch (Exception $ex) {
		die($ex->getMessage());
	}
	