<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<link rel="stylesheet" type="text/css" href="/assets/styles/bootstrap4/bootstrap.min.css">
<link href="/assets/plugins/fontawesome-free-5.0.1/css/fontawesome-all.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="/assets/plugins/OwlCarousel2-2.2.1/owl.carousel.css">
<link rel="stylesheet" type="text/css" href="/assets/plugins/OwlCarousel2-2.2.1/owl.theme.default.css">
<link rel="stylesheet" type="text/css" href="/assets/plugins/OwlCarousel2-2.2.1/animate.css">
<link rel="stylesheet" type="text/css" href="/assets/plugins/slick-1.8.0/slick.css">
<link href="/assets/plugins/icon-font/styles.css" rel="stylesheet" type="text/css">

<title><?= $this->title ?></title>
<meta name="description" content="<?= $this->description ?>">
<meta name="keywords" content="<?= $this->keywords ?>">
<meta name="author" content="<?= $this->author ?>">

<style>
    body, html {
        height: 100%;
        margin: 0;
    }

    a {
        color: #fff;
        text-decoration: none;
    }

    .bgimg {
        background-image: url('/uploads/other/coming_soon.jpg');
        height: 100%;
        background-position: center;
        background-size: cover;
        position: relative;
        color: white;
        font-family: "Courier New", Courier, monospace;
        font-size: 25px;
    }

    .topleft {
        position: absolute;
        top: 0;
        left: 16px;
    }

    .bottomleft {
        position: absolute;
        bottom: 0;
        left: 16px;
    }

    .middle {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    hr {
        margin: auto;
        width: 40%;
    }

    @media screen and (max-width: 480px) {
        .bgimg {
            font-size: 17px;
        }
    }
</style>
<body>

<div class="bgimg">

    <?php
    $logo = json_decode($this->comingSoon['cs_logo'])[0];
    ?>
    <?php if (!empty($logo)) : ?>
        <div class="topleft">
            <p><img src="/uploads/images/admin_settings/<?= $logo ?>" alt="" width="220"></p>
        </div>
    <?php endif; ?>

    <div class="middle">
        <h1><a href="/"><?= $this->comingSoon['cs_name'] ?></a></h1>
        <hr>


        <?php if (!empty($this->comingSoon['cs_full_description'])) : ?>
            <p><?= $this->comingSoon['cs_full_description'] ?></p>
        <?php endif; ?>
    </div>
    <div class="bottomleft">
        <?php if (!empty($this->comingSoon['cs_description'])) : ?>
            <p><?= $this->comingSoon['cs_description'] ?></p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
