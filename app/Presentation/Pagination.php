<?php namespace BibleBowl\Presentation;

class Pagination extends \Landish\Pagination\Pagination
{
    protected $paginationWrapper = '<span class="pagination"><ul>%s %s %s</ul></span>';

    protected $activePageWrapper = '<li class="active"><a href="#">%s</a></li>';

    protected $previousButtonText = '<i class="fa fa-chevron-left"></i>';

    protected $nextButtonText = '<i class="fa fa-chevron-right"></i>';

    protected function getPreviousButton()
    {
        $replacements = [
            'disabled'  => 'disabled prev',
            '<li>'      => '<li class="prev">',
            'span'      => 'a'
        ];
        return str_replace(array_keys($replacements), array_values($replacements), parent::getPreviousButton());
    }

    protected function getNextButton()
    {
        $replacements = [
            'disabled'  => 'disabled next',
            '<li>'      => '<li class="next">',
            'span'      => 'a'
        ];
        return str_replace(array_keys($replacements), array_values($replacements), parent::getNextButton());
    }
}