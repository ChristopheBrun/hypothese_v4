<?php
/**
 *
 */
return [
    //------------------------------------
    // languages
    //------------------------------------
    'GET cms/languages' => 'cms/languages/index',
    'GET cms/languages/view/<id:\d+>' => 'cms/languages/view',
    'GET,POST cms/languages/create' => 'cms/languages/create',
    'GET,POST cms/languages/update/<id:\d+>' => 'cms/languages/update',
    'DELETE cms/language/delete' => 'cms/languages/delete',

    //------------------------------------
    // base-pages
    //------------------------------------
    'GET cms/base-pages/<id:\d+>' => 'cms/base-pages/view',
    'GET,POST cms/base-pages/create' => 'cms/base-pages/create',
    'GET cms/base-pages/decrease-menu-index/<id:\d+>' => 'cms/base-pages/decrease-menu-index',
    'DELETE cms/base-pages/delete' => 'cms/base-pages/delete',
    'GET cms/base-pages/get-form/<id:\d+>' => 'cms/base-pages/get-form',
    'GET cms/base-pages/increase-menu-index/<id:\d+>' => 'cms/base-pages/increase-menu-index',
    'GET cms/base-pages/reset-menu-indexes' => 'cms/base-pages/reset-menu-indexes',
    'GET,POST cms/base-pages/update/<id:\d+>' => 'cms/base-pages/update',

    //------------------------------------
    // web-pages
    //------------------------------------
    'GET cms/web-pages' => 'cms/web-pages/index',
    'GET cms/web-pages/<id:\d+>' => 'cms/web-pages/view',
    'GET,POST cms/web-pages/create' => 'cms/web-pages/create',
    'GET,POST cms/web-pages/update/<id:\d+>' => 'cms/web-pages/update',
    'DELETE cms/web-pages/delete' => 'cms/web-pages/delete',

    //------------------------------------
    // base-texts
    //------------------------------------
    'GET cms/base-texts' => 'cms/base-texts/index',
    'GET cms/base-texts/<id:\d+>' => 'cms/base-texts/view',
    'GET,POST cms/base-texts/create' => 'cms/base-texts/create',
    'GET,POST cms/base-texts/update/<id:\d+>' => 'cms/base-texts/update',
    'GET cms/base-texts/get-form/<id:\d+>' => 'cms/base-texts/get-form',
    'DELETE cms/base-texts/delete' => 'cms/base-texts/delete',

    //------------------------------------
    // web-texts
    //------------------------------------
    'GET cms/web-texts' => 'cms/web-texts/index',
    'GET cms/web-texts/<id:\d+>' => 'cms/web-texts/view',
    'GET,POST cms/web-texts/create' => 'cms/web-texts/create',
    'GET,POST cms/web-texts/update/<id:\d+>' => 'cms/web-texts/update',
    'DELETE cms/web-texts/delete' => 'cms/web-texts/delete',

    //------------------------------------
    // base-news
    //------------------------------------
    'GET cms/base-news' => 'cms/base-news/index',
    'GET cms/base-news/<id:\d+>' => 'cms/base-news/view',
    'GET,POST cms/base-news/create' => 'cms/base-news/create',
    'GET cms/base-news/get-form/<id:\d+>' => 'cms/base-news/get-form',
    'GET,POST cms/base-news/update/<id:\d+>' => 'cms/base-news/update',
    'DELETE cms/base-news/delete' => 'cms/base-news/delete',

    //------------------------------------
    // web-news
    //------------------------------------
    'GET cms/web-news' => 'cms/web-news/index',
    'GET cms/web-news/<id:\d+>' => 'cms/web-news/view',
    'GET,POST cms/web-news/create' => 'cms/web-news/create',
    'GET,POST cms/web-news/update/<id:\d+>' => 'cms/web-news/update',
    'DELETE cms/web-news/delete' => 'cms/web-news/delete',

    // frontend
    'GET <slug:[-a-z0-9]+>/actualite/<id:\d+>' => 'cms/web-news/show',
    ['verb' => 'GET', 'pattern' => 'actualites/<page:\d+>', 'defaults' => ['page' => 1], 'route' => 'cms/web-news/display-search-results'],
    'GET,POST actualites/rechercher' => 'cms/web-news/post-search',
    'GET cms/web-news/display-search-results-sort/<orderBy:[-\w_]+>' => 'cms/web-news/display-search-results-sort',

    //------------------------------------
    //  base-tags
    //------------------------------------
    'GET cms/base-tags/<id:\d+>' => 'cms/base-tags/view',
    'GET,POST cms/base-tags/create' => 'cms/base-tags/create',
    'GET,POST cms/base-tags/update/<id:\d+>' => 'cms/base-tags/update',
    'GET cms/base-tags/get-form/<id:\d+>' => 'cms/base-tags/get-form',
    'DELETE cms/base-tags/delete' => 'cms/base-tags/delete',

    //------------------------------------
    // web-tags
    //------------------------------------
    'GET cms/web-tags' => 'cms/web-tags/index',
    'GET cms/web-tags/<id:\d+>' => 'cms/web-tags/view',
    'GET,POST cms/web-tags/create' => 'cms/web-tags/create',
    'GET,POST cms/web-tags/update/<id:\d+>' => 'cms/web-tags/update',
    'DELETE cms/web-tags/delete' => 'cms/web-tags/delete',
];