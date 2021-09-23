<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DataTable extends Component {
    public $head;
    public $array;
    public $model;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($head, $array, $model) {
        $this->head = $head;
        $this->array = $array;
        $this->model = $model;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render() {
        return view('components.data-table');
    }
}
