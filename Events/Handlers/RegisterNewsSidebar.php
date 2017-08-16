<?php

namespace Modules\News\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Sidebar\AbstractAdminSidebar;

class RegisterNewsSidebar extends AbstractAdminSidebar
{
    /**
     * @param Menu $menu
     *
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(trans('core::sidebar.content'), function (Group $group) {
            $group->item(trans('news::news.title.news'), function (Item $item) {

                $item->icon('fa fa-copy');
                $item->weight(0);
                $item->item(trans('news::post.title.post'), function (Item $item) {
                    $item->icon('fa fa-copy');
                    $item->weight(0);
                    $item->append('admin.news.post.create');
                    $item->route('admin.news.post.index');
                    $item->authorize(
                        $this->auth->hasAccess('news.posts.index')
                    );
                });
                $item->item(trans('news::category.title.category'), function (Item $item) {
                    $item->icon('fa fa-file-text');
                    $item->weight(1);
                    $item->route('admin.news.category.index');
                    $item->append('admin.news.category.create');
                    $item->authorize(
                        $this->auth->hasAccess('news.categories.index')
                    );
                });
                $item->authorize(
                    $this->auth->hasAccess('news.tags.index') || $this->auth->hasAccess('news.posts.index') || $this->auth->hasAccess('news.categories.index')
                );
            });
        });

        return $menu;
    }
}
