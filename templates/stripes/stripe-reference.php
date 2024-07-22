<?php if (!empty($referencePage)) : ?>
<?php $referenceTypeTemplate = $template_name . '--' . $referencePage['type']; ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo !empty($referencePage['type']) ? "data-type=\"{$referencePage['type']}\"" : '';
    echo !empty($variation) ? " data-variation=\"{$variation}\"" : '';
?>>
  <?php
  if ($this->engine->exists("$referenceTypeTemplate")) {
    $this->insert($referenceTypeTemplate, get_defined_vars());
  } else {
    $this->insert($template_name . '--default', get_defined_vars());
  }
  ?>
</div>
<?php endif;
