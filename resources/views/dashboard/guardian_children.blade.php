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
                $isRegisteredWithNBB = $child->isRegisteredWithNBB(Session::season());
                if ($isRegisteredWithNBB) {
                    $registration = $child->registration(Session::season());
                }
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
                    @if($isRegisteredWithNBB)
                        <td class="text-center v-align-middle">
                            {{ \BibleBowl\Presentation\Describer::describeGradeShort($registration->grade) }}
                        </td>
                        <td class="text-center v-align-middle">
                            {{ $registration->shirt_size }}
                        </td>
                    @else
                        <td class="text-center v-align-middle">-</td>
                        <td class="text-center v-align-middle">-</td>
                    @endif
                    <td class="text-center v-align-middle">
                        @if(is_null($group = $child->groupRegisteredWith(Session::season())) === false)
                            <div><div class="fa fa-check"></div> Registered with NBB</div>
                            {{ $group->name }}
                        @elseif($isRegisteredWithNBB)
                            <?php $program = \BibleBowl\Program::findOrFail($registration->program_id) ?>
                            <div><div class="fa fa-check"></div> Registered with NBB</div>
                            <a href="/join/{{ $program->slug }}/search/group">Find a group</a>
                        @else
                            <a href="/register/program">Register</a>
                        @endif
                    </td>
                    <td class="text-center v-align-middle">
                        <a href="/player/{{ $child->id }}/edit" class="fa fa-edit" id="edit-child-{{ $child->id }}"></a>
                    </td>
                </tr>
            @endforeach
            </thead>
        </table>
    </div>
</div>