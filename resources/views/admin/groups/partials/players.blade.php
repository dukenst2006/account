<table class="table">
    <tr>
        <th>Name</th>
        <th class="text-center hidden-sm hidden-xs">Age</th>
        <th class="text-center hidden-sm hidden-xs">Gender</th>
        <th class="text-center hidden-xs">Grade</th>
        @if(isset($extraColumns))
            @foreach (array_keys($extraColumns) as $columnName)
                <th class="text-center">{{ $columnName }}</th>
            @endforeach
        @endif
        <th class="text-center hidden-xs">Parent/Guardian</th>
    </tr>
    @foreach ($players as $player)
        <tr>
            <td><a href="/admin/players/{{ $player->id }}">{{ $player->full_name }}</a></td>
            <td class="text-center hidden-sm hidden-xs">{{ $player->age() }}</td>
            <td class="text-center hidden-sm hidden-xs">{!! Html::genderIcon($player->gender) !!}</td>
            <td class="text-center hidden-xs">
                {{ \BibleBowl\Presentation\Describer::describeGradeShort($player->pivot->grade) }}
            </td>
            @if(isset($extraColumns))
                @foreach ($extraColumns as $pivotColumn)
                    <?php
                        // allow for some special syntax for customizable additional columns
                        // pivot->[COLUMN] - Displays a pivot column
                        // [TIMESTAMP_COLUMN]|date - Formats as a date
                        $start = strpos($pivotColumn, '>')+1;
                        if (starts_with($pivotColumn, 'pivot->')) {
                            $end = strlen($pivotColumn);
                            if (str_contains($pivotColumn, '|')) {
                                $end = strpos($pivotColumn, '|')-$start;
                            }
                            $newPivotColumn = substr($pivotColumn, $start, $end);

                            $columnValue = $player->pivot->{$newPivotColumn};
                        } else {
                            $columnValue = $player->{$pivotColumn};
                        }

                        if (ends_with($pivotColumn, '|date')) {
                            $columnValue = (new \Carbon\Carbon($columnValue))->timezone(Auth::user()->settings->timeszone())->format('F j, Y');
                        }
                    ?>

                    <td class="text-center">{{ $columnValue }}</td>
                @endforeach
            @endif
            <td class="text-center hidden-xs">
                <a href="/admin/users/{{ $player->guardian_id }}">{{ $player->guardian->full_name }}</a>
            </td>
        </tr>
    @endforeach
</table>