<?php

use app\modules\hlib\helpers\AssetsHelper;
use yii\widgets\DetailView;

/** @var bool|int $result */
/** @var array $matches Résultats pour un appel à preg_match_all() */
/** @var array $parentheses */

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
            'label' => "Chaines satisfaisant la regex",
            'format' => 'html',
            'value' => function () use ($matches) {
                $out = "<ul>";
                for ($idx = 0, $end = count($matches[0]); $idx < $end; ++$idx) {
                    $out .= "<li>" . $matches[0][$idx] . "</li>";
                }
                $out .= "</ul>";
                return $out;
            },
        ];
    }

    for ($i = 1; $i < count($matches); ++$i) {
        $detailViewAttributes[] = [
            'label' => "Parenthèses capturante n°$i : <span class='regex-str'>{$parentheses[$i - 1]}</spanclass>",
            'format' => 'html',
            'value' => function () use ($matches, $i) {
                $out = "<ul>";
                for ($idx = 0, $end = count($matches[$i]); $idx < $end; ++$idx) {
                    $out .= "<li>" . $matches[$i][$idx] . "</li>";
                }
                $out .= "</ul>";
                return $out;
            },
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
