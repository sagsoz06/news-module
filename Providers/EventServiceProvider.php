<?php namespace Modules\News\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Media\Events\Handlers\HandleMediaStorage;
use Modules\Media\Events\Handlers\RemovePolymorphicLink;
use Modules\News\Events\PostWasCreated;
use Modules\News\Events\PostWasDeleted;
use Modules\News\Events\PostWasUpdated;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
      PostWasUpdated::class => [
          HandleMediaStorage::class
      ],
      PostWasCreated::class => [
          HandleMediaStorage::class
      ],
      PostWasDeleted::class => [
          RemovePolymorphicLink::class
      ]
    ];
}