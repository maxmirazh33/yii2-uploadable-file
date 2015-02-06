<?php

/**
 * File upload view.
 *
 * @var \yii\web\View $this
 * @var string $selector Widget ID selector
 * @var \yii\db\ActiveRecord $model
 * @var string $attribute
 */

use yii\helpers\Html;
?>

<div id="<?= $selector ?>" class="form-group uploader">
    <div class="btn btn-default fullinput">
        <div class="uploader-browse" onclick='$("#<?= $selector ?>-input").click(); return false;'>
            <span class="glyphicon glyphicon-file"></span>
                <span class="browse-text" id="<?= $selector ?>-name">
                    <?= Yii::t('maxmirazh33/file', 'Select') ?>
                </span>
            <?= Html::activeFileInput(
                $model,
                $attribute,
                ['id' => $selector . '-input', 'onchange' => '$("#" + "' . $selector . '" + "-name").html(this.files[0].name);']
            ) ?>
        </div>
    </div>
    <?= Html::activeHiddenInput($model, $attribute) ?>
</div>
