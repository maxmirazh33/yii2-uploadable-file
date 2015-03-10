<?php
namespace maxmirazh33\file;

trait GetFileUrlTrait
{
    /**
     * @param string $attr name of attribute
     * @return null|string url to file
     */
    public function getFileUrl($attr)
    {
        foreach ($this->behaviors as $behavior) {
            if ($behavior instanceof Behavior) {
                return $behavior->getFileUrl($attr);
            }
        }

        $class = new \ReflectionClass($this);
        $class = 'backend\models\\' . $class->getShortName();
        if (class_exists($class)) {
            $model = new $class;
            foreach ($model->behaviors as $behavior) {
                if ($behavior instanceof Behavior) {
                    return $behavior->getFileUrl($attr, $this);
                }
            }
        }

        return null;
    }
}
