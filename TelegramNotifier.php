<?php

	class TelegramNotifier {
		
		private $_apiUrl;
		private $_userId;
		
		public function __construct($apiKey, $userId) {
			$this->_apiUrl = 'https://api.telegram.org/bot'.$apiKey.'/';
			$this->_userId = $userId;
		}

		public function getChatId() 
		{
			$result = $this->webRequest($this->_apiUrl.'getUpdates?offset=0');
			$result = json_decode($result, true);
			if (!$result) throw new Exception('JSON decode error');

			$chatId = 0;
			foreach($result['result'] as $res) {
				if ($res['message']['from']['id']==$this->_userId) {
					$chatId = $res['message']['chat']['id'];
					break;
				}
			}			
			
			if (!$chatId) throw new Exception('Can\'t resolve Chat Id');
			
			return $chatId;
		}
		
		// https://core.telegram.org/bots/api#sendmessage
		public function sendMessage($message) 
		{
			$chat_id = $this->getChatId();
			return $this->webRequest($this->_apiUrl.'sendMessage', [
				'chat_id' => $chat_id, 
				'text' => $message,
				'disable_web_page_preview' => 1,
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