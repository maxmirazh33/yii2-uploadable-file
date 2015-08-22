Yii2 uploadable file
==================================
Yii2 расширение для загрузки файлов

[![Latest Version](https://img.shields.io/github/release/maxmirazh33/yii2-uploadable-file.svg?style=flat-square)](https://github.com/maxmirazh33/yii2-uploadable-file/releases)
[![Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://github.com/maxmirazh33/yii2-uploadable-file/blob/master/LICENSE.md)
[![Quality Score](https://img.shields.io/scrutinizer/g/maxmirazh33/yii2-uploadable-file.svg?style=flat-square)](https://scrutinizer-ci.com/g/maxmirazh33/yii2-uploadable-file)
[![Code Climate](https://img.shields.io/codeclimate/github/maxmirazh33/yii2-uploadable-file.svg?style=flat-square)](https://codeclimate.com/github/maxmirazh33/yii2-uploadable-file)
[![Total Downloads](https://img.shields.io/packagist/dt/maxmirazh33/yii2-uploadable-file.svg?style=flat-square)](https://packagist.org/packages/maxmirazh33/yii2-uploadable-file)

Установка
------------

Предпочтительно устанавливать расширение через [composer](http://getcomposer.org/download/).

Выполните в консоли

```
php composer.phar require --prefer-dist maxmirazh33/yii2-uploadable-file "*"
```

или добавьте

```
"maxmirazh33/yii2-uploadable-file": "*"
```

в секцию require вашего `composer.json` файла.


Использование
-----

Когда расширение установлено, его можно использовать таким образом:

В вашей модели:
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
        //другие поведения
        ];
    }
```
Валидацию атрибута необходимо производить как обычно, через метод rules().

Если вам нужен более симпатичное поле для ввода файла, вы можете в своём файле вида с формой использовать:
```php
echo $form->field($model, 'file')->widget('maxmirazh33\file\Widget');
```

Затем, в основном файле вида:
```php
echo Html::a('myCoolFile', $model->getFileUrl('file'));
```

Если вы используете Advanced App Template и это поведение находится в backend модели, то вы можете во frontend модель
добавить трейт
```php
use \maxmirazh33\file\GetFileUrlTrait
```
и использовать метод getFileUrl() и во frontend модели.
