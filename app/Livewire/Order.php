<?php

namespace App\Livewire;

use Livewire\Component;

class Order extends Component
{
    /**
     * Render the Livewire component view.
     *
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        return view('livewire.order');
    }
}
