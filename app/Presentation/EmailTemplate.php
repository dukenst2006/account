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

        return '<a href="'.$url.'" target="_blank" class="sectionRegularInfoTextTDLink" style="color: #0d638f;text-decoration: underline;outline: none;">'.$label.'</a>';
    }
}
