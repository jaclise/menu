<?php

namespace jaclise\menu\controllers;

use Yii;
use jaclise\menu\models\Menu;
use jaclise\menu\models\MenuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use jaclise\common\backend\BackendController;

/**
 * MenuController implements the CRUD actions for menu model.
 */
class MenuController extends BackendController
{
    public function behaviors()
    {
        return array_merge( 
        		parent::behaviors(), 
        		[
		            'verbs' => [
		                'class' => VerbFilter::className(),
		                'actions' => [
		                    'delete' => ['post'],
		                ],
		            ],
		        ]);
    }

    /**
     * Lists all menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single menu model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Menu();
        $permissionArray = ArrayHelper::map($model->permissions, 'name', 'name');
        $menuArray = ArrayHelper::map($model->menus, 'id', 'name');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            	'permissionArray' => ['' => '请选择权限'] + $permissionArray,
            	'menuArray' => ['0' => '请选择父菜单'] + $menuArray,
            ]);
        }
    }

    /**
     * Updates an existing menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $permissionArray = ArrayHelper::map($model->permissions, 'name', 'name');
        $menuArray = ArrayHelper::map($model->menus, 'id', 'name');
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            	'permissionArray' => ['' => '请选择权限'] + $permissionArray,
            	'menuArray' => ['0' => '请选择父菜单'] + $menuArray,
            ]);
        }
    }

    /**
     * Deletes an existing menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
