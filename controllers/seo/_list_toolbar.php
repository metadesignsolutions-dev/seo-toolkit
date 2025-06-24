<div data-control="toolbar">
    <a
        href="<?= Backend::url('metadesignsolutions/mdsoctoberseo/seo/create') ?>"
        class="btn btn-primary">
        <i class="icon-plus"></i>
        <?= __("New SEO Setting") ?>
    </a>

    <div class="toolbar-divider"></div>

    <button
        class="btn btn-secondary"
        data-request="onDelete"
        data-request-confirm="<?= __("Are you sure you want to delete the selected items?") ?>"
        data-list-checked-trigger
        data-list-checked-request
        disabled>
        <i class="icon-trash"></i>
        <?= __("Delete") ?>
    </button>
</div>