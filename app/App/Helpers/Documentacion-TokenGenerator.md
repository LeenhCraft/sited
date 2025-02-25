<?php
// Incluir la clase TokenGenerator
require_once 'TokenGenerator.php';

// Ejemplo 1: Generar un token simple
$tokenGenerator = new TokenGenerator();
$token = $tokenGenerator->generate();
echo "Token simple: " . $token . PHP_EOL;

// Ejemplo 2: Personalizar la longitud y añadir un prefijo
$tokenGenerator = new TokenGenerator(16, null, 'API_');
$token = $tokenGenerator->generate();
echo "Token personalizado: " . $token . PHP_EOL;

// Ejemplo 3: Utilizar diferentes conjuntos de caracteres
$tokenGenerator = new TokenGenerator(12);
echo "Token hexadecimal: " . $tokenGenerator->useHexadecimal()->generate() . PHP_EOL;
echo "Token con caracteres especiales: " . $tokenGenerator->useSpecialChars()->generate() . PHP_EOL;

// Ejemplo 4: Generar un token como hash
$tokenGenerator = new TokenGenerator();
$hashToken = $tokenGenerator->generateHash('usuario123');
echo "Token hash: " . $hashToken . PHP_EOL;

// Ejemplo 5: Generar token con tiempo de caducidad
$tokenGenerator = new TokenGenerator(32, 3600); // Expira en 1 hora
$tokenData = $tokenGenerator->generateWithExpiry(['user_id' => 123]);
echo "Token con caducidad: " . $tokenData['token'] . PHP_EOL;
echo "Expira en: " . date('Y-m-d H:i:s', $tokenData['expires_at']) . PHP_EOL;

// Verificar si el token ha expirado
if (TokenGenerator::isValid($tokenData['expires_at'])) {
    echo "El token sigue siendo válido" . PHP_EOL;
} else {
    echo "El token ha expirado" . PHP_EOL;
}

// Ejemplo 6: Generar y verificar un JWT
$tokenGenerator = new TokenGenerator();
$secret = 'mi_clave_secreta_muy_segura';
$jwt = $tokenGenerator->generateJWT(['user_id' => 123, 'role' => 'admin'], $secret);
echo "JWT: " . $jwt . PHP_EOL;

// Verificar el JWT
$payload = TokenGenerator::verifyJWT($jwt, $secret);
if ($payload) {
    echo "JWT verificado correctamente. Payload: " . json_encode($payload) . PHP_EOL;
} else {
    echo "JWT inválido o expirado" . PHP_EOL;
}

// Ejemplo 7: Uso con encadenamiento de métodos (method chaining)
$token = (new TokenGenerator())
    ->setLength(20)
    ->setPrefix('SECURE_')
    ->useAlphanumeric()
    ->generate();
echo "Token con encadenamiento: " . $token . PHP_EOL;
