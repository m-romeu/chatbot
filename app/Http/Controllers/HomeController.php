<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(){
        return view('home');
    }
	
	public function question(Request $request){
		
		$input = $request->all();
		$auth = json_decode($this->getToken());
		$token = $auth->accessToken;
		$url = $auth->apis->chatbot;
		$session = json_decode($this->getSessionToken($url, $token));
	    $sessionToken = $session->sessionToken;
		$response = json_decode($this->getResponse($token, $sessionToken));
		
		$answers = $response->answers;
		$array_answers = $answers[0]->messageList;
		if( count($array_answers) >0 ){			
			return view('ajax-request',['response' => $array_answers[0]]);
		}
	}
	
	private function getToken(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.inbenta.io/v1/auth",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => array('secret' => 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJwcm9qZWN0IjoieW9kYV9jaGF0Ym90X2VuIn0.anf_eerFhoNq6J8b36_qbD4VqngX79-yyBKWih_eA1-HyaMe2skiJXkRNpyWxpjmpySYWzPGncwvlwz5ZRE7eg'),
		  CURLOPT_HTTPHEADER => array(
			"x-inbenta-key: nyUl7wzXoKtgoHnd2fB0uRrAv0dDyLC+b4Y6xngpJDY="
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}
	
	private function getSessionToken($url, $token){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url."/v1/conversation",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => array('Authorization' => 'Bearer '.$token,'\'x-inbenta-key' => 'nyUl7wzXoKtgoHnd2fB0uRrAv0dDyLC+b4Y6xngpJDY='),
		  CURLOPT_HTTPHEADER => array(
			"x-inbenta-key: nyUl7wzXoKtgoHnd2fB0uRrAv0dDyLC+b4Y6xngpJDY=",
			"Authorization: Bearer ".$token
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}
	
	private function getResponse($token, $sessionToken,$question =""){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api-gce3.inbenta.io/prod/chatbot/v1/conversation/message",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => array('Authorization' => 'Bearer '.$token,'\'x-inbenta-key' => 'nyUl7wzXoKtgoHnd2fB0uRrAv0dDyLC+b4Y6xngpJDY=','message' => 'hello'),
		  CURLOPT_HTTPHEADER => array(
			"x-inbenta-key: nyUl7wzXoKtgoHnd2fB0uRrAv0dDyLC+b4Y6xngpJDY=",
			"x-inbenta-session: Bearer ".$sessionToken,
			"Authorization: Bearer ".$token
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
		
	}
}
