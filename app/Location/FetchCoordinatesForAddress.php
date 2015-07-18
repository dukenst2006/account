<?php namespace BibleBowl\Location;

use DatabaseSeeder;
use App;
use Log;
use Geocoder;
use BibleBowl\Address;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FetchCoordinatesForAddress implements ShouldQueue {

	use InteractsWithQueue;

	/**
	 * Handle the event.
	 *
	 * @param  Address  $address
	 * @return void
	 */
	public function handle(Address $address)
	{
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
                $address->save();
            } elseif ($response->status == 'ZERO_RESULTS') {
                return;
            }

            Log::error(
                'Problematic response from GMaps',
                [
                    'address' => $address,
                    'response' => (array)$response
                ]
            );
        } catch (\RuntimeException $e) {
            //ignore failures if we're local.
            //useful when seeding and not on the internet
            if (DatabaseSeeder::isSeeding() || (App::environment('local') && $e->getMessage() == 'cURL request returned following error: Could not resolve host: maps.googleapis.com')) {
                Log::debug('Suppressing error when fetching coordinates for address');
                return;
            }
            throw $e;
        }
	}

}
