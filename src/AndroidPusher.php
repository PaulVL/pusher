<?php

namespace PaulVL\Pusher;

class AndroidPusher
{
	public $message = 'This is a pusher message';

	protected $data = [];

	protected $registrationIds = array();

	private $api_access_key = 'GCM_API_ACCESS_KEY';

	public function addRecipent( $registrationId ) {
		if( is_array( $registrationId ) ) {
			$this->registrationIds = array_merge( $this->registrationIds, $registrationId );
		}else {
			array_push( $this->registrationIds, $registrationId );
		}
	}

	public function addData( $keyOrArray, $value = null ) {
		if( is_array( $keyOrArray ) ) {
			$this->data = array_merge( $this->data, $keyOrArray );
		}else {
			$this->data = array_merge( $this->data, [$keyOrArray => $value] );
		}
	}

	public function send() {
		$deb = [];
		
		$data = empty( $this->data ) ? ['message' => $this->message] : $this->data;

		$fields = [
			'registration_ids' => $this->registrationIds,
			'data'	=> $data
		];

		$headers = [
			'Authorization: key=' . $this->api_access_key,
			'Content-Type: application/json'
		];

		$curl = curl_init();
		curl_setopt( $curl,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
		curl_setopt( $curl,CURLOPT_POST, true );
		curl_setopt( $curl,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $curl,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $curl,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($curl );
		curl_close( $curl );
		//$result = json_decode($result);
		$deb['data'] = $data;
		$deb['fields'] = $fields;
		$deb['headers'] = $headers;
		$deb['result'] = $result;
		return $deb;
	}
}