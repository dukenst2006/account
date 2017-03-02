<html>
    <head>
        <style>
            @page {
                margin-top: 20px;
                margin-bottom: 50px;
            }
            body {
                font-size: 8pt;
            }
            .footer {
                position: fixed;
                bottom: 0px;
                width: 100%;
            }
            .title {
                padding-left: 5px;
                font-size: 22pt;
                font-weight: bold;
                margin-top: .5em;
                margin-bottom: 1px;
            }
            .group {
                padding-left: 5px;
                font-weight: bold;
                margin-bottom: 5px;
            }
            .grid {
                margin-top: 2em;
                border: 0px;
                border-collapse: collapse;
            }
            .cell {
                vertical-align: top;
                border: 0.0001em solid #000;
            }
            .header {
                width: 164px;
                font-weight: bold;
                text-align: center;
                font-size: 9.5pt;
                background-color: #eee;
                border-bottom: 0.0001em solid #000;
                padding: 4px;
            }
            .players {
                text-align: center;
                padding: 4px 4px 8px 4px;
                line-height: 190%;
            }
            .row {
                margin-top: 1em;
            }
            .text-right {
                text-align: right;
            }
            .text-center {
                text-align: center;
            }
            .text {
                color: #696969;
            }
            .footer {
                font-style: italic;
            }
            .divider {
                border-top: 1px solid #000;
            }
        </style>
    </head>
    <body>
        <div class="title">{{ $teamSet->name }}</div>
        <div class="group">{{ $teamSet->group->name }}</div>
        <div class="divider"></div>
        <table class="grid">
            <tr class="row">
            @foreach($teamSet->teams as $index => $team)
                @if($index > 1 && $index % 4 == 0)
            </tr>
        </table>
        <table class="grid">
            <tr>
                @endif
                <td class="cell">
                    <div class="header">{{ $team->name }}</div>
                    <div class="players">
                        @if($team->players->count() > 0)
                            @foreach($team->players as $player)
                                {{ $player->full_name }} ({{ \App\Presentation\Describer::describeGradeShort($player->seasons()->wherePivot('season_id', $teamSet->season_id)->first()->pivot->grade) }})<br/>
                            @endforeach
                        @else
                            &nbsp; <!-- Ensure there's some spacing -->
                        @endif
                    </div>
                </td>
            @endforeach
            </tr>
        </table>
        <table class="footer">
            <tr>
                <td width="30%"></td>
                <td width="30%" class="text text-center" style="line-height: 140%">
                    {{ Config::get('app.title') }}<br/>
                    {{ Config::get('app.url') }}
                </td>
                <td width="30%" class="text text-right">Last updated: {{ $lastUpdated->format('M j, Y') }}</td>
            </tr>
        </table>
    </body>
</html>