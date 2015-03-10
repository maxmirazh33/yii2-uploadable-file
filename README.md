Yii2 uploadable file
==================================
Yii2 extension for upload files

[![Latest Version](https://img.shields.io/github/release/maxmirazh33/yii2-uploadable-file.svg?style=flat-square)](https://github.com/maxmirazh33/yii2-uploadable-file/releases)
[![Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://github.com/maxmirazh33/yii2-uploadable-file/blob/master/LICENSE.md)
[![Quality Score](https://img.shields.io/scrutinizer/g/maxmirazh33/yii2-uploadable-file.svg?style=flat-square)](https://scrutinizer-ci.com/g/maxmirazh33/yii2-uploadable-file)
[![Code Climate](https://img.shields.io/codeclimate/github/maxmirazh33/yii2-uploadable-file.svg?style=flat-square)](https://codeclimate.com/github/maxmirazh33/yii2-uploadable-file)
[![Total Downloads](https://img.shields.io/packagist/dt/maxmirazh33/yii2-uploadable-file.svg?style=flat-square)](https://packagist.org/packages/maxmirazh33/yii2-uploadable-file)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist maxmirazh33/yii2-uploadable-file "*"
```

or add

```
"maxmirazh33/yii2-uploadable-file": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

In your model:
```php
public function behaviors()
    {
        return [
            [
                'class' => \maxmirazh33\file\Behavior::className(),
                'savePathAlias' => '@web/files/',
                'urlPrefix' => '/files/',
                'attributes' => [
                    'image' => [
                        'savePathAlias' => '@web/images/',
                        'urlPrefix' => '/images/',
                    ],
                    'file',
                ],
            ],
        //other behaviors
        ];
    }
```
Use rules for validate attribute.

If your need perfectly file input, then in your view file:
```php
echo $form->field($model, 'file')->widget('maxmirazh33\file\Widget');
```

After, in your view:
```php
echo Html::a('myCoolFile', $model->getFileUrl('file'));
```

If you use Advanced App Template and this behavior attached in backend model, then in frontend model add trait
```php
use \maxmirazh33\file\GetFileUrlTrait
```
and use getFileUrl() method for frontend model too.
