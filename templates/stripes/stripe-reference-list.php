<?php if (!empty($referencePages)) : ?>

    <?php
        // Replace everything after the last "/" with "stripe-reference"
        $reference_template = preg_replace(
            "/\/.*$/",
            '/stripe-reference',
            $template_name
        );
    ?>
    <?php foreach ($referencePages as $referencePage) : ?>
        <?php
        $reference_vars = array_merge(
            get_defined_vars(),
            [
                'type' => 'stripe-reference',
                'template_name' => $reference_template,
                'referencePage' => $referencePage
            ]
        );
        $this->insert($reference_template, $reference_vars);
        ?>
    <?php endforeach; ?>
<?php endif;
