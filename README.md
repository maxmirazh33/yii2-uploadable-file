Yii2 uploadable file
==================================
Yii2 extension for upload files

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

If your need perfectly file input, then in your view file:
```php
echo $form->field($model, 'file')->widget('maxmirazh33\file\Widget');
```
