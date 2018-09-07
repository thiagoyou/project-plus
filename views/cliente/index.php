<?php
use app\base\Util;
use yii\helpers\Html;
use app\models\Cliente;
use kartik\grid\GridView;

$this->title = 'Clientes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-primary panel-box">
	<div class="panel-body">
		<?= $this->render('_filter', [
                'model' => $searchModel,
		        'modelImport' => $modelImport,
            ]);                
        ?>
	</div>
	<!-- ./filtro -->
	<div class="panel-body">
		<?php $columns = [
                [
                    'attribute' => 'id',
                    'hAlign' => GridView::ALIGN_CENTER,
                ],
                [
                    'attribute' => 'nome',
                    'width' => '40%'
                ],
				[
					'attribute' => 'documento',
					'label' => 'CPF/CNPJ',
					'value' => function($model) {
						if ($model->tipo == Cliente::TIPO_FISICO) {
							return Util::mask($model->documento, Util::MASK_CPF);
						}
						
						return Util::mask($model->documento, Util::MASK_CNPJ);
					}
				],
    		    [
                    'attribute' => 'sexo',
    		        'hAlign' => GridView::ALIGN_CENTER,
    		    ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}{delete}',
                ],
            ];
		
		    // grid
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => $columns,
                /* 'pjax' => true,
                'pjaxSettings' => [
                    'options' => [
                        'id' => 'grid-clientes',
                        'enablePushState'=>false
                    ],
                ], */
    		    'toolbar' => [
    		        ['content' => Html::a('<i class="fa fa-plus"></i>&nbsp; Cliente', ['create'], ['class' => Util::BTN_COLOR_EMERALD, 'title' => 'Cadastrar Novo Cliente', 'data-toggle' => 'tooltip',])],
    		        ['content'=> Html::a('<i class="fa fa-undo"></i>', ['index'], ['id' => '_LimparFiltro', 'class' => Util::BTN_COLOR_DEFAULT, 'data-toggle' => 'tooltip', 'title' => 'Limpar Filtros'])],
    		        '{toggleData}',
    		    ],
    		    'bordered' => true,
    		    'striped' => true,
    		    'condensed' => true,
    		    'responsive' => true,
    		    'hover' => true,
    		    'showPageSummary' => false,
    		    'persistResize' => false,
            ]);
       ?>
    </div>
</div>