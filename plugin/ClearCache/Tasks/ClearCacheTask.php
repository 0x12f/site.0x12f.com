<?php declare(strict_types=1);

namespace Plugin\ClearCache\Tasks;

use App\Domain\AbstractTask;
use App\Domain\Service\Notification\NotificationService;
use App\Domain\Service\Task\TaskService;

class ClearCacheTask extends AbstractTask
{
    public const TITLE = 'Очистка кеша';

    public function execute(array $params = []): \App\Domain\Entities\Task
    {
        $default = [];
        $params = array_merge($default, $params);

        return parent::execute($params);
    }

    protected function action(array $args = []): void
    {
        $this->logger->info('ClearCache: remove cache files');
        @exec('rm -rf ' . CACHE_DIR . '/*');

        if ($this->parameter('ClearCachePlugin_tasks', 'off') === 'on') {
            $this->logger->info('ClearCache: remove task data');
            $taskService = TaskService::getWithContainer($this->container);

            foreach (
                $taskService->read([
                    'status' => [
                        \App\Domain\Types\TaskStatusType::STATUS_DONE,
                        \App\Domain\Types\TaskStatusType::STATUS_CANCEL,
                        \App\Domain\Types\TaskStatusType::STATUS_FAIL,
                        \App\Domain\Types\TaskStatusType::STATUS_DELETE,
                    ],
                ]) as $model
            ) {
                $taskService->delete($model);
            }
        }

        if ($this->parameter('ClearCachePlugin_notify', 'off') === 'on') {
            $this->logger->info('ClearCache: remove notify');
            $notificationService = NotificationService::getWithContainer($this->container);

            foreach ($notificationService->read() as $model) {
                $this->entityManager->remove($model);
            }
        }

        if ($this->parameter('ClearCachePlugin_log', 'off') === 'on') {
            $this->logger->info('ClearCache: remove log files');
            @exec('rm -rf ' . LOG_DIR . '/*');
        }

        $this->setStatusDone();
    }
}
