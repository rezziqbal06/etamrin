<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title><?=$this->getTitle()?></title>

    <meta name="description" content="<?=$this->getDescription()?>">
    <meta name="keyword" content="<?=$this->getKeyword()?>"/>
    <meta name="author" content="<?=$this->getAuthor()?>">
    <meta name="robots" content="<?=$this->getRobots()?>" />

    <!--== Favicon ==-->
    <link rel="apple-touch-icon" sizes="180x180" href="<?=$this->cdn_url()?>media/icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?=$this->cdn_url()?>media/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?=$this->cdn_url()?>media/icon/favicon-16x16.png">
    <link rel="manifest" href="<?=base_url()?>media/icon/site.webmanifest">
    <link rel="mask-icon" href="<?=$this->cdn_url()?>media/icon/safari-pinned-tab.svg" color="#f6efa2">
    <link rel="shortcut icon" href="<?=$this->cdn_url()?>media/icon/favicon.ico">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-config" content="<?=$this->cdn_url()?>media/icon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

    <!--== OG ==-->
    <meta itemprop="name" content="<?=$this->getTitle()?>">
    <meta itemprop="description" content="<?=$this->getDescription()?>">
    <meta name="twitter:title" content="<?=$this->getTitle()?>">
    <meta name="twitter:description" content="<?=$this->getDescription()?>">
    <meta property="og:title" content="<?=$this->getTitle()?>" />
    <meta property="og:description" content="<?=$this->getDescription()?>" />

    <!--== Google Fonts ==-->

    <!-- build:css assets/css/app.min.css -->
		<?php $this->getAdditionalBefore()?>
		<?php $this->getAdditional()?>
		<?php $this->getAdditionalAfter()?>
    <!-- endbuild -->
</head>
