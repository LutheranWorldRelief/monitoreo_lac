<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Json;

class UToken extends Component {

    public static function GenerarToken($id, $data = []) {
        $time = new \DateTime('1 days');
        $contenido = [
            'header' => ["alg" => "HS256", "typ" => "JWT"],
            'payload' => [
                'iss' => Yii::$app->params['UrlApp'], //Creador (iss) – Identifica a quien creo el JWT
                'sub' => '', //Razón (sub) – Identifica la razón del JWT, se puede usar para limitar su uso a ciertos casos.
                'aud' => 'web, android', //Audiencia (aud) – Identifica quien se supone que va a recibir el JWT. Un ejemplo puede ser web, android o ios. Quien use un JWT con este campo debe además de usar el JWT enviar el valor definido en esta propiedad de alguna otra forma.
                'exp' => $time->getTimestamp(), // Tiempo de expiración (exp) – Una fecha que sirva para verificar si el JWT esta vencido y obligar al usuario a volver a autenticarse.
                'nbf' => time(), //No antes (nbf) – Indica desde que momento se va a empezar a aceptar un JWT.
                'iat' => time(), //Creado (iat) – Indica cuando fue creado el JWT.
                'id' => $id . time(), //ID (jti) – Un identifador único para cada JWT.
                'data' => $data
            ],
            'random' => $id . rand(1, 9000)
        ];

        $unsignedToken = self::Encodificar($contenido);
//        $key = \Yii::$app->params['secretToken'];
//        $signature = hash_hmac("sha256", $unsignedToken, $key);
//        return $unsignedToken . '.' . $signature;
        return $unsignedToken;
    }

    public static function Encodificar($data) {
        return base64_encode(Json::encode($data));
    }

    public static function Decodificar($data) {
        return Json::decode(base64_decode($data));
    }

    public static function getExpiracion($token, $es_cadena = true) {
        if ($es_cadena)
            $token = self::Decodificar($token);
        if (isset($token['payload']['exp']))
            return $token['payload']['exp'];
        return null;
    }

    public static function ValidaToken($token) {
        if (self::getExpiracion($token) > time())
            return true;
        return false;
    }

}
