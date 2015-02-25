Yii2 uploadable file
==================================
Yii2 extension for upload files

[![Latest Stable Version](https://poser.pugx.org/maxmirazh33/yii2-uploadable-file/v/stable.svg)](https://packagist.org/packages/maxmirazh33/yii2-uploadable-file)
[![Total Downloads](https://poser.pugx.org/maxmirazh33/yii2-uploadable-file/downloads.svg)](https://packagist.org/packages/maxmirazh33/yii2-uploadable-file)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/maxmirazh33/yii2-uploadable-file/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/maxmirazh33/yii2-uploadable-file/?branch=master)
[![Code Climate](https://codeclimate.com/github/maxmirazh33/yii2-uploadable-file/badges/gpa.svg)](https://codeclimate.com/github/maxmirazh33/yii2-uploadable-file)
[![Latest Unstable Version](https://poser.pugx.org/maxmirazh33/yii2-uploadable-file/v/unstable.svg)](https://packagist.org/packages/maxmirazh33/yii2-uploadable-file)
[![License](https://poser.pugx.org/maxmirazh33/yii2-uploadable-file/license.svg)](https://packagist.org/packages/maxmirazh33/yii2-uploadable-file)
[![Dependency Status](https://www.versioneye.com/user/projects/54d4badf3ca08473b40003b0/badge.svg?style=flat)](https://www.versioneye.com/user/projects/54d4badf3ca08473b40003b0)
[![Build Status](https://scrutinizer-ci.com/g/maxmirazh33/yii2-uploadable-file/badges/build.png?b=master)](https://scrutinizer-ci.com/g/maxmirazh33/yii2-uploadable-file/build-status/master)

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

If you use Advanced App Template and this behavior attached in backend model, than in frontend model add trait
```php
use \maxmirazh33\file\GetFileUrlTrait
```
and use getFileUrl() method for frontend model too.
