<?php namespace BibleBowl\Support;

use Log;
use Geocoder;
use BibleBowl\Address;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class FetchCoordinatesForAddress implements ShouldBeQueued {

	use InteractsWithQueue;

	/**
	 * Handle the event.
	 *
	 * @param  Address  $address
	 * @return void
	 */
	public function handle(Address $address)
	{
		// object was serialized, so get a new one with DB connectivity
		$address = Address::findOrFail($address->id);

		$response = json_decode(Geocoder::geocode('json', [
			'address' => implode(' ', [
				$address->address_one,
				$address->address_two,
				$address->city,
				$address->state,
				$address->zip_code
			]),
			'componets' => 'country:GB'
		]));

		if ($response->status == 'OK') {
			$address->latitude = $response->results[0]->geometry->location->lat;
			$address->longitude = $response->results[0]->geometry->location->lng;
			$address->save();
		} elseif ($response->status == 'ZERO_RESULTS') {
			return;
		}

		Log::error('Problematic response from GMaps', [
			'address' => $address,
			'response' => (array) $response
		]);
	}

}
