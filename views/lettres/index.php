<?php

use app\components\CharStats;
use app\models\TraitementTexte;
use Stringy\Stringy;
use yii\helpers\ArrayHelper;
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

        <h2>Statistiques</h2>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => "Texte nettoyé",
                    'value' => nl2br($model->getSanitizedText()),
                    'format' => 'html',
                ],
                [
                    'label' => "Encodage",
                    'value' => (new Stringy($model->text))->getEncoding(),
                ],
                [
                    'label' => "Caractères",
                    'value' => function (TraitementTexte $model) {
                        $check100 = 0;
                        $out = "<ul>";
                        $charStats = $model->getCharStats();
                        foreach (ArrayHelper::toArray($charStats) as $attr => $value) {
                            $pc = 100 * $value / $charStats->nbChars;
                            $check100 += $attr == 'nbChars' ? 0: $pc;
                            $out .= $attr == 'nbChars' ?
                                sprintf('<li><label>%s</label> : %s</li>', CharStats::attributeLabel($attr), $value) :
                                sprintf('<li><label>%s</label> : %s (%f%%)</li>', CharStats::attributeLabel($attr), $value, $pc);
                        }
                        $out .= "<li><label>Total % : </label>$check100</li>";
                        return $out . "</ul>";
                    },
                    'format' => 'html',
                ],
            ],
        ]) ?>

    <?php endif ?>

</div>
