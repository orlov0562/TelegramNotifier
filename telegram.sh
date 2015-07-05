#!/bin/bash

APIKEY="---insert-your-API-Key-here---"
URL="https://api.telegram.org/bot$APIKEY"

TEXT="Hello world"

CHATID=$(curl -s "$URL/getUpdates?offset=0" | php -r '$r=json_decode(file_get_contents("php://stdin"), true); echo isset($r["result"][0]["message"]["chat"]["id"])?$r["result"][0]["message"]["chat"]["id"] : "";')
curl -s -d "chat_id=$CHATID&disable_web_page_preview=1&text=$TEXT" "$URL/sendMessage" >/dev/null 2>&1

