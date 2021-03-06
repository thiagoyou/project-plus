<?php
use yii\web\View;
use yii\helpers\Html;
use app\assets\AppAsset;
use app\models\Colaborador;

$base_path = \Yii::getAlias('@web');
$script = <<< JS
const BASE_PATH = '{$base_path}/';
JS;
$this->registerJs($script, View::POS_BEGIN);
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= \Yii::$app->language; ?>">
        <head>
            <meta charset="UTF-8"/>
            <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
            <meta name="viewport" content="width=device-width, initial-scale=1"/>
            <!-- ./meta -->
            <link rel="shortcut icon" href="<?= \Yii::$app->request->baseUrl; ?>/img/favicon-1.png" type="image/png" />
            <!-- ./favicon -->
            <?= Html::csrfMetaTags(); ?>
            <title><?= \Yii::$app->name . ' | ' . Html::encode($this->title); ?></title>
            <?php $this->head(); ?>
        </head>
        <!-- ./head -->
        <body>
            <?php $this->beginBody(); ?>
                <div id="main-content-wrapper" class="wrap">
                	<div class="main-header">
                        <nav id="main-navbar" class="navbar navbar-default navbar-fixed-top" role="navigation">
                			<div id="main-container" class="container">
                                <?= $this->render('menu'); ?>
                			</div>
                        </nav>
                    </div>
                    <!-- ./main menu -->
               		<div class="container-fluid">
               			<div class="sidenav">
                            <?php if (\Yii::$app->user->identity->cargo == Colaborador::CARGO_ADMINISTRADOR): ?>
                   				<?= $this->render('side-menu-admin'); ?>
                            <?php else: ?>
                            	<?= $this->render('side-menu-operario'); ?>
                            <?php endif; ?>
               			</div>
               			<!-- ./side menu -->
                        <section class="main-content">
                            <?php foreach (\Yii::$app->session->getAllFlashes() as $key => $message): ?>
								<div class="alert alert-flat alert-<?= $key ?> flash-msg">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?= $message ?>
                                </div>
                            <?php endforeach; ?>
                            <!-- ./flash-msg -->
                            <?= $content ?>
                            <!-- ./page-content -->
                        </section>
                        <!-- ./section-content -->                            
               		</div> 
               		<!-- ./main-content -->
            	</div>
                <!-- .content-wrapper -->
            <?php $this->endBody() ?>
        </body>
        <!-- ./body -->
    </html>
    <!-- ./html -->
<?php $this->endPage() ?>
<!-- ./file -->