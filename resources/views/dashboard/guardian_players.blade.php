<div class="row">
    <div class="col-md-12">
        <div class="tiles white m-b-10">
            <div class="tiles-body">
                <h4>Your <span class="semi-bold">Players</span></h4>
                <div class="row">
                    @foreach(Auth::user()->players as $index => $player)
                    <div class="col-md-3 @if($index > 0) b-l b-grey @endif p-l-20">
                        <h4 class="semi-bold">{{ $player->full_name }}</h4>
                        <p>{!! HTML::genderIcon($player->gender) !!} {{ $player->gender }}</p>
                        <p> {{ $player->age() }} years old</p>
                        <p class="text-center">
                            <a href="/player/{{ $player->id }}/edit">[ Edit ]</a>
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>