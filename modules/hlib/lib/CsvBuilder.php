<?php

namespace app\modules\hlib\lib;

use Exception;
use Yii;
use yii\console\Response;

/**
 * Classe permettant de construire un fichier CSV.
 * Utilisation :
 * - appeler setColumns() pour déclarer les colonnes à placer dans ce CSV
 * - Exemple de code ci-dessous :
 *    ** une fois les en-têtes de colonnes enregistrés dans le Builder, on prépare le builder en appelant begin().
 *    ** pour ajouter des lignes, on commence à construire une ligne en appelant beginLine(), puis on renseigne chaque cellule par un appel à la méthode
 *    set() qui permet de placer dans la colonne $column la valeur associé. Une fois la ligne entièrement remplie, on l'enregistre avec un appel à
 *    la méthode endLine() qui enregistre son contenu dans le buffer interne.
 *    ** une fois toutes les lignes construites, un appel à la méthode end()
 */
//
//[...]
//$builder->begin();
//$builder->setColumns($columnsNames);
//while ($row = $result->fetch_assoc()) {
//    $builder->beginLine();
//    foreach ($columnsNames as $column) {
//        $builder->set($column, $row[$column]);
//    }
//    $builder->endLine();
//}
//$builder->end();
//$builder->saveToFile($outputFile));
//

class CsvBuilder
{
    /**
     * @var ?array string[] Ligne CSV correspondant à une ligne du fichier à construire
     */
    protected ?array $csvLines = null;

    /**
     * @var array Liste des noms des colonnes du CSV. Leur libellé permet d'identifier une colonne et de renseigner la ligne des en-têtes
     */
    protected array $columns = [];

    /**
     * @var string Valeur par défaut pour les champs du tableau associatif
     */
    protected string $defVal;

    /**
     * @var string Séparateur de champs à utiliser dans le CSV
     */
    protected string $fieldSep;

    /**
     * @var string Séparateur de lignes à utiliser dans le CSV
     */
    protected string $eol;

    private bool $ready = false;
    private bool $utf8Encode;

    /**
     * @var ?array Ligne en cours de traitement.
     * NB : une ligne est un tableau associatif [$key => $value] où $key fait partie des noms de colonnes qui ont été déclarés dans setColumns().
     */
    protected ?array $currentLine = null;

    ////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////

    /**
     * Constructeur
     * @param string $fieldSep Séparateur de champs à utiliser pour le CSV
     * @param string $eol Séparateur de lignes à utiliser pour le CSV
     * @param string $defVal Valeur par défaut pour les champs du tableau associatif
     * @param boolean $utfEncode
     */
    public function __construct(string $fieldSep = "\t", string $eol = "\n", string $defVal = "", bool $utfEncode = false)
    {
        $this->defVal = $defVal;
        $this->fieldSep = $fieldSep;
        $this->eol = $eol;
        $this->utf8Encode = $utfEncode;
    }

    /**
     * Renvoie la liste des lignes du CSV. NB : chaque ligne est un tableau associatif [nom_col_1 => valeur_1, nom_col_2 => valeur_2, ...]
     * @return ?array
     */
    public function getLines(): ?array
    {
        return $this->csvLines;
    }

    /**
     * Renvoie true si le CSV a été entièrement construit (ce qui se manifeste dans le code par un appel à la métode end())
     * @return boolean
     */
    public function isReady(): bool
    {
        return $this->ready;
    }

    /**
     * @return string BOM UTF-8 (permet notamment de forcer le bon encodage sous Excel).
     */
    private function bom(): string
    {
        return $this->utf8Encode ? $utf8_with_bom = chr(239) . chr(187) . chr(191) : '';
    }

    /**
     * Enregistre la liste des en-têtes des colonnes du CSV.
     * @param string[] $cols
     * @return CsvBuilder
     */
    public function setColumns(array $cols): CsvBuilder
    {
        $this->columns = $cols;
        return $this;
    }

    /**
     * @param string $fieldSep
     * @return CsvBuilder
     */
    public function setFieldSeparator(string $fieldSep): CsvBuilder
    {
        $this->fieldSep = $fieldSep;
        return $this;
    }

    /**
     * @param $defVal
     * @return CsvBuilder
     */
    public function setDefaultValue(string $defVal): CsvBuilder
    {
        $this->defVal = $defVal;
        return $this;
    }

    /**
     * @param $eol
     * @return CsvBuilder
     */
    public function setEol(string $eol): CsvBuilder
    {
        $this->eol = $eol;
        return $this;
    }

    /**
     * Prépare une nouvelle ligne du CSV
     */
    public function beginLine(): void
    {
        $this->currentLine = [];
        foreach ($this->columns as $key) {
            $this->currentLine[$key] = $this->defVal;
        }
    }

    /**
     * La ligne en cours de traitement est terminée : on l'ajoute au tableau des lignes du CSV.
     */
    public function endLine(): void
    {
        $line = implode($this->fieldSep, $this->currentLine);
        $this->csvLines[] = $this->utf8Encode ? utf8_encode($line) : $line;
        $this->currentLine = null;
    }

    /**
     * Renseigne la colonne $key de la ligne en cours de traitement
     * @param string $key Nom de la colonne à renseigner
     * @param string $value Valeur à renseigner
     * @return bool
     * @throws Exception
     */
    public function set(string $key, string $value = ''): bool
    {
        if (!array_key_exists($key, $this->currentLine)) {
            throw new Exception(__FILE__ . " ligne " . __LINE__ . " : " . __METHOD__ . " : colonne inconnue : $key dans " . print_r($this->currentLine, true));
        }

        $this->currentLine[$key] = $value;
        return true;
    }

    /**
     * Initialisations
     */
    public function begin(): void
    {
        // On enregistre la ligne de header (qui contient les libellés des colonnes)
        $this->beginLine();
        $header = array_keys($this->currentLine);
        $line = implode($this->fieldSep, $header);
        $this->csvLines[] = $this->utf8Encode ? utf8_encode($line) : $line;
        $this->currentLine = null;
    }

    /**
     * Fin des traitements
     */
    public function end(): void
    {
        $this->ready = true;
    }

    /**
     * Sauvegarde le CSV dans le fichier $filename
     * @param string $filename Nom du fichier .csv à fabriquer
     * @throws Exception
     */
    public function saveToFile(string $filename): void
    {
        if (!$this->ready) {
            throw new Exception("Le CSV n'est pas entièrement construit");
        }

        $fh = fopen($filename, 'w');
        if (!$fh) {
            throw new Exception(__FILE__ . " ligne " . __LINE__ . " : " . __METHOD__ . " : Erreur sur fopen($filename, 'w')");
        }

        fwrite($fh, $this->bom());
        foreach ($this->csvLines as $i => $line) {
            if (!fwrite($fh, $line . $this->eol)) {
                fclose($fh);
                throw new Exception(__FILE__ . " ligne " . __LINE__ . " : " . __METHOD__ . " : Erreur sur fwrite($fh, $line . $this->eol) à la ligne $i");
            }
        }

        fclose($fh);
    }

    /**
     * Envoie le CSV dans le navigateur
     * @param string $filename Nom du fichier .csv à fabriquer
     * @return Response|\yii\web\Response
     * @throws Exception
     */
    public function sendToNavigator(string $filename): Response
    {
        if (!$this->ready) {
            throw new Exception("Le CSV n'est pas entièrement construit");
        }

        $output = $this->outputAsString($filename);
//        header('Content-type: text/csv');
//        header("Content-Disposition: attachment; filename=$filename");
//        header("Pragma: no-cache");
        return Yii::$app->response->sendContentAsFile($output, $filename, ['mimeType' => 'text/csv']);
    }

    /**
     * Une fois que la liste des lignes du CSV a été construite, on peut demander la construction du fichier CSV
     * @param bool $usePrefix
     * @return string
     * @throws Exception
     */
    public function outputAsString(bool $usePrefix = true): string
    {
        if (!$this->ready) {
            throw new Exception("Le CSV n'est pas entièrement construit");
        }

        $prefix = $usePrefix ? "\xEF\xBB\xBF" : "";
        $output = $this->bom() . implode($this->eol, $this->csvLines);
        return $prefix . $output;
    }

}

