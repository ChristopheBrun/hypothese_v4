<?php

namespace app\modules\user\controllers;

use app\modules\ia\lib\TreeNode;
use app\modules\user\models\AuthItem;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;


/**
 * Class RoleController
 * @package app\modules\user\controllers
 */
class RoleController extends Controller
{

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true, 'roles' => ['managePrivileges'],
                    ]
                ]
            ],
        ];
    }

    /**
     * Affiche & traite le formulaire de connexion
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $tree = new TreeNode();
        /** @var AuthItem $item */
        foreach (Yii::createObject('user/AuthItem')->find()->rootRoles()->all() as $item) {
            $tree->addChild($item->getTreeNode());
        }

        $keysMap = ['id' => 'text', 'children' => 'nodes', 'data' => null];
        return $this->render('index', [
            'data' => $tree->asArray($keysMap)['nodes'],
        ]);
    }

}