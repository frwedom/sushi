<?php
$result = \App::$pagination->getPagination($per_page, $query, $active_value, $query_params);

?>



        <footer class="pagination">
            <ul class="pagination-list">
                <?php foreach ($result as $v) : ?>
                    <li class="hidden-phone">
                        <?php if ($v['active']) : ?>
                            <span class="pagenav"><?= $v['order'] ?></span>
                        <?php else : ?>
                            <a href="?start=<?= $v['start'] ?>" class="pagenav <?= $v['active'] ? 'pagination_active' : '' ?>">
                                <?= $v['order'] ?>
                            </a>
                        <?php endif; ?>

                    </li>
                <?php endforeach; ?>

            </ul>
        </footer>



