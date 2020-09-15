<?php

use app\models\TraitementTexte;
use Stringy\Stringy;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model TraitementTexte */

?>

<div class="lettres-index">

    <h1>Lettres et le temps</h1>

    <div class="hint-block">Ne saissisez que du texte simple</div>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 10]) ?>

    <div class="form-group">
        <?= Html::submitButton("Traiter le texte", ['class' => 'btn btn-success']) ?>
    </div>

    <?php $form = ActiveForm::end() ?>

    <?php if ($model->getSanitizedText()) : ?>

        <h2>Texte nettoyé</h2>

        <div class="row">
            <div class="col-sm-12 bloc-texte">
                <?= nl2br($model->getSanitizedText()) ?>
            </div>
        </div>

        <h2>Statistiques</h2>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => "Encodage",
                    'value' => (new Stringy($model->text))->getEncoding(),
                ],
            ],
        ]) ?>

    <?php endif ?>

</div>
