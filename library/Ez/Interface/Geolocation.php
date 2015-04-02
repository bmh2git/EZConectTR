<?php
class Ez_Interface_Geolocation
{
	public static function getCoords($address)
	{
		try
		{
			$config = array(
					'adapter'      => 'Zend_Http_Client_Adapter_Socket',
					'ssltransport' => 'tls'
			);
			
			$client = new Zend_Http_Client('http://maps.google.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false', $config);
			
			$response = $client->request();
			$response = $response->getBody();
			
			$out = Zend_Json::decode($response);
			if(isset($out['results'][0]['geometry']['location']))
			{
				return array(
					'errorCode' => 0,
					'results' => $out['results'][0]['geometry']['location']
				);
			}
			
		}
		catch (Exception $e)
		{
			return array('errorCode' => 1, 'message' => $e->getMessage());
		}
	}
	
	public static function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2, $unit = 'Mi') {
		$theta = $longitude1 - $longitude2;
		$distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
		$distance = acos($distance);
		$distance = rad2deg($distance);
		$distance = $distance * 60 * 1.1515;
		switch($unit) {
			case 'Mi': break; case 'Km' : $distance = $distance * 1.609344;
		}
		return (round($distance,2));
	}
}