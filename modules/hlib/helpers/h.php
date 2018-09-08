<?php

namespace app\modules\hlib\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 *
 */
class h
{
    /**
     * Renvoie une ligne formatée pour les logs.
     * Le paramètre $data accepte plusieurs types. Si c'est un scalaire, la valeur sera rendue telle quelle. Sinon, il sera formatté
     * en fonction de sa classe. Si aucun format n'est explicitement prévu, il sera rendu avec le VarDumper.
     *
     * @param mixed  $data
     * @param string $file
     * @param string $line
     * @param string $method
     * @param string $errNum
     * @return string
     */
    public static function _($data, $file = "", $line = "", $method = "", $errNum = "")
    {
        $out = PHP_EOL;
        if ($errNum) {
            $out .= "-- Erreur #$errNum -- ";
        }
        if ($file) {
            $out .= "[$file]";
        }
        if ($method) {
            $out .= "[$method]";
        }
        if ($line) {
            $out .= "($line)";
        }

        $out .= PHP_EOL;

        if (is_scalar($data)) {
            // Un scalaire est affiché directement
            $out .= $data;
        }
        elseif (is_array($data)) {
            if (count($data) && array_key_exists(0, $data) && is_a($data[0], '\yii\db\ActiveRecord')) {
                // Tableau de ActiveRecord : on affiche explicitement les attributs
                $tmp = array();
                foreach ($data as $it) {
                    /** @var \yii\db\ActiveRecord $it */
                    $tmp[] = $it->getAttributes();
                }
                $out .= print_r($tmp, true);
            }
            else {
                // Tableau normal
                $out .= print_r($data, true);
            }
        }
        elseif (is_a($data, '\yii\db\Query')) {
            /** @var \yii\db\ActiveQuery $data */
            /** @noinspection PhpUndefinedFieldInspection */
            $out .= $data->sql;
        }
        elseif (is_a($data, '\yii\db\Command')) {
            /** @var \yii\db\Command $data */
            /** @noinspection PhpUndefinedMethodInspection */
            $out .= $data->getText();
        }
        elseif (is_a($data, '\Exception')) {
            /** @noinspection PhpUndefinedMethodInspection */
            $out .= $data->getMessage();
        }
        elseif (is_a($data, '\DOMDocument')) {
            /** @noinspection PhpUndefinedMethodInspection */
            $out .= $data->saveXML();
        }
        else {
            // Dans le doute...
            $out = "**** Classe inconnue : " . get_class($data) . PHP_EOL;
            $out .= VarDumper::dumpAsString($data);
        }

        return $out;
    }

//    /**
//     * @return bool
//     */
//    public static function enableCaptcha()
//    {
//        return CCaptcha::checkRequirements() && Yii::app()->params['site']['enableCaptcha'];
//    }

//    /**
//     * Normalise une chaine en ne conservant que des caractères alphanumériques et des tirets. Si l'extension est renseignée,
//     * elle sera protégée (non normalisée) dans le texte. L'extension peut être indifféremment de la forme '.ext' ou 'ext'
//     *
//     * @param string $text
//     * @param string $extension
//     * @return string
//     */
//    public static function slugify($text, $extension = "")
//    {
//        // Protection de l'extension (pour les noms de fichiers complets)
//        $extension = trim($extension);
//        if ($extension != "" && strpos($text, $extension) === false) {
//            $extension = "";
//        }
//
//        if ($extension != "") {
//            if ($extension[0] != '.') {
//                /**
//                 *  L'extension doit commencer par un .
//                 */
//                $extension = ".$extension";
//            }
//
//            // On la supprime du texte à traiter pour qu'elle ne soit pas transformée.
//            // On la replacera en fin de texte avant de quitter la méthode
//            $text = str_replace($extension, "", $text);
//        }
//
//        $text = Inflector::urlize($text);
//        return "$text$extension";
//    }

    /**
     * Source : http://php.developpez.com/telecharger/detail/id/1410/Connaitre-l-ip-reelle-du-visiteur
     *
     * @return string
     */
    public static function findVisitorIP()
    {
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            }
            elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $realip = $_SERVER["HTTP_CLIENT_IP"];
            }
            else {
                $realip = $_SERVER["REMOTE_ADDR"];
            }
        }
        else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            }
            elseif (getenv('HTTP_CLIENT_IP')) {
                $realip = getenv('HTTP_CLIENT_IP');
            }
            else {
                $realip = getenv('REMOTE_ADDR');
            }
        }

        return $realip;
    }

    /**
     * Encapsule l'appel à la fonction PHP get_browser() pour éviter les erreurs en mode console
     *
     * @return array
     */
    public static function getBrowser()
    {
        if (!array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
            // Cette variable n'est pas initialisée en mode console
            return array();
        }

        return get_browser(null, true);
    }

    /**
     *
     * @return bool
     */
    public static function isOSWindows()
    {
        $info = php_uname('s');
        return preg_match("/win/i", $info);
    }

    /**
     * Génère un mot de passe de $length caractères pris dans la collection $possible
     *
     * @param int    $length
     * @param string $possible Liste des caractères autorisés
     * @return string
     * @deprecated Utiliser les fonction Yii de la classe Security à la place
     */
    public static function generatePassword($length = 8, $possible = '$=@#0123456789abcdefghjkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ')
    {
        $password = '';
        $possible_length = strlen($possible) - 1;
        $char = '';
        while ($length--) {
            $except = substr($password, -$possible_length / 2);
            for ($n = 0; $n < 5; $n++) {
                $char = $possible{mt_rand(0, $possible_length)};
                if (strpos($except, $char) === false) {
                    break;
                }
            };

            $password .= $char;
        }

        return $password;
    }

//    /**
//     * @param DOMNode $node
//     * @return string
//     * @internal requires vendors/xmlTreeDump
//     */
//    public static function xmlTreeDump(DOMNode $node)
//    {
//        $iterator = new DOMRecursiveIterator($node);
//        $decorated = new DOMRecursiveDecoratorStringAsCurrent($iterator);
//        $tree = new RecursiveTreeIterator($decorated);
//        $out = "";
//        /** @noinspection PhpUnusedLocalVariableInspection */
//        foreach ($tree as $key => $value) {
//            $out .= $value . "\n";
//        }
//
//        return $out;
//    }

    /**
     * Renvoie une chaine au format "99@99999999" où le premier chiffre correspond à l'identifiant de l'utilisateur
     * et le second au timestamp UNIX lors de l'appel de la méthode.
     * Cette écriture permet de repérer facilement une erreur pour un utilisateur donné dans les fichiers de log
     *
     * @return string
     */
    public static function errNum()
    {
        $userId = ArrayHelper::getValue(Yii::$app->user, 'id', 0);
        return $userId . "@" . time();
    }

    /**
     * Méthode utilisée pour obtenir un identifiant de classe utilisable dans des balises HTML (par exemple pour attribuer
     * un nom ou un id à des formulaires)
     *
     * @param string $className
     * @return string
     * @internal code repris depuis framework/gii/CCodeModel::class2id()
     */
    public static function class2id($className)
    {
        return trim(strtolower(str_replace('_', '-', preg_replace('/(?<![A-Z])[A-Z]/', '-\0', $className))), '-');
    }

    /**
     * Si on est en mode DEBUG (sur le poste de dev en principe...) les mails de l'application sont envoyés au développeur.
     * Sinon, on expédie le mail à l'adresse prévue, indiquée dans $recipientEmail
     *
     * @param string $recipientEmail
     * @return mixed
     */
    public static function safeRecipientEmail($recipientEmail)
    {
        if (YII_ENV_DEV) {
            $recipientEmail = Yii::$app->params['devEmail'];
        }

        return $recipientEmail;
    }

    /**
     * Traduit une chaîne décrivant la localisation selon la norme ???
     * En effet, la méthdoe setlocale() a un comportement variable selon le système d'exploitation.
     * Windows -> setlocale('fr')
     * autres -> setlocale('fr_FR')
     *
     * @param string $locale Code de localisation xcomple (ex. : fr_FR)
     * @return string
     */
    public static function osLocale($locale)
    {
        if (static::isOSWindows()) {
            return substr($locale, 0, 2);
        }
        else {
            return $locale;
        }
    }

    /**
     * Renvoie le protocole de communication à utiliser dans les urls.
     * En dev, on utilise toujours http car aucun certificat autosigné n'est installé sur le poste de développement
     * En production, on utilise https pour les urls sécurisés enregistrées dans la configuration, et http pour les autres
     *
     * @param bool $useSecuredUrls
     * @return string
     */
    public static function protocol($useSecuredUrls = true)
    {
        if ($useSecuredUrls && !YII_ENV_DEV && ($securedUrls = hArray::getValue(Yii::$app->params, 'securedUrls'))) {
            return 'https';
        }

        return 'http';
    }

    /**
     * Extrait le code langue ISO 639 du code langue fourni
     *
     * @param string $fullI18NCode Code langue sur 5 caractères : fr_FR
     * @return string Code langue ISO 639 sur 2 caractères : fr
     */
    public static function getIso639Code($fullI18NCode = null)
    {
        if (is_null($fullI18NCode)) {
            $fullI18NCode = Yii::$app->language;
        }

        return substr($fullI18NCode, 0, 2);
    }

}