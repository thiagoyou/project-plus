<?php
use yii\web\View;
use app\base\Helper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Colaborador;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Carteira;
?>
<?php $form = ActiveForm::begin(); ?>
	<div class="panel panel-primary panel-box">
		<div class="panel-body">
            <div class="row">
            	<div class="col-md-8 col-sm-8 col-lg-8 col-xs-12">
                    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]); ?>
                </div>
                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
                    <?= $form->field($model, 'cargo')->dropDownList(Colaborador::getListaCargos(), [
                            'disabled' => \Yii::$app->user->identity->cargo == Colaborador::CARGO_ADMINISTRADOR ? false : true,
                        ]); 
                    ?>
                </div>
			</div>
			<!-- ./row -->
			<div class="row">
				<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">        
            		<?= $form->field($model, 'username', ['enableClientValidation' => false])->textInput(['maxlength' => true, 'data-user' => $model->username]); ?>
            	</div>
        		<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12">
        			<input style="display: none;">
            		<?= $form->field($model, 'password', ['enableClientValidation' => false])->passwordInput(['maxlength' => true, 'data-hash' => $model->password, 'value' => $model->password]); ?>
            	</div>
            	<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12 <?= $model->cargo == Colaborador::CARGO_CLIENTE ? '' : 'd-none'; ?>">
                	<div class="col-carteira">
                		<?= $form->field($model, 'id_carteira')->widget(Select2::className(), [
        		                'data' => ArrayHelper::map(Carteira::find()->where([
        		                    'ativo' => Carteira::ATIVO,            
        		                ])->all(), 'id', 'nome'),
                                'disabled' => \Yii::$app->user->identity->cargo == Colaborador::CARGO_ADMINISTRADOR ? false : true,
        		                'pluginOptions' => [
                                    'allowClear' => true,
        		                ],
        		                'options' => [
                                    'placeholder' => 'Selecione ...',
        		                ],
                            ]); 
                        ?>
                	</div>
            	</div>
        	</div>
        	<!-- ./row -->
		</div>
		<!-- ./painel-body -->
        <div class="panel-footer">
    		<div class="row">
    			<div class="col-md-3 col-sm-4 col-lg-3 col-xs-6">
                    <div class="form-group">
                        <?= Html::submitButton('<i class="fa fa-save"></i>&nbsp; '. ($model->isNewRecord ? 'Cadastrar' : 'Alterar'), [
                                'class' => $model->isNewRecord 
                                ? Helper::BTN_COLOR_SUCCESS.' btn-block' 
                                : Helper::BTN_COLOR_PRIMARY.' btn-block',
                            ]);
                        ?>
                    </div>
    			</div>
    			<div class="col-md-3 col-sm-4 col-lg-3 col-xs-6 pull-right">
                    <div class="form-group">
                        <?= Html::a('<i class="fa fa-reply"></i>&nbsp; Voltar', ['/colaborador'], [
                                'class' => Helper::BTN_COLOR_DEFAULT.' btn-block',
                            ]);
                        ?>
                    </div>
    			</div>
    		</div>
    		<!-- ./row -->
        </div>
        <!-- ./panel-footer -->
	</div>
	<!-- ./painel-footer -->
<?php ActiveForm::end(); ?>
<!-- ./form -->
<?php 
$script = <<<JS
    $(document).ready(function() {
        // show/hide do select da carteira quando o cargo for alterado
        $('body').on('change', '#colaborador-cargo', function() {
            // 3 => cliente
            if (this.value == 3) {
                $('.col-carteira').slideDown();
            } else {
                $('.col-carteira').slideUp(function() {
                    $('#colaborador-id_carteira').val('').trigger('change');
                });
            }
        });

        // select all on focus
        $('#colaborador-password').on('click', function(e) {
           $(this).select();
        });

        // primeira verificação para remover o autofill
        $(window).on('load', function() {
            const passInput = $('#colaborador-password');
            if (passInput.data('hash') != undefined && passInput.data('hash').length > 0) {
                passInput.val(passInput.data('hash')).trigger('change');
            } else {
                passInput.val('').trigger('change');
            }
        });

        // segunda verificação para remover o autofill
        $('#colaborador-password').on('change', function(e) {
            const passInput = $(this);
            if (e.originalEvent === undefined) {
                setTimeout(function() {
                    if (passInput.val().length > 0 && passInput.data('hash') == undefined || passInput.val() != passInput.data('hash')) {
                        passInput.val('').trigger('change');
                    }
                }, 100);
                setTimeout(function() {
                    if (passInput.val().length > 0 && passInput.data('hash') == undefined || passInput.val() != passInput.data('hash')) {
                        passInput.val('').trigger('change');
                    }
                }, 300);
            }

            return true;
        });
    });
JS;
// JS
$this->registerJs($script, View::POS_READY);
?>

