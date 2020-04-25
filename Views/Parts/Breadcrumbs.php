
<?php

$breadcrumbs = \App::$breadcrumbs->getBreads($this->table_name, $this->translations_table, $id, $type);

$last = end($breadcrumbs);

?>


<nav aria-label="breadcrumb">
    <ol class="breadcrumb">

    <li class="breadcrumb-item"><a href="/"><?= $this->translates['breadcrumbs']['main'] ?></a></li>

    <?php foreach ($breadcrumbs as $k => $v) : ?>

        <?php if ($v != $last) : ?>

            <li class="breadcrumb-item"><a href="/<?= $controller ?>/<?= !empty($k) ? "c/$k" : '' ?>"><span><?= $v ?></span></a>
            </li>

        <?php else : ?>

            <li class="breadcrumb-item active" aria-current="page"><span><?= $v ?></span></li>

        <?php endif; ?>

    <?php endforeach; ?>
    </ol>
</nav>

