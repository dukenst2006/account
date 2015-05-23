<?php namespace BibleBowl\Presentation;

class EmailTemplate
{
    /**
     * Generate a link
     *
     * @param      $url
     * @param null $label
     *
     * @return string
     */
    public function link($url, $label = null)
    {
        if (is_null($label)) {
            $label = $url;
        }

        return '<a href="'.$url.'" target="_blank" class="sectionRegularInfoTextTDLink" style="color: #a8b0b9;text-decoration: underline;outline: none;font-weight: bold;">'.$label.'</a>';
    }
}