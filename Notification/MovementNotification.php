<?php

namespace Kanboard\Plugin\WechatWorkNotifier\Notification;

use Kanboard\Plugin\WechatWorkNotifier\Notification\BaseNotification;
use Kanboard\Plugin\WechatWorkNotifier\Model\MessageModel;
use Kanboard\Core\Notification\NotificationInterface;
use Kanboard\Model\TaskModel;

class MovementNotification extends BaseNotification implements NotificationInterface
{
    public function notifyUser(array $user, $eventName, array $eventData){}

    public function notifyProject(array $project, $eventName, array $eventData)
    {
        // Send task movemens to task members
        if ($eventName === TaskModel::EVENT_MOVE_PROJECT ||
            $eventName === TaskModel::EVENT_MOVE_COLUMN ||
            $eventName === TaskModel::EVENT_MOVE_POSITION ||
            $eventName === TaskModel::EVENT_MOVE_SWIMLANE
        ){
            $this->sendMessage(MessageModel::create(
                $audiences      = $this->getAudiences($project, $eventData, $assigneeOnly = false),
                $taskId         = $eventData["task"]["id"], 
                $title          = $eventData["task"]["project_name"], 
                $subTitle       = $eventData["task"]["title"], 
                $key            = $eventData["task"]["column_title"], 
                $desc           = t("Progress updated"), 
                $quote          = null, 
                $contentList    = array{
                    t("Assignee") => $eventData["task"]["assignee_username"]
                }, 
                $taskLink       = $this->getTaskLink($eventData["task"]["id"]), 
                $projectLink    = $this->getProjectLink($eventData["task"]["project_id"])
            ));
        }
    }
}
