<?php namespace BibleBowl\Location;

use App;
use BibleBowl\Address;
use DatabaseSeeder;
use Geocoder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

class FetchCoordinatesForAddress implements ShouldQueue
{

    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  Address  $address
     * @return void
     */
    public function handle(Address $address)
    {
        if (app()->environment('testing')) {
            app('log')->info('Not fetching coordinates for address while testing');
            return;
        }

        try {
            // object was serialized, so get a new one with DB connectivity
            $address = Address::findOrFail($address->id);

            $response = json_decode(
                Geocoder::geocode(
                    'json',
                    [
                        'address' => implode(
                            ' ',
                            [
                                $address->address_one,
                                $address->address_two,
                                $address->city,
                                $address->state,
                                $address->zip_code
                            ]
                        ),
                        'componets' => 'country:GB'
                    ]
                )
            );

            if ($response->status == 'OK') {
                $address->latitude = $response->results[0]->geometry->location->lat;
                $address->longitude = $response->results[0]->geometry->location->lng;

                # Figure out the city/state
                foreach ($response->results[0]->address_components as $addressParts) {
                    if (property_exists($addressParts, 'types') && is_array($addressParts->types)) {
                        if (in_array('administrative_area_level_1', $addressParts->types)) {
                            $address->state = $addressParts->short_name;
                        } elseif (in_array('locality', $addressParts->types) || in_array('administrative_area_level_3', $addressParts->types)) {
                            $address->city = $addressParts->long_name;
                        }
                    }
                }

                $address->save();

                $this->delete();
            } else if (DatabaseSeeder::isSeeding() === false && app()->environment('testing') === false) {
                Log::error(
                    'Problematic response from GMaps',
                    [
                        'address' => $address,
                        'response' => (array)$response
                    ]
                );
            }

        } catch (\RuntimeException $e) {
            //ignore failures if we're local.
            //useful when seeding and not on the internet
            if (DatabaseSeeder::isSeeding() || (App::environment('local', 'testing') && $e->getMessage() == 'cURL request retuened following error: Could not resolve host: maps.googleapis.com')) {
                Log::debug('Suppressing error when fetching coordinates for address');
                return;
            }
            throw $e;
        }
    }
}
