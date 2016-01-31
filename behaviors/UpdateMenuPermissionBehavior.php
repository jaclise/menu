<?php

namespace jaclise\menu\behaviors;

use yii\base\Behavior;
use botwave\rbac\rbac\DbCacheManager;
use botwave\menu\models\Menu;

//更新菜单权限行为
class UpdateMenuPermissionBehavior extends Behavior
{
	public $eventNames = [];
	
	public function events()
	{
		return array_fill_keys($this->eventNames, 'handleUpdate');
	}
	
	public function handleUpdate($event)
	{
		switch ($event->name)
		{
			case DbCacheManager::EVENT_UPDATE_PERMISSION_After:
				if($event->oldName != $event->item->name)
				{
					$this->updateMenuPermission(
						Menu::find()->where(['permissionName' => $event->oldName])->all(),
						 $event->item->name);
				}
				break;
			case DbCacheManager::EVENT_DEL_PERMISSION_After:
				$this->updateMenuPermission(
					Menu::find()->where(['permissionName' => $event->item->name])->all(), '');
				break;
			case DbCacheManager::EVENT_DEL_ALL_PERMISSION_After:
				$this->updateMenuPermission(
					Menu::find()->where('permissionName <> \'\' ')->all(), '');
				break;
		}
	}
	
	private function updateMenuPermission($menus, $newName)
	{
		foreach ($menus as $menu)
		{
			$menu->permissionName = $newName;
			$menu->save();
		}
	}

}
