<?php
use Backend\Widgets\Form;

// Render the form for creating new SEO settings
$formWidget = new Form($this->controller, $this->formConfig);
$formWidget->bindToController();

?>

<div class="create-seo-settings">
    <h2><?= __("Create New SEO Setting") ?></h2>
    <?= $formWidget->render() ?>
</div>

<style>
    .create-seo-settings {
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
    }
</style>