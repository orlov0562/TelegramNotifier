<?php

	include 'TelegramNotifier.php';
	
	$apiKey = '---insert-your-API-Key-here---';
	$userId = '---insert-your-user-id-here---';
	
	try {
		(new TelegramNotifier($apiKey, $userId))->sendMessage('Hello world');
	} catch (Exception $ex) {
		die($ex->getMessage());
	}
	