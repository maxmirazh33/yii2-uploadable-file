<?php

namespace maxmirazh33\file;

use yii\base\Exception;
use yii\base\InvalidArgumentException;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use Yii;
use yii\helpers\FileHelper;

/**
 * Class model behavior for uploadable files
 *
 * Usage in your model:
 * ```
 * ...
 * public function behaviors()
 * {
 *     return [
 *         [
 *              'class' => \maxmirazh33\file\Behavior::className(),
 *              'savePathAlias' => '@web/files/',
 *              'urlPrefix' => '/files/',
 *              'attributes' => [
 *                  'image' => [
 *                      'savePathAlias' => '@web/images/',
 *                      'urlPrefix' => '/images/',
 *                  ],
 *                  'file',
 *              ],
 *         ],
 *     //other behaviors
 *     ];
 * }
 * ...
 * ```
 * @property ActiveRecord $owner
 */
class Behavior extends \yii\base\Behavior
{
    /**
     * @var array list of attribute as attributeName => options. Options:
     *  $savePathAlias @see \maxmirazh33\file\Behavior::$savePathAlias
     *  $urlPrefix @see \maxmirazh33\file\Behavior::$urlPrefix
     */
    public $attributes = [];

    /**
     * @var string. Default '@frontend/web/files/%className%/' or '@app/web/files/%className%/'
     */
    public $savePathAlias;

    /**
     * @var string part of url for file without hostname. Default '/files/%className%/'
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
     * function for EVENT_BEFORE_VALIDATE
     */
    public function beforeValidate()
    {
        $model = $this->owner;
        foreach ($this->attributes as $attr => $options) {
            self::ensureAttributes($attr, $options);
            if ($file = UploadedFile::getInstance($model, $attr)) {
                $model->{$attr} = $file;
            }
        }
    }

    /**
     * function for EVENT_BEFORE_INSERT and EVENT_BEFORE_UPDATE
     */
    public function beforeSave()
    {
        $model = $this->owner;
        foreach ($this->attributes as $attr => $options) {
            self::ensureAttributes($attr, $options);
            if ($file = UploadedFile::getInstance($model, $attr)) {
                $this->createDirIfNotExists($attr);
                if (!$model->isNewRecord) {
                    $this->deleteFiles($attr);
                }
                $fileName = uniqid('', true) . '.' . $file->extension;
                $model->{$attr} = $fileName;
                $file->saveAs($this->getSavePath($attr) . $fileName);
            } elseif (isset($model->oldAttributes[$attr])) {
                $model->{$attr} = $model->oldAttributes[$attr];
            }
        }
    }

    /**
     * @param ActiveRecord $object
     * @return string
     * @throws \ReflectionException
     */
    private function getShortClassName($object)
    {
        $obj = new \ReflectionClass($object);
        return mb_strtolower($obj->getShortName());
    }

    /**
     * function for EVENT_BEFORE_DELETE
     */
    public function beforeDelete()
    {
        foreach ($this->attributes as $attr => $options) {
            self::ensureAttributes($attr, $options);
            $this->deleteFiles($attr);
        }
    }

    /**
     * @param string $attr name of attribute
     * @param ActiveRecord $object that keep attribute. Default $this->owner
     * @return string url to image
     */
    public function getFileUrl($attr, $object = null)
    {
        $this->checkAttrExists($attr);
        $prefix = $this->getUrlPrefix($attr);
        $object = isset($object) ? $object : $this->owner;
        return $prefix . $object->{$attr};
    }

    /**
     * @param string $attr name of attribute
     * @throws Exception
     */
    private function createDirIfNotExists($attr)
    {
        $dir = $this->getSavePath($attr);
        if (!is_dir($dir)) {
            FileHelper::createDirectory($dir);
        }
    }

    /**
     * @param string $attr name of attribute
     * @return string save path
     * @throws \ReflectionException
     */
    private function getSavePath($attr)
    {
        if (isset($this->attributes[$attr]['savePathAlias'])) {
            return rtrim(Yii::getAlias($this->attributes[$attr]['savePathAlias']), '\/') . DIRECTORY_SEPARATOR;
        }
        if (isset($this->savePathAlias)) {
            return rtrim(Yii::getAlias($this->savePathAlias), '\/') . DIRECTORY_SEPARATOR;
        }

        if (isset(Yii::$aliases['@frontend'])) {
            return Yii::getAlias('@frontend/web/files/' . $this->getShortClassName($this->owner)) . DIRECTORY_SEPARATOR;
        }

        return Yii::getAlias('@app/web/files/' . $this->getShortClassName($this->owner)) . DIRECTORY_SEPARATOR;
    }

    /**
     * @param string $attr name of attribute
     * @param ActiveRecord $object for default prefix
     * @return string url prefix
     * @throws \ReflectionException
     */
    private function getUrlPrefix($attr, $object = null)
    {
        if (isset($this->attributes[$attr]['urlPrefix'])) {
            return '/' . trim($this->attributes[$attr]['urlPrefix'], '/') . '/';
        }
        if (isset($this->urlPrefix)) {
            return '/' . trim($this->urlPrefix, '/') . '/';
        }

        $object = isset($object) ? $object : $this->owner;
        return '/files/' . $this->getShortClassName($object) . '/';
    }

    /**
     * Delete images
     * @param string $attr name of attribute
     * @throws \ReflectionException
     */
    private function deleteFiles($attr)
    {
        $base = $this->getSavePath($attr);
        $model = $this->owner;
        if ($model->isNewRecord) {
            $value = $model->{$attr};
        } else {
            $value = $model->oldAttributes[$attr];
        }
        $file = $base . $value;

        if (is_file($file)) {
            unlink($file);
        }
    }

    /**
     * Check isset attribute or not
     * @param string $attribute name of attribute
     * @throws InvalidArgumentException
     */
    private function checkAttrExists($attribute)
    {
        foreach ($this->attributes as $attr => $options) {
            self::ensureAttributes($attr, $options);
            if ($attr == $attribute) {
                return;
            }
        }

        throw new InvalidArgumentException('checkAttrExists failed');
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
