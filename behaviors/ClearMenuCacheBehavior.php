<?php

namespace jaclise\menu\behaviors;

use yii;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;

//清除菜单缓存行为
class ClearMenuCacheBehavior extends Behavior
{
	public $eventNames = [];
	
	public function init()
	{
		parent::init();
	
		if (empty($this->eventNames)) {
			$this->eventNames = [
					BaseActiveRecord::EVENT_AFTER_INSERT,
					BaseActiveRecord::EVENT_AFTER_UPDATE,
					BaseActiveRecord::EVENT_AFTER_DELETE,
			];
		}
	}
	
	public function events()
	{
		return array_fill_keys($this->eventNames, 'clearMenuCache');
	}
	
	public function clearMenuCache($event)
	{
		Yii::$app->menuCache->flush();
	}
}
