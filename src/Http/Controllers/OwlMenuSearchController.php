<?php

namespace Slowlyo\OwlMenuSearch\Http\Controllers;

use Slowlyo\OwlAdmin\Admin;
use Slowlyo\OwlAdmin\Controllers\AdminController;

class OwlMenuSearchController extends AdminController
{
    public function index()
    {
        $q = request('q');

        if (blank($q)) {
            return $this->response()->success([]);
        }

        // 缓存 60s 避免频繁查库
        $list = cache()->remember('admin_menu_search', 60, function () {
            $menus = Admin::adminMenuModel()::query()->where('visible', 1)->orderBy('custom_order')->get()->toArray();

            return $this->format(array2tree($menus));
        });


        $result = [];
        foreach ($list as $item) {
            if (!str_contains($item['title'], $q) && !str_contains($item['url'], $q)) {
                continue;
            }

            $result[] = amis()->LinkAction()->className('w-full')->link($item['url'])
                ->body(
                    amis()->Card()->className('bg-gray-50 hover:border-primary')->header([
                        'title'    => $item['title'],
                        'subTitle' => $item['url'],
                    ])
                )
                ->onEvent([
                    'click' => [
                        'actions' => [
                            ['actionType' => 'closeDialog'],
                        ],
                    ],
                ]);
        }

        return $this->response()->success($result);
    }

    public function format($list, $parentTitle = '')
    {
        $result = [];
        foreach ($list as $item) {
            if (array_key_exists('children', $item)) {
                $result = array_merge($result, $this->format($item['children'], $item['title']));
                continue;
            }

            $item['title'] = $parentTitle ? $parentTitle . '/' . $item['title'] : $item['title'];
            $result[]      = $item;
        }

        return $result;
    }
}
