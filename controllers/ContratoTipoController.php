<?php
namespace app\controllers;

use Yii;
use app\models\ContratoTipo;
use app\models\ContratoTipoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Colaborador;
use yii\db\IntegrityException;

/**
 * ContratoTipoController implements the CRUD actions for ContratoTipo model.
 */
class ContratoTipoController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    /**
     * Valida a permissão do usuário com base no cargo
     *
     * @inheritDoc
     * @see \yii\web\Controller::beforeAction()
     */
    public function beforeAction($action)
    {
        if (\Yii::$app->user->identity->cargo != Colaborador::CARGO_ADMINISTRADOR) {
            throw new NotFoundHttpException();
        }
        
        return parent::beforeAction($action);
    }
    
    /**
     * Lists all ContratoTipo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContratoTipoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new ContratoTipo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ContratoTipo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ContratoTipo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ContratoTipo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // busca a model
        $model = $this->findModel($id);
        
        try {
            $transaction = \Yii::$app->db->beginTransaction();
            
            // deleta o registro
            $model->delete();
            
            $transaction->commit();
            \Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i>&nbsp; O tipo de contrato foi excluído com sucesso.');
        } catch (IntegrityException $e) {
            $transaction->rollBack();
            \Yii::$app->session->setFlash('danger', '<i class="fa fa-exclamation-triangle"></i>&nbsp; O tipo de contrato não pode ser deletada pois possui dados vinculados.');
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Erros: {$e->getMessage()}");
        }
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the ContratoTipo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContratoTipo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContratoTipo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException();
    }
}
