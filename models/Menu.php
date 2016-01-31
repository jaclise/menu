<?php

namespace jaclise\menu\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use jaclise\menu\behaviors\ClearMenuCacheBehavior;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $url
 * @property integer $orderNum
 * @property string $permissionName
 * @property integer $parentId
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class Menu extends \yii\db\ActiveRecord
{
	protected $_permissions;
	
	protected $_menus;
	
	private $_translatedUrl;
	
	public $children;
	
	public $level;
	
	const TRANSLATE_KEY = '{0}';
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }
    
    public function behaviors()
    {
    	return [
                'timestamp'=>[
                    'class'=>TimestampBehavior::className(),
                ],
                'blameable'=>[
                    'class'=>BlameableBehavior::className(),
                ],
                // ClearMenuCacheBehavior::className(),
    	];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['orderNum', 'parentId', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['orderNum'], 'integer', 'min' => 1],
        	[['name'], 'string', 'max' => 45],
            [['description', 'url', 'permissionName', 'icon'], 'string', 'max' => 145]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'name' => '名称',
            'description' => '描述',
            'url' => '链接',
            'orderNum' => '顺序',
            'permissionName' => '权限',
            'parentId' => '父菜单',
            'icon' => '图标',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'created_by' => '创建者',
            'updated_by' => '修改者',
        ];
    }
    
    public function getPermissions()
    {
    	if ($this->_permissions === null) {
    		$this->_permissions = Yii::$app->authManager->getPermissions();
    	
//     		if ($this->permissionName !== null) {
//     			unset($this->_permissions[$this->permissionName]);
//     		}
    	}
    	return $this->_permissions;
    }

    /**
     * 获取所有的菜单
     *
     * @param boolean $filterWithPermission 是否进行权限过滤
    */ 
    public function getMenus()
    {
    	if ($this->_menus === null) {
    		$this->_menus = self::find()->all();
    		
			foreach ($this->_menus as $key => $menu)
			{
				if($menu->id == $this->id){
					unset($this->_menus[$key]);
					break;
				}
			}
    	}
    	return $this->_menus;
    }
    
    public function getTranslatedUrl()
    {
    	return strtr($this->url, [self::TRANSLATE_KEY => $this->id]);
    }
    
    private static function arrangeMenus($menus, $level = 1)
    {
    	$arrangeMenus = [];
    	foreach ($menus as $menu)
    	{
    		$menu->level = $level;
    		$arrangeMenus[] = $menu;
    		if($menu->children != null){
    			$arrangeMenus = $arrangeMenus + self::arrangeMenus($menu->children, $level + 1);
    		}
    	}
    	return $arrangeMenus;
    }
    
    public static function getSimpleMenusByParent($parentId = 0, $deep = true,$filterWithPermission=true)
    {
    	return self::arrangeMenus(self::getMenusByParent($parentId, $deep,$filterWithPermission));
    }
    
    public static function getMenusByParent($parentId = 0, $deep = true,$filterWithPermission=true)
    {
        // $menus = Yii::$app->menuCache->get(self::getMenusKey($parentId, $deep))
        //      ?: self::queryMenusByParent($parentId, $deep,$filterWithPermission);
        
        // Yii::$app->menuCache->set(self::getMenusKey($parentId, $deep), $menus, 300);
        
        // return $menus;
        return self::queryMenusByParent($parentId, $deep,$filterWithPermission);
    }
    
    private static function getMenusKey($parentId, $deep)
    {
    	return 'getMenus_' . $parentId . '_' . $deep;
    }

   /**
    *
    * 根据父id返回菜单，可以制定是否过滤掉当前登陆用户没有权限的菜单，如果过滤返回的只是用户有权限的menu
    */ 
    private static function queryMenusByParent($parentId = 0, $deep = true,$filterWithPermission=true)
    {
        $menus = self::find()->where(['parentId' => $parentId])->orderBy('orderNum')->all();

        if($filterWithPermission){
            $menus = array_filter($menus,"self::filterByPermission");
        }
        if($deep)
    	{
    		foreach ($menus as $menu)
    		{
    			$menu->children = self::queryMenusByParent($menu->id, $deep);
    		}
    	}
    	return $menus;
    }

    /**
     *
     * 一个array_filter的回调函数，只返回当前登陆用户有权限的menus
     *
     */
    public static function filterByPermission($menu)
    {
        return  \Yii::$app->user->can($menu->permissionName);
    }
}
