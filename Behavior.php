<?php

namespace maxmirazh33\file;

use yii\base\InvalidParamException;
use yii\db\ActiveRecord;
use yii\validators\FileValidator;
use yii\web\UploadedFile;
use Yii;

/**
 * Class model behavior for uploadable files
 *
 * Usage in your model:
 * ```
 * ...
 * public function behaviors()
 * {
 *     return [
 *         'uploadFileBehavior' => [
 *              'class' => \maxmirazh33\file\Behavior::className(),
 *              'attributes' => [
 *                  'file' => [
 *                      'allowEmpty' => true,
 *                  ],
 *                  'otherFile',
 *              ],
 *         ],
 *     //other behaviors
 *     ];
 * }
 * ...
 * ```
 */
class Behavior extends \yii\base\Behavior
{
    /**
     * @var array list of attribute as $attributeName => $options. Options:
     *  $savePathAlias @see maxmirazh33\file\Behavior $savePathAlias
     *  $allowEmpty @see maxmirazh33\file\Behavior $allowEmpty
     *  $allowEmptyScenarios @see maxmirazh33\file\Behavior $allowEmptyScenarios
     *  $urlPrefix @see maxmirazh33\file\Behavior $urlPrefix
     *  $validatorOptions @see yii\validators\FileValidator
     */
    public $attributes = [];
    /**
     * @var string. Default @frontend/web/files/className or @app/web/files/className
     */
    public $savePathAlias;
    /**
     * @var bool allow don't attach file for all scenarios
     */
    public $allowEmpty = false;
    /**
     * @var array scenarios, when allow don't attach file
     */
    public $allowEmptyScenarios = ['update'];
    /**
     * @var string part of url for file without hostname. Default '/files/className/'
     */
    public $urlPrefix;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate($event)
    {
        /**
         * @var ActiveRecord $model
         */
        $model = $this->owner;
        $validator = new FileValidator();

        foreach ($this->attributes as $attr => $options) {
            $this->ensureAttributes($attr, $options);
            $validator->attributes = [$attr];
            $attrAllowEmpty = isset($options['allowEmpty']) ? $options['allowEmpty'] : null;
            $attrAllowEmptyScenarios = isset($options['allowEmptyScenarios']) ? $options['allowEmptyScenarios'] : null;
            if (isset($attrAllowEmpty) && isset($attrAllowEmptyScenarios)) {
                $validator->skipOnEmpty = $attrAllowEmpty || in_array($model->scenario, $attrAllowEmptyScenarios);
            } elseif (isset($attrAllowEmpty)) {
                $validator->skipOnEmpty = $attrAllowEmpty;
            } elseif (isset($attrAllowEmptyScenarios)) {
                $validator->skipOnEmpty = in_array($model->scenario, $attrAllowEmptyScenarios);
            } else {
                $validator->skipOnEmpty = $this->allowEmpty || in_array($model->scenario, $this->allowEmptyScenarios);
            }

            if (isset($options['validatorOptions']) && is_array($options['validatorOptions'])) {
                foreach ($options['validatorOptions'] as $name => $value) {
                    if (property_exists('\yii\validators\FileValidator', $name)) {
                        $validator->{$name} = $value;
                    }
                }
            }

            $model->validators[] = $validator;
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($event)
    {
        /**
         * @var ActiveRecord $model
         */
        $model = $this->owner;
        foreach ($this->attributes as $attr => $options) {
            $this->ensureAttributes($attr, $options);
            if ($file = UploadedFile::getInstance($model, $attr)) {
                $this->createDirIfNotExists($attr);
                if (!$model->isNewRecord) {
                    $this->deleteFiles($attr);
                }
                $fileName = uniqid() . '.' . $file->extension;
                $model->{$attr} = $fileName;
                $file->saveAs($this->getSavePath($attr) . DIRECTORY_SEPARATOR . $fileName);
            }
        }
    }

    /**
     * @param $object
     * @return string
     */
    private function getShortClassName($object)
    {
        $object = new \ReflectionClass($object);
        return mb_strtolower($object->getShortName());
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete($event)
    {
        foreach ($this->attributes as $attr => $options) {
            $this->ensureAttributes($attr, $options);
            $this->deleteFiles($attr);
        }
    }

    /**
     * @param string $attr name of attribute
     * @return string url to image
     */
    public function getFileUrl($attr)
    {
        $this->checkAttrExists($attr);
        $prefix = $this->getUrlPrefix($attr);
        return $prefix . $this->owner->{$attr};
    }

    /**
     * @param string $attr name of attribute
     */
    private function createDirIfNotExists($attr)
    {
        $dir = $this->getSavePath($attr);
        if (!@is_dir($dir)) {
            @mkdir($dir);
        }
    }

    /**
     * @param string $attr name of attribute
     * @return bool|string save path
     */
    private function getSavePath($attr)
    {
        if (isset($this->attributes[$attr]['savePathAlias'])) {
            return Yii::getAlias($this->attributes[$attr]['savePathAlias']);
        } elseif (isset($this->savePathAlias)) {
            return Yii::getAlias($this->savePathAlias);
        }

        if (isset(Yii::$aliases['@frontend'])) {
            return Yii::getAlias('@frontend/web/files/' . $this->getShortClassName($this->owner));
        } else {
            return Yii::getAlias('@app/web/files/' . $this->getShortClassName($this->owner));
        }
    }

    /**
     * @param string $attr name of attribute
     * @return string url prefix
     */
    private function getUrlPrefix($attr)
    {
        if (isset($this->attributes[$attr]['urlPrefix'])) {
            return $this->attributes[$attr]['urlPrefix'];
        } elseif (isset($this->urlPrefix)) {
            return $this->urlPrefix;
        } else {
            return '/files/' . $this->getShortClassName($this->owner) . '/';
        }
    }

    /**
     * Delete images
     * @param string $attr name of attribute
     */
    private function deleteFiles($attr)
    {
        $base = $this->getSavePath($attr);
        $file = $base . DIRECTORY_SEPARATOR . $this->owner->{$attr};
        if (@is_file($file)) {
            @unlink($file);
        }
    }

    /**
     * Check, isset attribute or not
     * @param string $attribute name of attribute
     * @throws InvalidParamException
     */
    private function checkAttrExists($attribute)
    {
        foreach ($this->attributes as $attr => $options) {
            $this->ensureAttributes($attr, $options);
            if ($attr == $attribute) {
                return;
            }
        }
        throw new InvalidParamException();
    }

    /**
     * @param $attr
     * @param $options
     */
    public static function ensureAttributes(&$attr, &$options)
    {
        if (!is_array($options)) {
            $attr = $options;
            $options = [];
        }
    }
}
