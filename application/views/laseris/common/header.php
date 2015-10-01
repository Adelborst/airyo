<!DOCTYPE html>
<html lang="ru">

<head>
    <title><?= @$page['title'] ? $page['title'] : 'ЛазерИнформСервис'?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8" />
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,700,600,800,300&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/themes/laseris/css/reset.css" />
    <link rel="stylesheet" href="/themes/laseris/css/style.css" />
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" />
	
	<?= @$this->css?>
	
    <!--[if lte IE 8]><link href= "/themes/laseris/css/ie.css" rel= "stylesheet" media= "all" /><![endif]-->

    <!--[if lt IE 9]>
		<script src="/themes/laseris/js/html5-ie.js"></script>
		<script src="/themes/laseris/js/respond.min.js"></script>
	<![endif]-->
    
    <script src="/themes/laseris/js/jquery-1.8.3.min.js"></script>
    <script src="/themes/laseris/js/jquery.placeholder.min.js"></script>
    <script src="/themes/laseris/js/script.js"></script>
    
    <link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico">
    <link rel="shortcut icon" href="favicon.ico">

    <body>
        <!-- wrap -->
        <div class="wrapper">
        <div class="wrap">
            <!-- header -->
            <header class="header">
                <div class="logo_text"><img src="/themes/laseris/img/main/logo_text.png" alt=""></div>
                <a href="/" class="logo_img"><img src="/themes/laseris/img/main/logo_img.png" alt=""></a>
                <div class="logo_pic"><img src="/themes/laseris/img/main/logo_pic2.jpg" alt=""></div>
                <div class="text">Информационные, консультационные и маркетинговые услуги Технология и оборудование для лазерной сварки - «под ключ»</div>
                <div class="contacts">
                    <div class="phone">+7 905 204 23 25 (СПб), +7 965 410 16 06 (по России)</div>      <div class="mail"><a href="mailto:laseris-spb@peterlink.ru">laseris-spb@peterlink.ru</a></div>
                </div>
            </header>
            <!-- /header -->
            <!-- nav -->
            <!-- <nav class="nav">
                <ul>
                	
                    <?
					if(is_array($mainmenu) && count($mainmenu)){
					foreach ($mainmenu as $item)
					{
					
					?>
						<li <? echo (($this->uri->uri_string() == $item['url'] or current_url() == $item['url']) ) ? 'class="active"' : ''; ?>>
							<a href="<?=$item['url']?>"><?=$item['name']?></a>
							<?
							if(is_array($item['childs']) && count($item['childs'])){
							?>
							<ul>
							<?
								foreach($item['childs'] as $childitem){
								?>
								<li><a href="<?=$childitem['url']?>"><?=$childitem['name']?></a></li>
								<?
								}
							?>
							</ul>
							<?
							}
							?>
						</li>
					<?
					}
					}
					?>
                </ul>
            </nav> -->
            <!-- /nav -->
            <!-- page -->
            <div class="page">
                <!-- content -->
                <div class="content">
                