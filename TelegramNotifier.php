<?php

	class TelegramNotifier {
		
		private $_apiUrl;
		
		public function __construct($apiKey) {
			$this->_apiUrl = 'https://api.telegram.org/bot'.$apiKey.'/';
		}

		public function getChatId() 
		{
			$result = $this->webRequest($this->_apiUrl.'getUpdates?offset=0');
			$result = json_decode($result, true);
			if (!$result) throw new Exception('JSON decode error');
			if (empty($result['result'][0]['message']['chat']['id'])) throw new Exception('Can\'t resolve Chat Id');
			return $result['result'][0]['message']['chat']['id'];
		}
		
		public function sendMessage($message) 
		{
			$chat_id = $this->getChatId();
			return $this->webRequest($this->_apiUrl.'sendMessage', [
				'chat_id' => $chat_id, 
				'text' => $message,
			]);			
		}

		private function webRequest($url, array $post=[]) 
		{
			$ch = curl_init($url);
			
			$options = [
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYPEER => false,
			];
			
			if ($post)  $options += [
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => $post
			];
			
			curl_setopt_array($ch, $options);
			$result = curl_exec($ch);
			if ($errMsg = curl_error($ch)) throw new Exception($errMsg);
			curl_close($ch);
			return $result;		
		}
	}