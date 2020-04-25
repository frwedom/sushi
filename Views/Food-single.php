<section class="slice slice-xl ispage" data-delimiter="1">
</section>

<section class="slice sct-color-1">
    <div class="container">
        <div class="row cols-xs-space cols-sm-space align-items-center">
            <div class="col-md-6">
                <img src="/uploads/images/foods/<?= json_decode($item['images'])[0] ?>" class="img-center img-fluid rounded z-depth-3">
            </div>
            <div class="col-md-6 col-lg-5 ml-lg-auto">
                <div class="pr-md-4">
                    <h3 class="heading heading-3 strong-500">
                        <?= $item['name'] ?>
                    </h3>

                    <h5 class="foods__price">
                        <?php if (!empty($item['sale_percent'])) : ?>
                            <small class="foods__price_old">
                                <?= ($item['price'] * (100 - $item['sale_percent'])) / 100 ?> тг.
                            </small> &nbsp;
                        <?php endif; ?>
                        <?= $item['price'] ?> тг.
                    </h5>
                    <p class="lead text-gray mt-4">
                        <?= $item['description'] ?>
                    </p>

                    <?php if (!empty($item['fats']) || !empty($item['proteins']) || !empty($item['carbohydrates']) || !empty($item['nutritional']) || !empty($item['weight'])) : ?>
                        <div>
                            <table class="table table-hover table-cards align-items-center">
                                <thead>
                                    <tr>
                                        <th scope="col">Пункт</th>
                                        <th scope="col">Значение</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($item['fats'])) : ?>
                                        <tr class="bg-white">
                                            <th scope="row">
                                                <div class="media align-items-center">
                                                    <div class="media-body">
                                                        <h6 class="h5 font-weight-normal mb-0">Жиры</h6>
                                                    </div>
                                                </div>
                                            </th>
                                            <td><?= $item['fats'] ?> г. </td>
                                        </tr>
                                        <tr class="table-divider"></tr>
                                    <?php endif; ?>

                                    <?php if (!empty($item['proteins'])) : ?>
                                        <tr class="bg-white">
                                            <th scope="row">
                                                <div class="media align-items-center">
                                                    <div class="media-body">
                                                        <h6 class="h5 font-weight-normal mb-0">Белки</h6>
                                                    </div>
                                                </div>
                                            </th>
                                            <td><?= $item['proteins'] ?> г. </td>
                                        </tr>
                                        <tr class="table-divider"></tr>
                                    <?php endif; ?>

                                    <?php if (!empty($item['carbohydrates'])) : ?>
                                        <tr class="bg-white">
                                            <th scope="row">
                                                <div class="media align-items-center">
                                                    <div class="media-body">
                                                        <h6 class="h5 font-weight-normal mb-0">Углеводы</h6>
                                                    </div>
                                                </div>
                                            </th>
                                            <td><?= $item['carbohydrates'] ?> г. </td>
                                        </tr>
                                        <tr class="table-divider"></tr>
                                    <?php endif; ?>

                                    <?php if (!empty($item['nutritional'])) : ?>
                                        <tr class="bg-white">
                                            <th scope="row">
                                                <div class="media align-items-center">
                                                    <div class="media-body">
                                                        <h6 class="h5 font-weight-normal mb-0">Пищевая ценность</h6>
                                                    </div>
                                                </div>
                                            </th>
                                            <td><?= $item['nutritional'] ?> ккал </td>
                                        </tr>
                                        <tr class="table-divider"></tr>
                                    <?php endif; ?>

                                    <?php if (!empty($item['weight'])) : ?>
                                        <tr class="bg-white">
                                            <th scope="row">
                                                <div class="media align-items-center">
                                                    <div class="media-body">
                                                        <h6 class="h5 font-weight-normal mb-0">Вес</h6>
                                                    </div>
                                                </div>
                                            </th>
                                            <td><?= $item['weight'] ?> г. </td>
                                        </tr>
                                        <tr class="table-divider"></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                    <div>
                        <?= $item['full_description'] ?>
                    </div>

                    <div>
                        <div class="btn-container mt-5">
                            <a href="/pages/c/<?= $this->layoutParams['contacts_page']['url'] ?>" class="btn btn-primary">
                                Заказать
                            </a>
                            <a href="/home/#list" class="btn btn-link">Вернуться назад</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>