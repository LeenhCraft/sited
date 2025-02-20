<?php

namespace App\Core;

/**
 * Clase Logger personalizada para manejo de logs
 */
class Logger
{
    /**
     * @var string Ruta donde se almacenarán los logs
     */
    private string $logPath = __DIR__ . '/../../logs/app.log';

    /**
     * @var array Configuración del logger
     */
    private array $config = [
        'includeTrace' => true,
        'includeRequest' => true,
        'dateFormat' => 'Y-m-d H:i:s'
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ensureLogDirectoryExists();
    }

    /**
     * Obtiene la ruta del archivo de log
     */
    public function getLogPath(): string
    {
        return $this->logPath;
    }

    /**
     * Establece la ruta del archivo de log
     */
    public function setLogPath(string $logPath): self
    {
        $this->logPath = $logPath;
        $this->ensureLogDirectoryExists();
        return $this;
    }

    /**
     * Obtiene si se debe incluir el trace
     */
    public function getIncludeTrace(): bool
    {
        return $this->config['includeTrace'];
    }

    /**
     * Establece si se debe incluir el trace
     */
    public function setIncludeTrace(bool $include): self
    {
        $this->config['includeTrace'] = $include;
        return $this;
    }

    /**
     * Obtiene si se debe incluir el request
     */
    public function getIncludeRequest(): bool
    {
        return $this->config['includeRequest'];
    }

    /**
     * Establece si se debe incluir el request
     */
    public function setIncludeRequest(bool $include): self
    {
        $this->config['includeRequest'] = $include;
        return $this;
    }

    /**
     * Verifica y crea el directorio de logs si no existe
     */
    private function ensureLogDirectoryExists(): void
    {
        $directory = dirname($this->logPath);
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0777, true) && !is_dir($directory)) {
                throw new \RuntimeException(
                    sprintf('No se pudo crear el directorio de logs en: %s', $directory)
                );
            }
            chmod($directory, 0777);
        }

        if (!is_writable($directory)) {
            throw new \RuntimeException(
                sprintf('El directorio de logs no tiene permisos de escritura: %s', $directory)
            );
        }
    }

    /**
     * Registra un error
     */
    public function error(string $message, ?\Throwable $exception = null, array $context = []): void
    {
        $logData = [
            'timestamp' => date($this->config['dateFormat']),
            'level' => 'ERROR',
            'message' => $message
        ];

        if ($this->config['includeTrace'] && $exception) {
            $logData['error'] = [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ];
        }

        if ($this->config['includeRequest'] && isset($_SERVER)) {
            $logData['request'] = [
                'method' => $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN',
                'uri' => $_SERVER['REQUEST_URI'] ?? 'UNKNOWN',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN'
            ];
        }

        $logData = array_merge($logData, $context);
        $this->writeLog($logData);
    }

    /**
     * Registra información
     */
    public function info(string $message, array $context = []): void
    {
        $logData = [
            'timestamp' => date($this->config['dateFormat']),
            'level' => 'INFO',
            'message' => $message
        ];

        if ($this->config['includeRequest'] && isset($_SERVER)) {
            $logData['request'] = [
                'method' => $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN',
                'uri' => $_SERVER['REQUEST_URI'] ?? 'UNKNOWN'
            ];
        }

        $logData = array_merge($logData, $context);
        $this->writeLog($logData);
    }

    /**
     * Escribe en el archivo de log
     */
    private function writeLog(array $data): void
    {
        $logMessage = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL;

        if (error_log($logMessage, 3, $this->logPath) === false) {
            throw new \RuntimeException(
                sprintf('No se pudo escribir en el archivo de log: %s', $this->logPath)
            );
        }
    }
}
