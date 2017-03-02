<?php

namespace App\Presentation;

use App\Address;
use Collective\Html\HtmlBuilder;
use Illuminate\Pagination\LengthAwarePaginator;

class Html extends HtmlBuilder
{
    // colors used in charts
    const ACCENT_COLORS = [
        '#8BC34A', // green
        '#FF9800', // orange
        '#BA68C8', // purple
        '#FF5252', // red
        '#42A5F5', // blue
        '#FFEA00', // yellow
    ];

    const GENDER_MALE = 'M';
    const GENDER_FEMALE = 'F';

    public static $includeJs = [];

    public static $includeCss = [];

    public static $js = '';

    /** @var string Data to pass to JS */
    public static $jsData = '';

    public static $css = '';

    /**
     * Render a gender icon.
     *
     * @param $gender
     *
     * @return string
     */
    public function genderIcon($gender)
    {
        if ($gender == self::GENDER_MALE) {
            return '<i class="fa fa-male"></i>';
        } elseif ($gender == self::GENDER_FEMALE) {
            return '<i class="fa fa-female"></i>';
        }
    }

    /**
     * Format a phone number.
     *
     * @param $phone
     *
     * @return string
     */
    public function formatPhone($phone)
    {
        return preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $phone);
    }

    /**
     * Format an address.
     *
     * @param $address
     *
     * @return string
     */
    public function address(Address $address)
    {
        $html = '<address>'.$address->address_one;

        if (!empty($address->address_two)) {
            $html .= '<br/>'.$address->address_two;
        }

        return $html.'<br/>'.$address->city.', '.$address->state.' '.$address->zip_code.'</address>';
    }

    public function pagination(LengthAwarePaginator $paginator)
    {
        $numStartedOnPage = $paginator->perPage() * ($paginator->currentPage() - 1);
        $numEndedOnPage = $numStartedOnPage + $paginator->perPage();
        if ($numStartedOnPage == 0) {
            $numStartedOnPage = 1;
        } elseif ($numStartedOnPage > 0) {
            $numStartedOnPage += 1;
        }
        if ($numEndedOnPage > $paginator->total()) {
            $numEndedOnPage = $paginator->total();
        }

        return '<div class="row">'.
                    '<div class="col-sm-6">Showing <b>'.number_format($numStartedOnPage).' to '.number_format($numEndedOnPage).'</b> of '.number_format($paginator->total()).' entries</div>'.
                    '<div class="col-sm-6 text-right">'.(new \App\Presentation\Pagination($paginator))->render().'</div>'.
                '</div>';
    }
}
