<?php
namespace maxmirazh33\file;

use yii\base\InvalidConfigException;
use yii\widgets\InputWidget;
use Yii;

/**
 * Class for uploadable file widget
 *
 * Usage:
 * ```
 * ...
 * echo $form->field($model, 'file')->widget('maxmirazh33\file\Widget');
 * ...
 * ```
 */
class Widget extends InputWidget
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!$this->hasModel() && $this->name === null) {
            throw new InvalidConfigException("'model' and 'attribute' properties must be specified.");
        }
        parent::init();

        $this->registerTranslations();
    }

    /**
     * Register widget translations.
     */
    public function registerTranslations()
    {
        if (!isset(Yii::$app->i18n->translations['maxmirazh33/file']) && !isset(Yii::$app->i18n->translations['maxmirazh33/*'])) {
            Yii::$app->i18n->translations['maxmirazh33/file'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@maxmirazh33/file/messages',
                'fileMap' => [
                    'maxmirazh33/file' => 'file.php'
                ],
                'forceTranslation' => true
            ];
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render(
            'view',
            [
                'selector' => $this->getSelector(),
                'model' => $this->model,
                'attribute' => $this->attribute,
            ]
        );
    }

    /**
     * @return string Widget selector
     */
    public function getSelector()
    {
        $object = new \ReflectionClass($this->model);
        return mb_strtolower($object->getShortName()) . '-' . $this->attribute;
    }
}
