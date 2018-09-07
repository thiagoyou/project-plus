<?php
use app\base\Util;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\typeahead\Typeahead;
?>
<div class="box">
	<div class="row">
		<div class="col-md-8 col-sm-8 col-lg-8 col-xs-12">
            <?php $form = ActiveForm::begin(['id' => 'form-filtro', 'method' => 'post']); ?>
            	<div class="box-header">
            		<h4><i class="fa fa-filter"></i>&nbsp; Opções de Filtro</h4>
        		</div>
            	<!-- ./box-header -->
               	<div class="box-body">       	
                	<div class="row">
            			<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                			<?= $form->field($model, 'nome')->widget(Typeahead::classname(),[
                                    'pluginOptions' => ['highlight' => true],
                                    'dataset' => [
                                       [
                                          'display'=> 'value',
                                          'notFound' => '<span class="alert alert-danger"><i class="fa fa-ban"></i>&nbsp; Nehum cliente foi encontrado ...</div>',
                                          'remote'=>[
                                              'url' => Url::to(['search-list']).'?q[nome]=%QUERY',
                                              'wildcard' => '%QUERY'
                                           ]
                                       ]
                                    ]
                                ]);
                            ?>	
                		</div>
            		</div>
            		<!-- ./row -->
            		<div class="row">
                    	<div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
                			<?= $form->field($model, 'telefone')->widget(Typeahead::classname(),[
                                    'pluginOptions' => ['highlight' => true],
                                    'dataset' => [
                                       [
                                          'display'=> 'value',
                                          'notFound' => '<span class="alert alert-danger"><i class="fa fa-ban"></i>&nbsp; Nehum cliente foi encontrado ...</div>',
                                          'remote'=>[
                                              'url' => Url::to(['search-list']).'?q[telefone]=%QUERY',
                                              'wildcard' => '%QUERY'
                                           ]
                                       ]
                                    ]
                                ]);
                            ?>	
                		</div>
                		<div class="col-md-6 col-sm-6 col-lg-6 col-xs-12">
                			<?= $form->field($model, 'documento')->widget(Typeahead::classname(),[
                                    'pluginOptions' => ['highlight' => true],
                                    'dataset' => [
                                       [
                                          'display'=> 'value',
                                          'notFound' => '<span class="alert alert-danger"><i class="fa fa-ban"></i>&nbsp; Nehum cliente foi encontrado ...</div>',
                                          'remote'=>[
                                              'url' => Url::to(['search-list']).'?q[documento]=%QUERY',
                                              'wildcard' => '%QUERY'
                                           ]
                                       ]
                                    ]
                                ]);
                            ?>	
                		</div>
            		</div>
            		<!-- ./row -->
            	</div>
            	<!-- ./box-body -->
            	<br>
            	<div class="box-footer">
            		<div class="row">
                		<div class="col-md-6 col-lg-6 col-xs-12 col-sm-6">
            				<?= Html::submitButton('<i class="fa fa-search"></i>&nbsp; Pesquisar', [
            				        'class' => Util::BTN_COLOR_PRIMARY.' btn-block',
            				    ]);
            				?>
                		</div>
                	</div>
            	</div>
            	<!-- ./box-footer -->
                <!-- ./box -->
            <?php ActiveForm::end(); ?>
            <!-- ./form -->
        </div>
        <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
        	<?php $form = ActiveForm::begin(['action' => Url::to('cliente/upload-excel'), 'options' => ['enctype' => 'multipart/form-data']]) ?>
        		<div class="box-header">
            		<h4><i class="fa fa-upload"></i>&nbsp; Upload de Arquivo Excel</h4>
        		</div>
        		<!-- ./box-header -->
        		<br><br>
        		<div class="box-body">  
        			<div class="row">
                		<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12">
                        	<?= $form->field($modelImport, 'fileImport')->fileInput(); ?>
                    	</div>
                	</div>
            	</div>
            	<!-- ./box-body -->
            	<br><br><br>
            	<div class="box-footer">
            		<div class="row">
                		<div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
            				<?= Html::submitButton('<i class="fa fa-paper-plane"></i>&nbsp; Enviar', [
            				        'class' => Util::BTN_COLOR_PRIMARY.' btn-block',
            				    ]);
            				?>
                		</div>
                	</div>
            	</div>
            	<!-- ./box-footer -->
            <?php ActiveForm::end(); ?>
            <!-- ./form -->
        </div>
    </div>
</div>
<!-- ./box -->