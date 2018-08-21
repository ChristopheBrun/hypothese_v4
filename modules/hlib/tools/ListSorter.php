<?php

namespace app\modules\hlib\tools;

use Yii;
use yii\db\QueryInterface;
use yii\helpers\ArrayHelper;


/**
 * Class ListSorter
 * @package app\modules\hlib\helpers
 */
class ListSorter
{
    /** @var array */
    private static $sortClauseSequence = ['asc', 'desc', ''];

    /**
     * Met à jour La clé de session mémorisant le tri. NB : cette clé contient un tableau au format [nom_colonne => ordre_de_tri]
     * Format de la clé : pp\models\MaClasse.sort
     * Ex. : app\models\CalendarEntry.sort = ['title' => 'asc', 'date' => 'desc']
     *
     * @param string $sortColumn
     * @param string $sessionKey
     */
    public static function updateSession($sortColumn, $sessionKey)
    {
        $sortClauses = Yii::$app->session->get($sessionKey, []);
        $currentSortClause = ArrayHelper::getValue($sortClauses, $sortColumn, '');
        $updatedSortClause = self::getNextSortClause($currentSortClause);

        $sortClauses[$sortColumn] = $updatedSortClause;
        Yii::$app->session->set($sessionKey, $sortClauses);
    }

    /**
     * Calcul du prochain état de la clause $currentSortClause.
     *
     * @param string $currentSortClause
     * @return mixed
     */
    private static function getNextSortClause($currentSortClause)
    {
        foreach (self::$sortClauseSequence as $idx => $clause) {
            if ($clause == $currentSortClause) {
                break;
            }
        }

        if (!isset($idx)) {
            // $currentSortClause n'a pas une valeur reconnue par le tableau. Par défaut on prend ''
            $idx = 2;
        }

        return self::$sortClauseSequence[++$idx % 3];
    }

    /**
     * Ajoute à $query les clauses de tri stockées en session
     *
     * @param QueryInterface $query
     * @param string         $sessionKey La clé de session, en principe au format : app\models\MaClasse.sort
     * @param string         $defaultSortClause Paramètres par défaut pour ORDER BY. N'est exploité que si $sessionKey ne pointe sur rien.
     * @return QueryInterface
     */
    public static function updateQuery(QueryInterface $query, $sessionKey, $defaultSortClause = '')
    {
        // La clé de session contient un tableau au format : nom_colonne => ordre_de_tri
        $sortClauses = Yii::$app->session->get($sessionKey, []);
        if (!$sortClauses && $defaultSortClause) {
            $query->addOrderBy($defaultSortClause);
            return $query;
        }

        foreach ($sortClauses as $field => $clause) {
            if ($clause == 'asc' || $clause == 'desc') {
                $query->addOrderBy($field . ' ' . $clause);
            }
        }

        return $query;
    }

}
