 <div class="tiles white m-b-10">
    <div class="tiles-body">
        <div class="pull-left">
            <h4>Your <span class="semi-bold">Students</span></h4>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary btn-cons" href="/player/create">Add another student</a>
        </div>
        <table class="table" style="margin-bottom: 0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th class="text-center hidden-xs hidden-sm">Gender</th>
                    <th class="text-center hidden-xs hidden-sm">Age</th>
                    <th class="text-center">Grade</th>
                    <th class="text-center hidden-xs hidden-sm">T-Shirt Size</th>
                    <th class="text-center">{{ $season->name }} Season</th>
                    <th class="text-center">Options</th>
                </tr>
            <?php $playersNotRegistered = false; ?>
            @foreach($children as $child)
                <?php
                $group = $child->groupRegisteredWith(Session::season());
                $isRegistered = $group !== null;
                ?>
                <tr>
                    <td class="v-align-middle">
                        {{ $child->full_name }}
                    </td>
                    <td class="text-center v-align-middle hidden-xs hidden-sm">
                        {!! HTML::genderIcon($child->gender) !!} {{ $child->gender }}
                    </td>
                    <td class="text-center v-align-middle hidden-xs hidden-sm">
                        {{ $child->age() }}
                    </td>
                    @if($isRegistered)
                        <td class="text-center v-align-middle">
                            {{ \BibleBowl\Presentation\Describer::describeGradeShort($group->pivot->grade) }}
                        </td>
                        <td class="text-center v-align-middle hidden-xs hidden-sm">
                            {{ $group->pivot->shirt_size }}
                        </td>
                        <td class="text-center v-align-middle">
                            <div><div class="fa fa-check"></div> Registered with {{ $group->name }}</div>
                        </td>
                    @else
                        <?php $playersNotRegistered = true; ?>
                        <td class="text-center v-align-middle">-</td>
                        <td class="text-center v-align-middle hidden-xs hidden-sm">-</td>
                        <td class="text-center v-align-middle">
                            @can(BibleBowl\Ability::REGISTER_PLAYERS)
                                <a href="/register/players">Register</a>
                            @endcan
                        </td>
                    @endif
                    <td class="text-center v-align-middle">
                        <a href="/player/{{ $child->id }}/edit" class="fa fa-edit" id="edit-child-{{ $child->id }}"></a>
                    </td>
                </tr>
            @endforeach
            </thead>
        </table>
        @if($playersNotRegistered)
            <div class="alert alert-info text-center m-t-15">
            @if($hasGroupToRegisterWith)
                Once you've added all your students, <a href="/register/players" style="text-decoration: underline">register them with {{ $groupToRegisterWith->name }}</a> for the {{ $season->name }} season.
            @else
                Don't forget to register your students for this season with your local group by clicking "Register".
            @endif
            </div>
        @endif
    </div>
</div>