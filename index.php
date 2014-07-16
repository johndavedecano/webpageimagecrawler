<?php 
include 'vendor/autoload.php';
include 'libraries/autoload.php';
$app = new \Slim\Slim();

// Index Page
$app->get('/',function() use ($app){
	$app->render('index.php');
});

// GET IMAGE URLS
$app->post('/url',function() use ($app){

	$response = array();
	if(!$app->request->isAjax())
	{
		$app->halt(500, "Invalid Request");
		exit;
	}

	$url = $app->request->post('url');
	$img = new Img();
	if($img->is_valid_url($url))
	{
		$image_urls = $img->get_image_urls($url);
		if(is_null($image_urls) || empty($image_urls))
		{
			$response['success'] = false;
			$response['content'] = 'No image urls found';
		}else{
			$response['success'] = true;
			$response['content'] = $image_urls;
		}
	}else{
		$response['success'] = false;
		$response['content'] = 'Invalid URL';
	}
	header("Content-type:text/json");
	echo json_encode($response);
});

// DOWNLOAD IMAGE
$app->post('/download',function() use ($app){

	if(!$app->request->isAjax())
	{
		$app->halt(500, "not valid");
		exit;
	}
	$response = array();
	$url = $app->request->post('url');
	$img = new Img();
	$data = $img->request($url);
	$file = basename($url);

	if(preg_match('/\.(jpg|jpeg|png|gif)(?:[\?\#].*)?$/i', $file, $matches))
	{
		if(!file_exists(dirname(__FILE__).'/images'))
		{
			@mkdir(dirname(__FILE__).'/images');
		}
		$path = 'images/'.$file;
		@file_put_contents($path,$data);
		$response['success'] = true;
		$response['content'] = $path;
	}else{
		$response['success'] = false;
		$response['content'] = 'Invalid Image';
	}
	header("Content-type:text/json");
	echo json_encode($response);

});

$app->run();