<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DataSearch extends Component {
    public $model;
    public $btnCreateText;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($model, $btnCreateText) {
        $this->model = $model;
        $this->btnCreateText = $btnCreateText;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render() {
        return view('components.data-search');
    }
}
