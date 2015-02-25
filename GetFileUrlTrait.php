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
        if (mb_strpos(get_class($this), 'backend') === false) {
            $class = new \ReflectionClass($this);
            $class = 'backend\models\\' . $class->getShortName();
            $model = new $class;
            foreach ($model->behaviors as $b) {
                if ($b instanceof Behavior) {
                    return $b->getFileUrl($attr, $this);
                }
            }
        }

        foreach ($this->behaviors as $b) {
            if ($b instanceof Behavior) {
                return $b->getFileUrl($attr);
            }
        }

        return null;
    }
}
