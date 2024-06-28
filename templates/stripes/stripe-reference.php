<?php if (!empty($referencePage)) : ?>
<?php $referenceTypeTemplate = $template_name . '--' . $referencePage['type']; ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>" data-type="<?= $referencePage['type'] ?? '' ?>">
  <?php
  if ($this->engine->exists("$referenceTypeTemplate")) {
    $this->insert($referenceTypeTemplate, get_defined_vars());
  } else {
    $this->insert($template_name . '--default', get_defined_vars());
  }
  ?>
</div>
<?php endif;

