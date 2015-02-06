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
            'uploadFileBehavior' => [
                'class' => \maxmirazh33\file\Behavior::className(),
                'attributes' => [
                    'file' => [
                        'allowEmpty' => true,
                    ],
                    'otherFile',
                ],
            ],
            //other behaviors
        ];
    }
```

Don't add rules in your model for used attribute. Validator added automatically.

If your need perfectly file input, then in your view file:
```php
echo $form->field($model, 'file')->widget('maxmirazh33\file\Widget');
```
