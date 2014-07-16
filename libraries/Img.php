<?php
class Img
{
	public function is_valid_url($url = null)
	{
		if(!filter_var($url,FILTER_VALIDATE_URL)){
			return false;
		}
		return true;
	}
	public function get_image_urls($url = null)
	{
		$image_urls = array();
		if($url)
		{
			$data = $this->request($url);
			if(!empty($data))
			{
				$dom = new domDocument;
				$image_urls = array();
				libxml_use_internal_errors(true);
				$dom->loadHTML($data);
				$dom->preserveWhiteSpace = false;
				$images = $dom->getElementsByTagName('img');
				if(!empty($images) && is_object($images))
				{
					foreach ($images as $image) {
					  $image_urls[] = $image->getAttribute('src');
					}
					
				}
			}
		}
		return $image_urls;
	}
	public function request($url = null)
	{
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}
