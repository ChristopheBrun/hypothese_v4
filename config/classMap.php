<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */

/**
 * Définitions du container.
 * Il s'agit ici des définitions partagées entre l'application web et l'application console
 */


return [
    \yii\filters\auth\HttpBasicAuth::class => \app\filters\HttpBasicAuth::class,
];

