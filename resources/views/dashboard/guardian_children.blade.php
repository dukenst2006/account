 <div class="tiles white m-b-10">
        <div class="tiles-body">
            <div class="pull-left">
                <h4>Your <span class="semi-bold">Children</span></h4>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary btn-cons" href="/player/create">Add another child</a>
            </div>
            <table class="table no-more-tables" style="margin-bottom: 0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Age</th>
                    </tr>
                @foreach(Auth::user()->players as $index => $player)
                    <tr>
                        <td class="v-align-middle">
                            <a href="/player/{{ $player->id }}/edit">{{ $player->full_name }}</a>
                        </td>
                        <td class="v-align-middle">
                            {!! HTML::genderIcon($player->gender) !!} {{ $player->gender }}
                        </td>
                        <td class="v-align-middle">
                            {{ $player->age() }}
                        </td>
                    </tr>
                @endforeach
                </thead>
            </table>
        </div>
    </div>