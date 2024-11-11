<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css"
          href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css<?= defined('YII_DEBUG') ? '?' . rand() : ''; ?>"/>
    <script type="module" src="/js/notificationBar.js"></script>
</head>

<body>
<div class="body_background">
    <div class="wrapper">
        <div class="site-box">
            <header class="header" id="header"></header><!--.header-->
            <div class="main" id="main">
                <section class="middle">
                    <main class="content full registration" role="main">
                        <?php echo $content; ?>
                    </main>
                </section><!--.middle-->
            </div><!--.main-->
        </div>
    </div><!--.wrapper -->
</div>
<!--****GROUP****-->

<?$appearance = Appearance::model()->find();?>
<script>
    let bodyBackground = document.getElementsByClassName('body_background')[0];
    let appearance = <?echo json_encode($appearance->attributes)?>;

    switch (appearance.background_image_type) {
        case 'rotate':
        case 'image':
        case 'link': {
            bodyBackground.style.backgroundImage = 'url(' + appearance.background_image_type_value + ')';
            break;
        }
        //градиент
        default: {
            bodyBackground.style.background = appearance.background_image_type_value;
            break;
        }

    }
    console.log(appearance)
    //$appearance
</script>
</body>
</html>
<? Yii::app()->clientScript->registerScriptFile('/js/jquery-3.2.1.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile('/js/jquery.formstyler.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile('/js/jquery.fancybox.pack.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile('/js/jquery.validate.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile('/js/jquery.bxslider.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile('/js/mask.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile('/js/main.js', CClientScript::POS_HEAD);
//Yii::app()->clientScript->registerScriptFile('/js/notificationBar.js', CClientScript::POS_END);

