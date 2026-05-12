<?php
namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class StudentLayout extends Component
{
    /**
     * The title for the page.
     *
     * @var string
     */
    public $title;

    /**
     * Create a new component instance.
     *
     * @param string $title
     * @return void
     */
    public function __construct($title = 'Laravel')
    {
        $this->title = $title;
    }

    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        return view('student.layouts.student');
    }
}
