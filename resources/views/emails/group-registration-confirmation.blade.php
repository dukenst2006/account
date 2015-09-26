@extends('emails.simple')

@section('body')
    <?php
        $primaryAddress = \BibleBowl\Address::findOrFail($guardian['primary_address_id']);
        $group = App::make(\BibleBowl\Group::class, [$group]);
        $playerCount = count($players);
    ?>

    @include('emails.theme.header', [
        'header' => 'Player Registration Confirmation'
    ])

    @include('emails.theme.text-block', [
        'body' => ($playerCount > 1 ? 'A few players have' : 'A player has').' just registered for <strong>'. $group->name .' ('. $group->program->name .')</strong>.  You can see where they live in relation to where your group meets on the '. EmailTemplate::link(url('roster/map'), 'Player Map') .'.'
    ])

    <table width="600" align="center" cellpadding="0" cellspacing="0" bgcolor="#f4f4f4" border="0" class="table600">
        <tr>
            <!--=========== JUST ENTER YOUR INFO HERE =========-->
            <td valign="middle" align="center" bgcolor="#f4f4f4" height="10" class="sectionRegularInfoTextTD" style="border-collapse: collapse;color: #6e777e;font-family: Arial, Tahoma, Verdana, sans-serif;font-size: 13px;font-weight: lighter;padding: 0;margin: 0;text-align: left;line-height: 165%;letter-spacing: 0;">


                <!--================== BEGIN SHOWING GUARDIAN/PLAYER INFO =================-->
                <table align="left" cellpadding="0" cellspacing="0" bgcolor="#f4f4f4" border="0">
                    <tbody>
                    <tr>
                        <td><br/>
                            <strong>{{ $guardian['full_name'] }}</strong>
                            @include('partials.address', [
                                'address' => $primaryAddress
                            ])
                            <a href="mailto:{{ $guardian['email'] }}">{{ $guardian['email'] }}</a>
                            @if(isset($guardian['phone']) && !empty($guardian['phone']))
                                <br/><a href="tel:{{ $guardian['phone'] }}">{{ HTML::formatPhone($guardian['phone']) }}</a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><br/>
                            <h2>Players</h2>
                        </td>
                    </tr>
                    <tr>
                        @foreach ($players as $player)
                            <td valign="top" bgcolor="#f4f4f4" style="border-collapse: collapse;">
                                <table width="280" align="center" cellpadding="0" cellspacing="0" bgcolor="#f4f4f4" border="0" class="table280">
                                    <tr>
                                        <td valign="top" align="center" height="10" bgcolor="#f4f4f4" class="sectionRegularInfoTextTD" style="border-collapse: collapse;color: #6e777e;font-family: Arial, Tahoma, Verdana, sans-serif;font-size: 13px;font-weight: lighter;padding: 0;margin: 0;text-align: left;line-height: 165%;letter-spacing: 0;">
                                            <strong>{{ $player['full_name'] }}</strong><br/>
                                            Gender: {{ $player['gender'] }}<br/>
                                            Age: {{ $player['age'] }}<br/>
                                            Grade: {{ \BibleBowl\Presentation\Describer::describeGrade($player['grade']) }}<br/>
                                            T-Shirt size: {{ \BibleBowl\Presentation\Describer::describeShirtSize($player['shirt_size']) }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        @endforeach
                    </tr>
                    </tbody>
                </table>

            </td>
            <!--================ End of the section ============-->
        </tr>
        @include('emails.unsubscribe-notifications')
    </table>
@endsection