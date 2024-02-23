<?php

namespace Slowlyo\OwlMenuSearch;

use Slowlyo\OwlAdmin\Admin;
use Slowlyo\OwlAdmin\Extend\ServiceProvider;

class OwlMenuSearchServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();

        Admin::prependNav($this->searchBtn());
    }

    public function searchBtn()
    {
        return amis()
            ->DialogAction()
            ->icon('fa fa-search')
            ->level('link')
            ->iconClassName('text-gray-700 text-xl hover:text-gray-700')
            ->hotKey('command+k,ctrl+k')
            ->dialog(
                amis()
                    ->Dialog()
                    ->title()
                    ->actions([])
                    ->closeOnEsc()
                    ->closeOnOutside()
                    ->showCloseButton(false)
                    ->body(
                        amis()->Form()->wrapWithPanel(false)->autoFocus()->body([
                            amis()->TextControl('keywords', false)
                                ->placeholder(self::trans('menu-search.keywords'))
                                ->description(self::trans('menu-search.hot_key')),
                            amis()->Service()
                                ->className('max-h-80 overflow-auto')
                                ->schemaApi('get:/menu-search?q=${keywords}'),
                        ])
                    )
            );
    }
}
