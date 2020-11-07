<?php


namespace app\lib\helpers;

use execut\yii\base\Exception;

/**
 * Class UtilitairesHelper
 * @package app\lib\helpers
 */
class UtilitairesHelper
{
    /**
     * Récupère  le contenu des parenthèses capturantes de $regex
     *
     * @param string $regex
     * @return string[]
     * @throws Exception
     */
    public static function parseParenthesesRegex($regex)
    {
        $parentheses = [];
        $echap = false;
        $idxParenthesesOuvertes = [];

        for ($i = 0, $end = strlen($regex); $i < $end; ++$i) {
            $skipIdx = -1;
            switch ($regex[$i]) {
                case '\\' :
                    // le prochain caractère sera échappé
                    $echap = true;
                    break;
                case ')' :
                    if (!$echap) {
                        // fin d'une parenthèse capturante : la dernière parenthèse encore ouverte doit être fermée
                        array_pop($idxParenthesesOuvertes);
                    }
                    $echap = false;
                    break;
                case '(' :
                    if (!$echap) {
                        // nouvelle parenthèse capturante
                        $parentheses[] = '';
                        $idxParenthesesOuvertes[] = count($parentheses) - 1;
                        // on ajoute ce caractère dans toutes les parenthèses capturantes sauf celle que l'on vient d'ouvrir
                        $skipIdx = count($parentheses) - 1;
                    }
                    $echap = false;
                    break;
                default :
                    // caractère sans traitement particulier
                    $echap = false;
            }

            // On ajoute le caractère courant aux parenthèses capturantes (sauf à la dernière si on vient d'en créer une)
            for ($j = 0, $endj = count($idxParenthesesOuvertes); $j < $endj; ++$j) {
                $idxParentheses = $idxParenthesesOuvertes[$j];
                if ($idxParentheses == $skipIdx) {
                    continue;
                }

                $parentheses[$idxParentheses] .= $regex[$i];
            }
        }

        // Contrôle final : si les parenthèses capturantes sont bien appariées, on devrait avoir
        // $idxParenthesesOuvertes == []
        if ($idxParenthesesOuvertes) {
            throw new Exception("Parenthèses ouvrantes non fermées : " . count($idxParenthesesOuvertes));
        }

        return $parentheses;
    }
}