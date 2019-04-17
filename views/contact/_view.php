<?php
if (!isset($class)) $class = 'col-md-2';
?>
<?php if ($model->{$attrib}): ?>
    <div class="<?= $class ?>">
        <p class="bg-primary">
        <div style="background-color: white; padding: 5px; font-size:11px; color : #999; font-weight: bold; border-bottom: 2px solid #3c8dbc;"><?= $model->getAttributeLabel($attrib) ?></div>
        <div style="background-color: white; padding: 5px; font-size:16px;"><?php echo $model->{$attrib} ?></div>
        </p>
    </div>
<?php endif ?>