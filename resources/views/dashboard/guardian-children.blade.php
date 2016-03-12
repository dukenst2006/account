 <div class="tiles white m-b-10">
    <div class="tiles-body">
        <div class="pull-left">
            <h4>Your <span class="semi-bold">Students</span></h4>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary btn-cons" href="/player/create">Add another student</a>
        </div>
        <table class="table no-more-tables" style="margin-bottom: 0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th class="text-center">Gender</th>
                    <th class="text-center">Age</th>
                    <th class="text-center">Grade</th>
                    <th class="text-center">T-Shirt Size</th>
                    <th class="text-center">{{ Session::season()->name }} Season</th>
                    <th class="text-center">Options</th>
                </tr>
            @foreach($children as $child)
                <?php
                $group = $child->groupRegisteredWith(Session::season());
                $isRegistered = $group !== null;
                ?>
                <tr>
                    <td class="v-align-middle">
                        {{ $child->full_name }}
                    </td>
                    <td class="text-center v-align-middle">
                        {!! HTML::genderIcon($child->gender) !!} {{ $child->gender }}
                    </td>
                    <td class="text-center v-align-middle">
                        {{ $child->age() }}
                    </td>
                    @if($isRegistered)
                        <td class="text-center v-align-middle">
                            {{ \BibleBowl\Presentation\Describer::describeGradeShort($group->pivot->grade) }}
                        </td>
                        <td class="text-center v-align-middle">
                            {{ $group->pivot->shirt_size }}
                        </td>
                        <td class="text-center v-align-middle">
                            <div><div class="fa fa-check"></div> Registered with {{ $group->name }}</div>
                        </td>
                    @else
                        <td class="text-center v-align-middle">-</td>
                        <td class="text-center v-align-middle">-</td>
                        <td class="text-center v-align-middle">
                            <a href="/register/players">Register</a>
                        </td>
                    @endif
                    <td class="text-center v-align-middle">
                        <a href="/player/{{ $child->id }}/edit" class="fa fa-edit" id="edit-child-{{ $child->id }}"></a>
                    </td>
                </tr>
            @endforeach
            </thead>
        </table>
    </div>
</div>