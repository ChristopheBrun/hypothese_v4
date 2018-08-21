<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use yii\db\IntegrityException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;

use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
    use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
    use yii\data\ActiveDataProvider;
<?php endif; ?>

use app\modules\hlib\HLib;

/**
* <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
*/
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access_admin' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Renvoie une instance du modèle identifiée par sa clé primaire.
     * Si cette clé ne correspond à aucun objet, une erreur 404 est lancée.
     *
     * @param string $id
     * @return <?= $modelClass ?>
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        <?php
        if (count($pks) === 1) {
            $condition = '$id';
        } else {
            $condition = [];
            foreach ($pks as $pk) {
                $condition[] = "'$pk' => \$$pk";
            }
            $condition = '[' . implode(', ', $condition) . ']';
        }
        ?>
        if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Modèle introuvable : #' . $id);
    }

   /**
    * Affichage de la liste des objets

    * @return mixed
    */
    public function actionIndex()
    {
    <?php if (!empty($generator->searchModelClass)): ?>
        $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', compact('searchModel', 'dataProvider'));
    <?php else: ?>
        $dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass ?>::find(),
        ]);

        return $this->render('index', compact('dataProvider'));
    <?php endif; ?>
    }

   /**
    * Affichage de la page de consultation
    *
    * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
    * @return mixed
    */
    public function actionView(<?= $actionParams ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);
        return $this->render('view', compact('model'));
    }

    /**
     * Affichage & traitement du formulaire de création.
     * En cas d'erreur de validation, le formulaire est ré-affiché.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new <?= $modelClass ?>();
        if(Yii::$app->request->isPost) {
            // Traitement du formulaire
            if (!($model->load(Yii::$app->request->post()) && $model->save())) {
                // On ré-affiche le formulaire avec ses erreurs
                Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'There are errors in your form'));
            }
            else {
                // Retour à la liste ou redirection sur la page d'édition, selon le bouton qui a été cliqué
                Yii::$app->session->setFlash('flash-success', HLib::t('messages', 'Create successful'));
                $requestedRedirection =
                Yii::$app->request->getBodyParam('action') == 'saveAndKeepEditing' ?
                    Url::toRoute(['/MODULE/CONTROLLER/update', 'id' => $model->id]) :
                    Url::to(['/MODULE/CONTROLLER/index', 'page' => 1]);
                return $this->redirect($requestedRedirection);
            }
        }

        // Affichage ou ré-affichage
        return $this->render('create', compact('model'));
    }

    /**
     * Affichage & traitement du formulaire de modification.
     * En cas d'erreur de validation, le formulaire est ré-affiché.
     *
     * @param int $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->request->isPost) {
            // Traitement du formulaire
            if (!($model->load(Yii::$app->request->post()) && $model->save())) {
                // On ré-affiche le formulaire avec ses erreurs
                Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'There are errors in your form'));
            }
            else {
                Yii::$app->session->setFlash('flash-success', HLib::t('messages', 'Update successful'));
                if(Yii::$app->request->getBodyParam('action') == 'saveAndBackToList') {
                    return $this->redirect(Url::to(['/MODULE/CONTROLLER/index', 'page' => 1]));
                }
            }
        }

        // Affichage ou ré-affichage
        return $this->render('update', compact('model'));
    }

    /**
     * Suppression d'un objet
     *
     * @param int $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('flash-success', HLib::t('messages', 'Delete successful'));
        }
        catch(IntegrityException $s) {
            Yii::$app->session->setFlash('flash-warning', HLib::t('messages', 'This object is referenced by another object. Deletion failed'));
        }

        return $this->redirect(Url::toRoute('/MODULE/CONTROLLER/index'));
    }

}
