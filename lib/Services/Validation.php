<?php

namespace XentePaymentSDK\Services;

require 'vendor/autoload.php';


use GuzzleHttp\Client;

class Validation
{
    
}

// $client = new Client([
//     'base_uri' => 'https://jsonplaceholder.typicode.com',
//     'headers' => [
//         'Content-Type' => 'application/json',
//         'Accept' => 'application/json'
//     ]
// ]);

// $requestData = [
//     "title" => "I am using guzzle",
//     "completed" =>  false
// ];

// $requestOptions = [
//     'json' => $requestData,
// ];

// $response = $client->post('/todos', $requestOptions );

// function success($data){
//     return [
//         'status' 	=> 'success',
//         'data'		=> json_decode($data->getBody()->getContents())
//     ];
// }

// function verify()
// {
//     return success($client->post('/todos', $requestOptions ));
// }


// print_r(verify());







// $client = new Client(["base_uri" => "https://jsonplaceholder.typicode.com"]);
// $options = [
//     'json' => [
//         "title" => "I am using guzzle",
//         "completed" =>  false
//        ]
//    ]; 
// $response = $client->post("/todos", $options);

// echo $response->getBody();


$client = new Client([
    'headers' => [ 'Content-Type' => 'application/json' ]
]);

$response = $client->post('https://jsonplaceholder.typicode.com/todos',
    ['body' => json_encode(
        [
            "title" => "I am using guzzle",
            "completed" =>  false
        ]
    )]
);

echo $response->getStatusCode();

$results = $response->getBody()->getContents();
print_r(json_decode($results));
// print_r(($response->getBody()->getContents()));

// class Validation
// {
//     public function __constructor()
//     {

//     }
// }

// $client = new Client();
// $response = $client->request('POST', 'https://jsonplaceholder.typicode.com/todos', 
// [
//     'json' => [
//         "userId" => 1,
//         "title" => "I am using guzzle",
//         "completed" =>  false
//     ]
// ]
// );

// $headers = $response->getHeaders();
// $body = $response->getBody();
// var_dump($body);
// $response = $client->request('GET', 'https://api.github.com/repos/guzzle/guzzle');

// echo $response->getStatusCode(); # 200
// echo $response->getHeaderLine('content-type'); # 'application/json; charset=utf8'
// print_r($response->getBody()); # '{"id": 1420053, "name": "guzzle", ...}'

# Send an asynchronous request.
// $request = new \GuzzleHttp\Psr7\Request('GET', 'http://httpbin.org');
// $promise = $client->sendAsync($request)->then(function ($response) {
//     echo 'I completed! ' . $response->getBody();
// });

// $promise->wait();