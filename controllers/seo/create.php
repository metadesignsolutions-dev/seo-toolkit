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

<div class="text-center mt-4">
    <p class="text-muted small">SEO Toolkit - A comprehensive SEO plugin for October CMS</p>
    <p class="text-muted small">© 2025 MetaDesign Solutions. All rights reserved.</p>
    <p class="text-muted small"><a href="https://metadesignsolutions.com" target="_blank">metadesignsolutions.com</a></p>
</div>

<style>
    .create-seo-settings {
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
    }
</style>