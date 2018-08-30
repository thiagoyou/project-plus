<?php
namespace app\controllers;

use app\models\Cliente;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use app\models\ClienteSearch;
use yii\web\NotFoundHttpException;

/**
 * ClienteController implements the CRUD actions for Cliente model.
 */
class ClienteController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Cliente models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClienteSearch();
        
        // seta os params do filtro
        if (!$params = \Yii::$app->request->post()) {
            $params = \Yii::$app->request->queryParams;
        }

        // realiza o filtro
        $dataProvider = $searchModel->search($params);
        
        // model do import file
        $modelImport = new \yii\base\DynamicModel(['fileImport' => 'File Import']);
        $modelImport->addRule(['fileImport'],'required');
        $modelImport->addRule(['fileImport'], 'file', ['extensions' => 'ods,xls,xlsx']);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'modelImport' => $modelImport
        ]);
    }

    /**
     * Cadastra um registro
     */
    public function actionCreate()
    {
        $model = new Cliente();
        
        if ($post = \Yii::$app->request->post()) {
        	$model->load($post);
        	if (!$model->save()) {
        		// TODO implementar mensagem de erro
        	}
        	
        	return $this->redirect(['index']);
        }
          
        return $this->render('create', [
        	'model' => $model,
        ]);
        
    }

    /**
     * Altera um registro
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($post = \Yii::$app->request->post()) {
        	$model->load($post);
        	if (!$model->save()) {
        		// TODO implementar mensagem de erro
        	}
	        
            return $this->redirect(['index']);
        }
        
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * TODO
     * Realiza o upload e processamento de um arquivo Excel
     */
    public function actionUploadExcel() 
    {
    	// model do import file
    	$modelImport = new \yii\base\DynamicModel(['fileImport' => 'File Import']);
    	$modelImport->addRule(['fileImport'],'required');
    	$modelImport->addRule(['fileImport'], 'file', ['extensions' => 'ods,xls,xlsx']);
		
    	$modelImport->fileImport = UploadedFile::getInstance($modelImport,'fileImport');
    	
    	if ($modelImport->fileImport && $modelImport->validate()) {
    		/* $inputFileType = \PHPExcel_IOFactory::identify($modelImport->fileImport->tempName);
    		$objReader = \PHPExcel_IOFactory::createReader($inputFileType);
    		$objPHPExcel = $objReader->load($modelImport->fileImport->tempName);
    		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
    		$baseRow = 3;
    		
    		while (!empty($sheetData[$baseRow]['B'])) {
    			var_dump((string)$sheetData[$baseRow]['B']);
    			$baseRow++;
    		} */
    		
    		var_dump('to aqui');
    	} else {
    		var_dump($modelImport->errors);
    	}
    	
    	die;	
    }
    
    /**
     * Deleta um registro
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Busca um cliente por ajax typeahead
     */
    public function actionSearchList(array $q)
    {
        $data = [];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = Cliente::find();
        
        if (isset($q['nome'])) { $query->select('nome')->andWhere(['like', 'nome', $q['nome']])->distinct(true); }
        if (isset($q['telefone'])) { $query->select('telefone')->andWhere(['like', 'telefone', $q['telefone']])->distinct(true); }
        if (isset($q['documento'])) { $query->select('documento')->andWhere(['like', 'documento', $q['documento']])->distinct(true); }
        
        $model = $query->all();
        
        if ($model != null) {
            foreach ($model as $key) {
                if (isset($q['nome'])) { $data[]['value'] = $key['nome']; }
                if (isset($q['telefone'])) { $data[]['value'] = $key['telefone']; }
                if (isset($q['documento'])) { $data[]['value'] = $key['documento']; }
            }
        }
        
        return $data;
    }
    
    /**
     * Busca um registro
     */
    protected function findModel($id)
    {
        if (($model = Cliente::findOne($id)) !== null) {
            return $model;
        }
        
        throw new NotFoundHttpException();
    }
}
