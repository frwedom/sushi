<?php if (!empty($page)) : ?>

    <section class="slice slice-xl ispage" data-delimiter="1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div>
                        <h2 class="heading h1 mb-4 text-center">
                            <?= $page['name'] ?>
                        </h2>

                        <div>
                            <div class="text-center m-4">
                                <h5><?= $page['description'] ?></h3>
                            </div>

                            <div class="page__text">
                                <?= $page['full_description'] ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if ($page[0] == 7) : ?>

        <?php if (!empty($best) && !empty($best_parent)) : ?>
            <section class="slice-lg">
                <div class="container">
                    <div class="row align-items-center cols-xs-space cols-sm-space cols-md-space">
                        <div class="col-lg-12 text-center mb-4">
                            <h2><?= $best_parent['name'] ?></h4>
                        </div>
                        <div class="col-lg-6 order-lg-2 ml-lg-auto">
                            <div class="block block-image">
                                <img src="/uploads/images/pages/<?= json_decode($best_parent['images'])[0] ?>" class="img-fluid img-center">
                            </div>
                        </div>
                        <div class="col-lg-5 order-lg-1">
                            <div class="row-wrapper">
                                <?php foreach ($best as $v) : ?>
                                    <div class="row">
                                        <div class="col">
                                            <div class="d-flex align-items-start">
                                                <div class="icon icon-lg">
                                                    <i class="<?= $v['icon'] ?>"></i>
                                                </div>
                                                <div class="icon-text">
                                                    <h3 class="heading h4"><?= $v['name'] ?></h3>
                                                    <p><?= $v['description'] ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php if (!empty($callus)) : ?>
            <section class="slice grey_header">
                <div class="container">
                    <div class="row align-items-center cols-xs-space cols-sm-space cols-md-space text-center text-lg-left">
                        <div class="col-lg-7">
                            <h1 class="heading h2 text-white strong-500">
                                <?= $callus['name'] ?>
                            </h1>
                            <p class="lead text-white mb-0"> <?= $callus['description'] ?></p>
                        </div>
                        <div class="col-lg-3 ml-lg-auto">
                            <div class="text-center text-md-right">
                                <a href="<?= $callus['link'] ?>" class="btn bg-secondary">
                                    Контакты
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($page[0] == 6) : ?>
        <?php if (!empty($this->layoutParams['addresses_parent']) && !empty($this->layoutParams['addresses'])) : ?>
            <section class="slice bg-tertiary bg-cover bg-size--cover" style="background-image: url('/uploads/images/company/<?= json_decode($this->layoutParams['addresses_parent']['images'])[0] ?>')">
                <span class="mask bg-tertiary alpha-9"></span>
                <div class="container">
                    <div class="row cols-xs-space cols-sm-space cols-md-space justify-content-center">
                        <?php foreach ($this->layoutParams['addresses'] as $address) : ?>
                            <div class="col-lg-6">
                                <div class="card bg-dark alpha-container text-white border-0 overflow-hidden">
                                    <a href="<?= $address['map_code'] ?>" target="_blank">
                                        <div class="card-img-bg" style="background-image: url('/uploads/images/company/<?= json_decode($address['images'])[0] ?>');"></div>
                                        <span class="mask bg-dark alpha-5 alpha-4--hover"></span>
                                        <div class="card-body px-5 py-5">
                                            <div style="min-height: 300px;">
                                                <h3 class="heading h1 text-white mb-3"><?= $address['city'] ?></h3>
                                                <p class="mt-4 mb-1 h5 text-white lh-180">
                                                    <i class="<?= $address['icon'] ?>"></i> <?= $address['value'] ?>
                                                </p>
                                            </div>
                                            <span href="#" class="text-white text-uppercase font-weight-500">
                                                Посмотреть на карте
                                                <svg class="svg-inline--fa fa-arrow-right fa-w-14 ml-2" aria-hidden="true" data-prefix="fas" data-icon="arrow-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                                    <path fill="currentColor" d="M190.5 66.9l22.2-22.2c9.4-9.4 24.6-9.4 33.9 0L441 239c9.4 9.4 9.4 24.6 0 33.9L246.6 467.3c-9.4 9.4-24.6 9.4-33.9 0l-22.2-22.2c-9.5-9.5-9.3-25 .4-34.3L311.4 296H24c-13.3 0-24-10.7-24-24v-32c0-13.3 10.7-24 24-24h287.4L190.9 101.2c-9.8-9.3-10-24.8-.4-34.3z"></path>
                                                </svg><!-- <i class="fas fa-arrow-right ml-2"></i> -->
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
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

    <?php endif; ?>
<?php endif; ?>