<?php

use app\modules\hlib\helpers\AssetsHelper;
use yii\widgets\DetailView;

/** @var bool|int $result */
/** @var array $matches Résultats pour un appel à preg_match() */

$display = !is_null($result) && $result !== false;

?>

<?php if ($display) : ?>
    <?php $detailViewAttributes = [[
        'label' => "Correspondance",
        'value' => AssetsHelper::getImageTagForBoolean($result),
        'format' => 'html',
    ]];

    if ($result) {
        $detailViewAttributes[] = [
            'label' => "Chaine satisfaisant la regex",
            'value' => $matches[0],
        ];
    }

    for ($i = 1; $i < count($matches); ++$i) {
        $detailViewAttributes[] = [
            'label' => "Parenthèse capturante n°$i",
            'value' => $matches[$i],
        ];
    }
    ?>

    <div class="row">
        <div class="col-sm-12 form-group">
            <?= DetailView::widget([
                'model' => 111,
                'attributes' => $detailViewAttributes,
            ]) ?>
        </div>
    </div>
<?php endif ?>

