<?php if (!empty($reference_page)) : ?>
<?php $referenceTypeTemplate = $template_name . '--' . $reference_page['type']; ?>
<div class="<?= $this->e($type, 'wrapperClasses') ?>"<?php
    echo !empty($reference_page['type']) ? " data-type=\"{$reference_page['type']}\"" : '';
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
