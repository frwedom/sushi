 <?php //password_hash('kdje28smn', PASSWORD_DEFAULT); ?>

<!doctype html>
<html lang="<?= \App::$language->getCurrentLanguage() ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $this->title ?></title>
    <meta name="description" content="<?= $this->description ?>">
    <meta name="keywords" content="<?= $this->keywords ?>">
    <meta name="author" content="<?= $this->author ?>">

    <link rel="apple-touch-icon" sizes="180x180" href="/uploads/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/uploads/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/uploads/favicon/favicon-16x16.png">
    <link rel="manifest" href="/uploads/favicon/site.webmanifest">
    <link rel="mask-icon" href="/uploads/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>

    <!-- Gates Custom styles -->
    <link rel="stylesheet" href="/assets/gates_custom/styles/custom.css?v=0.0.15">

    <!-- vue / axios -->
    <script type='text/javascript' src='/assets/gates_custom/libraries/vue.min.js' rel="prefetch"></script>
    <script type='text/javascript' src='/assets/gates_custom/libraries/axios.min.js'></script>
    <script type='text/javascript' src='/assets/gates_custom/libraries/axios.min.js'></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,800|Roboto:400,500,700" rel="stylesheet">
    <!-- Theme CSS -->
    <link type="text/css" href="/assets/css/theme.css" rel="stylesheet">
    <link type="text/css" href="/assets/css/demo.css" rel="stylesheet">

</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-transparent navbar-dark bg-dark py-4 <?= $this->header_grey == true ? 'grey_header' : '' ?>">
            <div class="container">
                <a class="navbar-brand" href="/">
                    <?php if ($header_logo['images']) : ?>
                        <img src="/uploads/images/company/<?= json_decode($header_logo['images'])[0] ?>" width="140">
                    <?php endif ?>
                </a>
                <button class="navbar-toggler" type="button" data-action="offcanvas-open" data-target="#navbar_main" aria-controls="navbar_main" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse offcanvas-collapse" id="navbar_main">
                    <ul class="navbar-nav ml-auto align-items-lg-center">
                        <h6 class="dropdown-header font-weight-600 d-lg-none px-0">Меню</h6>

                        <?php
                        $uri = $_SERVER['REQUEST_URI'];
                        ?>

                        <?php if (!empty($header_menu[0]['children'])) : ?>
                            <?php foreach ($header_menu[0]['children'] as $menu) : ?>
                                <?php
                                if ($uri == '/') {
                                    $uri = '/home/';
                                }
                                $needle = $menu['link'];
                                $pos = strripos($uri, $needle);

                                $has_children = $menu['children'] ? true : false;
                                ?>
                                <li class="nav-item <?= $pos !== false ? 'active' : '' ?> <?= $has_children ? 'dropdown' : '' ?>">
                                    <a class="nav-link <?= $has_children ? 'dropdown-toggle' : '' ?>" <?= $has_children ? 'id="navbar_main_dropdown_' . $menu[0] . '"  role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : '' ?> href="<?= $menu['link'] ?>"><?= $menu['name'] ?></a>
                                    <?php if ($has_children) : ?>
                                        <div class="dropdown-menu" <?= 'aria-labelledby="navbar_1_dropdown_' . $menu[0] . '"' ?>>
                                            <?php foreach ($menu['children'] as $child) : ?>
                                                <a class="dropdown-item" href="<?= $child['link'] ?>"><?= $child['name'] ?></a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a :href="callbackEl ? '#callback' : '/pages/c/<?= $contacts_page['url'] ?>#callback'" class="nav-link d-lg-none">Обратная связь</a>
                            <a :href="callbackEl ? '#callback' : '/pages/c/<?= $contacts_page['url'] ?>#callback'" class="btn btn-sm bg-white d-none d-lg-inline-flex">Обратная связь</a>
                        </li>
                        <div class="dropdown-divider d-lg-none my-4"></div>

                        <?php if (!empty($social)) : ?>
                            <h6 class="dropdown-header font-weight-600 d-lg-none px-0">Социальные сети</h6>

                            <?php foreach ($social as $soc) : ?>
                                <li class="nav-item">
                                    <a class="nav-link nav-link-icon" href="<?= $soc['link'] ?>" target="_blank"><i class="<?= $soc['icon'] ?>"></i><span class="ml-2 d-lg-none"><?= $soc['name'] ?></span></a>
                                </li>
                            <?php endforeach ?>

                        <?php endif ?>

                    </ul>
                </div>
            </div>
        </nav>

        <main class="main">
            <?= $body ?>
        </main>
        <footer class="pt-5 pb-3 footer  footer-dark bg-tertiary">
            <div class="container">
                <div class="row">
                    <?php if (!empty($footer_info)) : ?>
                        <div class="col-12 col-md-6">
                            <div class="pr-lg-5">
                                <h1 class="heading h6 text-uppercase font-weight-700 mb-3"><?= $footer_info['name'] ?></h1>
                                <p><?= $footer_info['full_description'] ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($footer_menu[0]['children'])) : ?>
                        <div class="col-12 col-md-3">
                            <h5 class="heading h6 text-uppercase font-weight-700 mb-3"><?= $footer_menu[0]['name'] ?></h5>
                            <ul class="list-unstyled text-small">
                                <?php foreach ($footer_menu[0]['children'] as $menu) : ?>
                                    <li><a class="text-muted" href="<?= $menu['link'] ?>"><?= $menu['name'] ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="col-12 col-md-3">
                        <h5 class="heading h6 text-uppercase font-weight-700 mb-3">Контакты</h5>
                        <ul class="list-unstyled text-small">
                            <?php if (!empty($phones)) : ?>
                                <?php foreach ($phones as $phone) : ?>
                                    <li><a class="text-muted" href="tel:<?= preg_replace('/[^0-9]/', '', $phone['phone_number']); ?>"><i class="<?= $phone['icon'] ?>"></i> <?= $phone['phone_number'] ?></a></li>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <?php if (!empty($mails)) : ?>
                                <?php foreach ($mails as $mail) : ?>
                                    <li><a class="text-muted" href="mailto:<?= $mail['mail'] ?>"><i class="<?= $mail['icon'] ?>"></i> <?= $mail['mail'] ?></a></li>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <?php if (!empty($addresses)) : ?>
                                <?php foreach ($addresses as $address) : ?>
                                    <li><a class="text-muted" href="" @click.prevent=""><i class="<?= $address['icon'] ?>"></i> <?= $address['value'] ?></a></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <hr>
                <div class="d-flex align-items-center">
                    <span class="">
                        Copyright &copy; <script>
                            document.write(new Date().getFullYear());
                        </script>
                        Все права защищены
                    </span>
                    <ul class="nav ml-lg-auto">
                        <?php if (!empty($social)) : ?>
                            <h6 class="dropdown-header font-weight-600 d-lg-none px-0">Социальные сети</h6>

                            <?php foreach ($social as $soc) : ?>
                                <li class="nav-item">
                                    <a class="nav-link nav-link-icon" href="<?= $soc['link'] ?>" target="_blank"><i class="<?= $soc['icon'] ?>"></i><span class="ml-2 d-lg-none"><?= $soc['name'] ?></span></a>
                                </li>
                            <?php endforeach ?>

                        <?php endif ?>
                    </ul>
                </div>
            </div>
        </footer>

        <!--Notifications-->
        <notifications>
            <transition-group name="notification" tag="div">
                <notification v-for="notification in notifications" v-bind:key="notification" :notification="notification" @close="removeNotification(notification)"></notification>
            </transition-group>
        </notifications>

    </div>



    <!--Notifications-->
    <script type="text/x-template" id="notifications-template">
        <div class="vue-toast">
        <div class="toast__container">
            <div class="toast__cell">
                <slot></slot>
            </div>
        </div>
    </div>
    </script>
    <script type="text/x-template" id="notification-template">
        <transition name="list">
            <div class="notification">
                <div class="toastt" :class="'toast--' + notification.type">
                    <div class="toast__icon"></div>
                    <div class="toast__content">
                        <p class="toast__type">{{ notification.title }}</p>
                        <p class="toast__message" v-html="notification.text"></p>
                    </div>
                    <div class="toast__close" @click="$emit('close')">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15.642 15.642"
                            xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 15.642 15.642">
                            <path fill-rule="evenodd"
                                d="M8.882,7.821l6.541-6.541c0.293-0.293,0.293-0.768,0-1.061  c-0.293-0.293-0.768-0.293-1.061,0L7.821,6.76L1.28,0.22c-0.293-0.293-0.768-0.293-1.061,0c-0.293,0.293-0.293,0.768,0,1.061  l6.541,6.541L0.22,14.362c-0.293,0.293-0.293,0.768,0,1.061c0.147,0.146,0.338,0.22,0.53,0.22s0.384-0.073,0.53-0.22l6.541-6.541  l6.541,6.541c0.147,0.146,0.338,0.22,0.53,0.22c0.192,0,0.384-0.073,0.53-0.22c0.293-0.293,0.293-0.768,0-1.061L8.882,7.821z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </transition>
    </script>

    <!-- Modal -->
    <script type="text/x-template" id="modal">

        <transition name="modal-fade">
        <div class="modal-backdrop-vue" role="dialog">
            <div class="modal-vue big" ref="modal">
                <div class="modal-header-vue">
                    <slot name="header">
                        <h2></h2>

                        <button type="button" class="btn-close-vue btn-right-vue" @click="close"
                                aria-label="Close modal">
                            x
                        </button>
                    </slot>
                </div>

                <section class="modal-body-vue">
                    <slot name="body">

                    </slot>
                </section>

                <div class="modal-footer-vue">
                    <slot name="footer">
                        <button type="button" class="btn btn-green" @click="close" aria-label="Close modal">
                            Закрыть
                        </button>
                    </slot>
                </div>
            </div>
        </div>
    </transition>
</script>


    <!--Comments-->
    <script type="text/x-template" id="comments-template">
        <div>
            <section class="comment">

                <div class="comment-title">
                    <h1>Отзывы</h1>
                </div>


                <div class="comment_public">
                    <div v-show="!comments__items.loading">
                        <div v-if="comments__items.items.length > 0">
                            <slot name="comments" :comments="comments__items.items"></slot>
                        </div>

                        <div v-else>
                            <p class="comments__no-element">Список отзывов пуст. Оставьте отзыв первым..</p>
                        </div>

                        <div class="comments_loading-more" @click="comments__changePage"
                            v-if="comments__items.loadMore.count">
                            <span>Загрузить ещё [{{ comments__items.loadMore.count }}]</span>
                            <img src="/uploads/other/reload.gif" alt="loading" v-if="comments__items.loadMore.loading">
                        </div>
                    </div>

                    <div class="comments__loading" v-show="comments__items.loading">
                        <img src="/uploads/other/loading.gif" alt="loading" width="167">
                    </div>


                </div>


                <div class="comment_form-wrapper">

                    <div class="comment_form">
                        <div class="comment-item_img--wrapper">
                            <div class="comment-item_img">
                                <img src="/uploads/images/company/comment.jpg">
                            </div>
                        </div>

                        <form action="#" class="form_comment">


                            <?php if (!\App::$users->user) : ?>
                                <div class="link_social">
                                    <p>ВОЙТИ С ПОМОЩЬЮ</p>
                                    <a href="https://oauth.vk.com/authorize?client_id=<?= vk_id ?>&display=page&redirect_uri=<?= vk_URL ?>&response_type=code"
                                    target="_blank" title="вконтакте" class="vk_link--comment">
                                        <i class="fab fa-vk"></i>
                                    </a>

                                </div>
                            <?php endif; ?>

                            <div class="form-title-star">
                                <div class="form-stars">
                                    <div class="rating rating2"><!--
                --><a href="#5" title="5" data-val="5" :class="5 <= commentAddForm.rate ? 'checked' : ''"
                    @click.prevent="changeRate"><i class="fas fa-star"></i></a><!--
                --><a href="#4" title="4" data-val="4" :class="4 <= commentAddForm.rate ? 'checked' : ''"
                    @click.prevent="changeRate"><i class="fas fa-star"></i></a><!--
                --><a href="#3" title="3" data-val="3" :class="3 <= commentAddForm.rate ? 'checked' : ''"
                    @click.prevent="changeRate"><i class="fas fa-star"></i></a><!--
                --><a href="#2" title="2" data-val="2" :class="2 <= commentAddForm.rate ? 'checked' : ''"
                    @click.prevent="changeRate"><i class="fas fa-star"></i></a><!--
                --><a href="#1" title="1" data-val="1" :class="1 <= commentAddForm.rate ? 'checked' : ''"
                    @click.prevent="changeRate"><i class="fas fa-star"></i></a>
                                    </div>
                                </div>

                                <?php if (!\App::$users->user) : ?>
                                    <div class="form-user_name">
                                        <input type="text" placeholder="Имя" class="form-star_user"
                                            v-model="commentAddForm.name">
                                        <div class="gates__form_error">
                                            {{ addError.name }}
                                        </div>
                                    </div>
                                    <div class="form-user_name">
                                        <input type="text" placeholder="Почта" class="form-star_user"
                                            v-model="commentAddForm.email">
                                        <div class="gates__form_error">
                                            {{ addError.email }}
                                        </div>
                                    </div>
                                <?php endif ?>

                            </div>


                            <textarea id="subject" name="subject" placeholder="Написать отзыв.."
                                    class="form_textarea-comment" v-model="commentAddForm.text"></textarea>
                            <div class="gates__form_error">
                                {{ addError.text }}
                            </div>
                            <div class="form-btn--wrapper">

                                <button class="form_comment--btn" @click.prevent="sendComment">Отправить</button>

                                <button type="submit" class="form_comment-btn_cancel"
                                        @click.prevent="commentAddForm.text = ''">Очистить
                                </button>

                            </div>
                        </form>
                    </div>


                </div>


            </section>
        </div>
    </script>

    <script type="text/x-template" id="comment-template">
        <transition name="test">
            <div class="comment-item">
                <div class="comment-item_img--wrapper">
                    <div class="comment-item_img">
                        <img src="/uploads/images/company/comment.jpg" :alt="comment.name">
                    </div>
                </div>

                <div class="comment-item_info">
                    <div class="comment-item_user--title">
                        <a class="comment-item_user-name"> {{ comment.name }} </a>
                        <span class="comment-item_user-data">{{ comment.date_added }}</span>
                    </div>

                    <div class="form-star">
                        <div class=" rating3"><!--
                                --><a href="#5" :class="5 <= comment.rate ? 'checked' : ''" title="5"><i
                                        class="fas fa-star"></i></a><!--
                                --><a href="#4" :class="4 <= comment.rate ? 'checked' : ''" title="4"><i
                                        class="fas fa-star"></i></a><!--
                                --><a href="#3" :class="3 <= comment.rate ? 'checked' : ''" title="3 "><i
                                        class="fas fa-star"></i></a><!--
                                --><a href="#2" :class="2 <= comment.rate ? 'checked' : ''" title="2 "><i
                                        class="fas fa-star"></i></a><!--
                                --><a href="#1" :class="1 <= comment.rate ? 'checked' : ''" title="1"><i
                                        class="fas fa-star"></i></a>
                        </div>
                    </div>

                    <div class="comment-item_user--comment">
                        <p>
                            {{ comment.comment }}
                        </p>
                    </div>

                </div>

            </div>

        </transition>
    </script>

    <!-- Gates Custom scripts -->
    <script src="/assets/gates_custom/scripts/custom.js?v=0.0.15"></script>
    <script type="text/javascript" src="/assets/gates_custom/scripts/components.js?v=0.0.15"></script>
    <script type="text/javascript" src="/assets/gates_custom/scripts/app.js?v=0.0.15"></script>


    <!-- Core -->
    <script src="/assets/vendor/jquery/jquery.min.js"></script>
    <script src="/assets/vendor/popper/popper.min.js"></script>
    <script src="/assets/js/bootstrap/bootstrap.min.js"></script>
    <!-- FontAwesome 5 -->
    <script src="/assets/vendor/fontawesome/js/fontawesome-all.min.js" defer></script>
    <!-- Theme JS -->
    <script src="/assets/js/theme.js"></script>

</body>

</html>