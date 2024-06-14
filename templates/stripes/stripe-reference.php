<?php if (!empty($referencePage)) : ?>
<?php $reference_type_template = $template_name . '--' . $referencePage['type']; ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>">
  <?php
  if ($this->engine->exists("$reference_type_template")) {
    $this->insert($reference_type_template, get_defined_vars());
  } else {
    $this->insert("$template_name--default", get_defined_vars());
  }
  ?>
</div>
<?php endif; ?>
