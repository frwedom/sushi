<!-- <?= password_hash('kdje28smn', PASSWORD_DEFAULT); ?> -->

<!doctype html>
<html lang="<?= \App::$language->getCurrentLanguage() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
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

    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css'
          integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>

    <!-- Gates Custom styles -->
    <link rel="stylesheet" href="/assets/gates_custom/styles/custom.css?v=0.0.15">

    <!-- vue / axios -->
    <script type='text/javascript' src='/assets/gates_custom/libraries/vue.min.js' rel="prefetch"></script>
    <script type='text/javascript' src='/assets/gates_custom/libraries/axios.min.js'></script>
    <script type='text/javascript' src='/assets/gates_custom/libraries/axios.min.js'></script>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Prata&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/assets/css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/animate.css">

    <link rel="stylesheet" href="/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="/assets/css/magnific-popup.css">

    <link rel="stylesheet" href="/assets/css/aos.css">

    <link rel="stylesheet" href="/assets/css/ionicons.min.css">

    <link rel="stylesheet" href="/assets/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="/assets/css/nouislider.css">


    <link rel="stylesheet" href="/assets/css/flaticon.css">
    <link rel="stylesheet" href="/assets/css/icomoon.css">
    <link rel="stylesheet" href="/assets/css/style.css">


</head>
<body>

<div id="app">

    <div class="main-section">

        <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
            <div class="container">
                <a class="navbar-brand" href="/home/">
                    <?php if ($header_logo['images']): ?>
                        <a href="/home/">
                            <img src="/uploads/images/company/<?= json_decode($header_logo['images'])[0] ?>"
                                 width="100">
                        </a>
                    <?php endif ?>
                
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
                        aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="oi oi-menu"></span> Меню
                </button>
                <div class="collapse navbar-collapse" id="ftco-nav">
                    <ul class="navbar-nav ml-auto">
                        <?php print_r($this->layoutParams) ?>
                        <?php if (!empty($this->layoutParams['social'])): ?>

                            <?php foreach ($this->layoutParams['social'] as $soc): ?>
                                <li class="nav-item"><a href="<?= $soc['link'] ?>"
                                                        class="nav-link icon d-flex align-items-center"><i
                                                class="<?= $soc['icon'] ?>"></i></a></li>
                            <?php endforeach ?>

                        <?php endif ?>

                    </ul>
                </div>
            </div>
        </nav>
        <!-- END nav -->

        <?= $body ?>


        <footer class="ftco-section ftco-section-2">
            <div class="col-md-12 text-center">
                <p class="mb-0">
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    Copyright &copy;<script>
                        document.write(new Date().getFullYear());

                    </script>
                    All rights reserved
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                </p>
            </div>
        </footer>

    </div>

    <!-- loader -->
    <div id="ftco-loader" class="show fullscreen">
        <svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/>
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10"
                    stroke="#F96D00"/>
        </svg>
    </div>
</div>

<!--<div id="app">-->
<!---->
<!--    <div>-->
<!---->
<!--    </div>-->
<!---->
<!--Notifications-->
<!--    <notifications>-->
<!--        <transition-group name="notification" tag="div">-->
<!--            <notification v-for="notification in notifications" v-bind:key="notification" :notification="notification"-->
<!--                          @close="removeNotification(notification)"></notification>-->
<!--        </transition-group>-->
<!--    </notifications>-->
<!---->
<!--</div>-->


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


                        <?php if (!\App::$users->user): ?>
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

                            <?php if (!\App::$users->user): ?>
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


<script src="/assets//js/jquery.min.js"></script>
<script src="/assets//js/jquery-migrate-3.0.1.min.js"></script>
<script src="/assets//js/popper.min.js"></script>
<script src="/assets//js/bootstrap.min.js"></script>
<script src="/assets//js/jquery.easing.1.3.js"></script>
<script src="/assets//js/jquery.waypoints.min.js"></script>
<script src="/assets//js/jquery.stellar.min.js"></script>
<script src="/assets//js/owl.carousel.min.js"></script>
<script src="/assets//js/jquery.magnific-popup.min.js"></script>
<script src="/assets//js/aos.js"></script>

<script src="/assets//js/nouislider.min.js"></script>
<script src="/assets//js/moment-with-locales.min.js"></script>
<script src="/assets//js/bootstrap-datetimepicker.min.js"></script>
<script src="/assets//js/main.js"></script>

</body>
</html>