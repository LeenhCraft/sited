<?php

namespace App\Helpers;

use InvalidArgumentException;

/**
 * Clase TokenGenerator
 * 
 * Una clase robusta para generar tokens seguros con varias opciones
 * de configuración, validación y gestión de caducidad.
 */
class TokenGenerator
{
    /**
     * Longitud predeterminada del token
     */
    private int $length = 32;

    /**
     * Tiempo de caducidad predeterminado en segundos (24 horas)
     */
    private int $expiry = 86400;

    /**
     * Prefijo opcional para el token
     */
    private ?string $prefix = null;

    /**
     * Caracteres permitidos para el token
     */
    private string $charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    /**
     * Constructor de la clase
     * 
     * @param int|null $length Longitud del token
     * @param int|null $expiry Tiempo de caducidad en segundos
     * @param string|null $prefix Prefijo opcional para el token
     */
    public function __construct(?int $length = null, ?int $expiry = null, ?string $prefix = null)
    {
        if ($length !== null) {
            $this->setLength($length);
        }

        if ($expiry !== null) {
            $this->setExpiry($expiry);
        }

        if ($prefix !== null) {
            $this->setPrefix($prefix);
        }
    }

    /**
     * Establece la longitud del token
     * 
     * @param int $length Longitud del token
     * @return self
     * @throws InvalidArgumentException Si la longitud no es válida
     */
    public function setLength(int $length): self
    {
        if ($length < 8) {
            throw new InvalidArgumentException('La longitud del token debe ser al menos 8 caracteres');
        }

        $this->length = $length;
        return $this;
    }

    /**
     * Establece el tiempo de caducidad del token
     * 
     * @param int $seconds Tiempo en segundos
     * @return self
     * @throws InvalidArgumentException Si el tiempo no es válido
     */
    public function setExpiry(int $seconds): self
    {
        if ($seconds < 0) {
            throw new InvalidArgumentException('El tiempo de caducidad no puede ser negativo');
        }

        $this->expiry = $seconds;
        return $this;
    }

    /**
     * Establece un prefijo para el token
     * 
     * @param string|null $prefix Prefijo o null para quitarlo
     * @return self
     */
    public function setPrefix(?string $prefix): self
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * Personaliza los caracteres permitidos para el token
     * 
     * @param string $charset Conjunto de caracteres a utilizar
     * @return self
     * @throws InvalidArgumentException Si el charset está vacío
     */
    public function setCharset(string $charset): self
    {
        if (empty($charset)) {
            throw new InvalidArgumentException('El conjunto de caracteres no puede estar vacío');
        }

        $this->charset = $charset;
        return $this;
    }

    /**
     * Usa solo caracteres alfanuméricos
     * 
     * @return self
     */
    public function useAlphanumeric(): self
    {
        $this->charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        return $this;
    }

    /**
     * Usa solo caracteres hexadecimales
     * 
     * @return self
     */
    public function useHexadecimal(): self
    {
        $this->charset = '0123456789abcdef';
        return $this;
    }

    /**
     * Usa caracteres alfanuméricos y especiales
     * 
     * @return self
     */
    public function useSpecialChars(): self
    {
        $this->charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()-_=+[]{}|;:,.<>?';
        return $this;
    }

    /**
     * Genera un token aleatorio
     * 
     * @return string El token generado
     */
    public function generate(): string
    {
        $token = '';
        $max = strlen($this->charset) - 1;

        // Usar random_int para mayor seguridad criptográfica
        for ($i = 0; $i < $this->length; $i++) {
            $token .= $this->charset[random_int(0, $max)];
        }

        // Aplicar prefijo si existe
        if ($this->prefix !== null) {
            $token = $this->prefix . $token;
        }

        return $token;
    }

    /**
     * Genera un token aleatorio con un hash
     * 
     * @param string $data Datos adicionales para incluir en el hash
     * @return string El token generado como hash
     */
    public function generateHash(string $data = ''): string
    {
        // Combinar datos aleatorios con los datos proporcionados
        $randomBytes = random_bytes($this->length);
        $dataToHash = $randomBytes . $data . microtime(true) . uniqid('', true);

        // Generar hash
        $hash = hash('sha256', $dataToHash);

        // Aplicar prefijo si existe
        if ($this->prefix !== null) {
            $hash = $this->prefix . $hash;
        }

        return $hash;
    }

    /**
     * Genera un token con información de caducidad
     * 
     * @param array $payload Datos adicionales para incluir en el token
     * @return array Array con el token y sus metadatos
     */
    public function generateWithExpiry(array $payload = []): array
    {
        $token = $this->generate();
        $expires = time() + $this->expiry;

        return [
            'token' => $token,
            'expires_at' => $expires,
            'created_at' => time(),
            'payload' => $payload
        ];
    }

    /**
     * Verifica si un token con tiempo de caducidad sigue siendo válido
     * 
     * @param int $expiryTimestamp Timestamp de caducidad del token
     * @return bool True si el token es válido, false si ha caducado
     */
    public static function isValid(int $expiryTimestamp): bool
    {
        return $expiryTimestamp > time();
    }

    /**
     * Genera un JWT simple (JSON Web Token)
     * 
     * Nota: Esta es una implementación básica.
     * Para uso en producción, considere usar bibliotecas específicas de JWT.
     * 
     * @param array $payload Datos a incluir en el token
     * @param string $secret Clave secreta para firmar el token
     * @return string Token JWT
     */
    public function generateJWT(array $payload, string $secret): string
    {
        // Cabecera predeterminada
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        // Añadir tiempo de expiración si no está definido
        if (!isset($payload['exp'])) {
            $payload['exp'] = time() + $this->expiry;
        }

        // Añadir tiempo de creación
        if (!isset($payload['iat'])) {
            $payload['iat'] = time();
        }

        // Codificar cabecera y payload
        $headerEncoded = $this->base64UrlEncode(json_encode($header));
        $payloadEncoded = $this->base64UrlEncode(json_encode($payload));

        // Crear firma
        $signature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $secret, true);
        $signatureEncoded = $this->base64UrlEncode($signature);

        // Construir JWT
        return "$headerEncoded.$payloadEncoded.$signatureEncoded";
    }

    /**
     * Verifica un JWT y devuelve su payload si es válido
     * 
     * @param string $jwt Token JWT a verificar
     * @param string $secret Clave secreta para verificar la firma
     * @return array|false Payload del token si es válido, false si no lo es
     */
    public static function verifyJWT(string $jwt, string $secret)
    {
        // Dividir el token en sus componentes
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            return false;
        }

        [$headerEncoded, $payloadEncoded, $signatureEncoded] = $parts;

        // Recrear la firma para comparar
        $signature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $secret, true);
        $signatureCheck = self::base64UrlEncode($signature);

        // Verificar firma
        if (!hash_equals($signatureEncoded, $signatureCheck)) {
            return false;
        }

        // Decodificar payload
        $payload = json_decode(self::base64UrlDecode($payloadEncoded), true);
        if (!is_array($payload)) {
            return false;
        }

        // Verificar expiración
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return false;
        }

        return $payload;
    }

    /**
     * Codifica datos en Base64Url (compatible con JWT)
     * 
     * @param string $data Datos a codificar
     * @return string Datos codificados
     */
    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Decodifica datos en Base64Url (compatible con JWT)
     * 
     * @param string $data Datos a decodificar
     * @return string Datos decodificados
     */
    private static function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
