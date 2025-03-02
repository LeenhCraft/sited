<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Logger;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BuscarDocController extends Controller
{
    private Client $client;
    private array $apiConfig;
    private Logger $logger;

    public function __construct()
    {
        parent::__construct();
        $this->client = new Client();
        $this->apiConfig = [
            '1' => [ // DNI
                'url' => $_ENV['API_URL_DNI'],
                'key' => $_ENV['API_KEY']
            ],
            '2' => [ // RUC
                'url' => $_ENV['API_URL_RUC'],
                'key' => $_ENV['API_KEY']
            ]
        ];

        // Instanciamos y configuramos el logger
        $this->logger = new Logger();
        $this->logger
            ->setLogPath(__DIR__ . '/../../../Logs/buscardoc.log')
            ->setIncludeTrace(false)
            ->setIncludeRequest(true);
    }

    public function buscarDni($request, $response, $args)
    {
        $data = $this->sanitize($args);
        $data["tipo"] = "1";
        if (!isset($data['dni']) || empty($data['dni'])) {
            return $this->respondWithError($response, "El DNI es obligatorio.");
        }
        try {
            $apiData = $this->buscarDocumento($data["tipo"], $data["dni"]);
            $this->logger->info("Buscar DNI", [
                'dni' => $data['dni'],
                'response' => $apiData
            ]);
            return $this->respondWithJson($response, $apiData);
        } catch (\Exception $e) {
            $this->logger->error(
                'Error al buscar DNI',
                $e,
                [
                    'params' => $data
                ]
            );
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    public function buscarRuc($request, $response, $args)
    {
        $data = $this->sanitize($args);
        $data["tipo"] = "2";
        if (!isset($data['ruc']) || empty($data['ruc'])) {
            return $this->respondWithError($response, "El RUC es obligatorio.");
        }
        try {
            // Consultar API
            $apiData = $this->buscarDocumento($data["tipo"], $data["ruc"]);
            return $this->respondWithJson($response, $apiData);
        } catch (\Exception $e) {
            return $this->respondWithError($response, $e->getMessage());
        }
    }

    private function buscarDocumento($tipo, $numeroDoc)
    {
        if (empty($numeroDoc)) {
            return [
                'status' => false,
                'message' => 'El número de documento es obligatorio.'
            ];
        }
        try {
            // Consultar API
            $apiData = $this->consultarApi($tipo, $numeroDoc);
            return $apiData;
        } catch (\Exception $e) {
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    }

    private function consultarApi($tipo, $numero)
    {
        if (!isset($this->apiConfig[$tipo])) {
            throw new \Exception("Tipo de documento no válido");
        }

        try {
            $config = $this->apiConfig[$tipo];
            $response = $this->client->request('GET', $config['url'] . $numero, [
                'headers' => [
                    'User-Agent' => 'blog yawar muxus',
                    'sk' => $config['key'],
                    'tk' => $_ENV['API_TOKEN']
                ],
                'timeout' => 30,
                'http_errors' => false,
                'verify' => false
            ]);

            $body = $response->getBody()->getContents();
            return json_decode($body, true);
        } catch (GuzzleException $e) {
            throw new \Exception("Error al consultar la API: " . $e->getMessage());
        }
    }
}
