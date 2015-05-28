<div class="row">
    <div class="col-md-12">
        <div class="tiles white m-b-10">
            <div class="tiles-body">
                <h4>Your <span class="semi-bold">Players</span></h4>
                <div class="row">
                    @foreach(Auth::user()->players as $player)
                    <div class="col-md-3">
                        <h3>{{ $player->full_name }}</h3>
                        <p>Shirt size: {{ $player->shirt_size }}</p>
                        <br>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>