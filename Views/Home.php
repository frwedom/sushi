<?php if (!empty($banner)) : ?>
    <section class="spotlight parallax bg-cover bg-size--cover" data-spotlight="fullscreen" style="background-image: url('/uploads/images/main/<?= json_decode($banner['images'])[0] ?>')">
        <span class="mask bg-primary alpha-7"></span>
        <div class="spotlight-holder py-lg pt-lg-xl">
            <div class="container d-flex align-items-center no-padding">
                <div class="col">
                    <div class="row cols-xs-space align-items-center text-center text-md-left justify-content-center">
                        <div class="col-7">
                            <div class="text-center mt-5">
                                <a href="#list">
                                    <img src="/uploads/images/main/<?= json_decode($banner['images2'])[0] ?>" style="width: 200px;" class="img-fluid animated" data-animation-in="jackInTheBox" data-animation-delay="1000">
                                </a>
                                <h2 class="heading display-4 font-weight-400 text-white mt-5 animated" data-animation-in="fadeInUp" data-animation-delay="2000">
                                    <?= $banner['name'] ?>
                                </h2>
                                <p class="lead text-white mt-3 lh-180 c-white animated" data-animation-in="fadeInUp" data-animation-delay="2500">
                                    <?= $banner['description'] ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<foods inline-template :connect="''">

    <div>
        <section class="slice slice-xl pb-5" id="list" data-delimiter="1">
            <div class="container">
                <div class="row align-items-center mb-4">
                    <div class="col-lg-12 mb-4">
                        <div class="text-center text-lg-left">
                            <h3 class="heading h3">Суши</h3>
                            <div class="row mt-3">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Поиск по наименованию</label>
                                        <div class="input-group input-group-transparent mb-4">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                            </div>
                                            <input type="text" class="form-control" placeholder="Поиск" v-model="foods__items.searchByName">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Сортировка по</label>
                                        <div class="input-group input-group-transparent mb-4">
                                            <select class="form-control" @change="foods__sortIt">
                                                <option value="date_added" data-type="1">По дате добавления</option>
                                                <option value="name" data-type="0">По наименованию</option>
                                                <option value="price" data-type="1">По цене</option>
                                            </select>

                                            <div @click="foods__changeSortRow" class="foods__sort-arrows">
                                                <div v-show="foods__items.sortByValues.row == 'DESC'">
                                                    <i class="fas fa-sort-amount-up"></i>
                                                </div>
                                                <div v-show="foods__items.sortByValues.row == 'ASC'" style="display: none;">
                                                    <i class="fas fa-sort-amount-down"></i>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div v-show="foods__items.firstLoading == false" style="display: none;">
                        <div class="row" v-if="foods__items.items.length > 0">
                            <div class="col-md-4 foods__item" v-for="(item, index) in foods__items.items" :class="foods__items.loading ? 'loading' : ''" :food-margin="index < 3 ? '0' : '1'">
                                <div class="card bg-lighter">
                                    <div class="px-3">
                                        <img class="card-img z-depth-2" :src="item['images'] ? '/uploads/images/foods/' + JSON.parse(item['images'])[0] : ''" style="margin-top: -30px;" :alt="item.name">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title mb-2">{{ item.name }}</h5>
                                        <p class="card-text">{{ item.description }}</p>
                                        <div class="card-footer">
                                            <div>
                                                <h5 class="foods__price">
                                                    <small class="foods__price_old" v-if="item.sale_percent != 0 && item.sale_pecent !== null && item.sale_percent !== ''">{{ (item.price * (100 - item.sale_percent)) / 100 }} тг.</small>
                                                    {{ item.price }} тг.
                                                </h5>
                                            </div>

                                            <div>
                                                <a :href="'/foods/a/' + item.url" class="btn btn-block foods_more_button">Подробнее</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mt-4">
                                <nav aria-label="list" v-show="foods__items.pagination.pages.length > 1">
                                    <ul class="pagination">
                                        <li class="page-item" title="К началу">
                                            <a class="page-link" href="#" v-if="foods__items.pagination.currentPage > 0" :data-start="0" @click.prevent="foods__changePage">
                                                <i class="fas fa-angle-double-left"></i>
                                            </a>
                                        </li>
                                        <li class="page-item" title="Назад">
                                            <a class="page-link" href="#" :data-start="foods__items.pagination.start - foods__items.pagination.per_page" @click="foods__changePage" v-if="foods__items.pagination.currentPage > 0">
                                                <i class="fas fa-angle-left"></i>
                                            </a>
                                        </li>
                                        <!-- v-if="foods__items.pagination.currentPage <= page.start + 2 && foods__items.pagination.currentPage >= page.start - 2" -->
                                        <li class="page-item" v-for="page in foods__items.pagination.pages" :class="[foods__items.pagination.start == page.start ? 'active' : '']">
                                            <a class="page-link" href="#" @click="foods__changePage" :data-start="page.start">{{ page.order }}</a>
                                        </li>
                                        <li class="page-item" title="Вперед">
                                            <a class="page-link" href="#" :data-start="foods__items.pagination.start + foods__items.pagination.per_page" @click.prevent="foods__changePage" v-if="foods__items.pagination.currentPage + 1 < foods__items.pagination.pages.length">
                                                <i class="fas fa-angle-right"></i>
                                            </a>
                                        </li>
                                        <li class="page-item" title="В конец">
                                            <a class="page-link" href="#" v-if="foods__items.pagination.pages.length > 1 && foods__items.pagination.currentPage + 1 < foods__items.pagination.pages.length" :data-start="(foods__items.pagination.pages.length - 1) * foods__items.pagination.per_page" @click.prevent="foods__changePage">
                                                <i class="fas fa-angle-double-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>

                        </div>

                        <div class="row text-center mt-4" v-else-if="foods__items.filter == true">
                            <div class="col-md-12">
                                Не удалось ничего найти. Попробуйте другие варианты
                            </div>
                        </div>

                        <div class="row text-center mt-4" v-else>
                            <div class="col-md-12">
                                В данный момент список пуст. Попробуйте позже
                            </div>
                        </div>
                    </div>

                </div>

                <div v-show="foods__items.firstLoading == true">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </div>
            </div>

        </section>
    </div>
</foods>

<?php if (!empty($dop_block)) : ?>
    <section class="slice slice-lg" data-delimiter="1">
        <div class="container">
            <div class="px-4">
                <div class="row justify-content-center cols-xs-space cols-sm-space cols-md-space">
                    <div class="col-lg-7">
                        <div class="text-center">
                            <h1 class="heading h3">
                                <?= $dop_block['name'] ?>
                            </h1>
                            <div class="lead lh-180 mt-4">
                                <?= $dop_block['full_description'] ?>
                            </div>
                            <!-- <div class="btn-container mt-5">
                                <a href="#" class="btn btn-primary">
                                    Download FREE
                                </a>
                                <a href="#" class="btn btn-link">See all features</a>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>


<contact inline-template>
    <div>
        <section class="slice slice-lg" id="callback">
            <div class="container">
                <div class="row align-items-center cols-xs-space cols-sm-space cols-md-space">
                    <div class="col-lg-6">
                        <h3 class="heading h3 mb-4">Обратная связь</h3>
                        <form>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Как вас звать? *" type="text" v-model="contactForm.name">
                                        <span class="gates__form_error" v-show="contactFormErrors.name" style="display: none;">{{ contactFormErrors.name }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Email *" type="email" v-model="contactForm.email">
                                        <span class="gates__form_error" v-show="contactFormErrors.email " style="display: none;">{{ contactFormErrors.email  }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Номер телефона *" type="text" v-model="contactForm.phone_number">
                                        <span class="gates__form_error" v-show="contactFormErrors.phone_number " style="display: none;">{{ contactFormErrors.phone_number  }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <textarea class="form-control" rows="5" placeholder="Ваше сообщение *" v-model="contactForm.message"></textarea>
                                        <span class="gates__form_error" v-show="contactFormErrors.message" style="display: none;">{{ contactFormErrors.message }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary px-4" @click.prevent="sendIt">
                                    Отправить сообщение
                                    <div class="loading_icon" v-show="loading" style="display: none">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </div>

                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-5 ml-lg-auto">
                        <?php if (!empty($this->layoutParams['addresses'])) : ?>
                            <?php foreach ($this->layoutParams['addresses'] as $address) : ?>
                                <h3 class="heading heading-3 strong-300">
                                    <i class="<?= $address['icon'] ?>"></i> <?= $address['value'] ?>
                                </h3>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <p class="lead mt-4 mb-4">
                            <?php if (!empty($this->layoutParams['mails'])) : ?>
                                <?php foreach ($this->layoutParams['mails'] as $phone) : ?>
                                    <a href="mailto:<?= $phone['mail'] ?>"><i class="<?= $phone['icon'] ?>"></i> <?= $phone['mail'] ?></a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <br>
                            <?php if (!empty($this->layoutParams['phones'])) : ?>
                                <?php foreach ($this->layoutParams['phones'] as $phone) : ?>
                                    <a href="tel:<?= preg_replace('/[^0-9]/', '', $phone['phone_number']); ?>"><i class="<?= $phone['icon'] ?>"></i> <?= $phone['phone_number'] ?></a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </p>
                        <p class="">
                            <?= $this->layoutParams['contacts_info']['full_description'] ?>
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</contact>