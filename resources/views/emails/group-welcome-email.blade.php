@extends('emails.simple')

@section('body')
    <?php
        // Serialized objects need to be re-instantiated in order
        // to have a successful database connection
        $primaryAddress = \BibleBowl\Address::findOrFail($guardian['primary_address_id']);
        $group = \BibleBowl\Group::findOrFail($groupId);
        $playerCount = count($players);
    ?>

    @if ($hasEmailBody)
        @include('emails.theme.text-block', [
            'body' => $emailBody
        ])

        @include('emails.theme.empty-spacer')
    @endif

    @include('emails.theme.header', [
        'header' => 'Registration Confirmation'
    ])

    <table width="600" align="center" cellpadding="0" cellspacing="0" bgcolor="#f4f4f4" border="0" class="table600">
        <tr>
            <!--=========== JUST ENTER YOUR INFO HERE =========-->
            <td valign="middle" align="center" bgcolor="#f4f4f4" height="10" class="sectionRegularInfoTextTD" style="border-collapse: collapse;color: #6e777e;font-family: Arial, Tahoma, Verdana, sans-serif;font-size: 13px;font-weight: lighter;padding: 0;margin: 0;text-align: left;line-height: 165%;letter-spacing: 0;">

                @include('emails.theme.text-block', [
                    'body' => 'Please double check the below information to ensure everything is correct.  If you find a mistake, you can '.EmailTemplate::link(url('login'), 'login to your Bible Bowl account').' and correct the mistake.'
                ])

                <!--================== BEGIN SHOWING GUARDIAN/PLAYER INFO =================-->
                <table align="left" cellpadding="0" cellspacing="0" bgcolor="#f4f4f4" border="0">
                    <tbody>
                    <tr>
                        <td colspan="2"><br/>
                            <strong>{{ $guardian['full_name'] }}</strong>
                            @include('partials.address', [
                                'address' => $primaryAddress
                            ])
                            {{ $guardian['email'] }}
                            @if(isset($guardian['phone']) && !empty($guardian['phone']))
                                <br/><a href="tel:{{ $guardian['phone'] }}">{{ HTML::formatPhone($guardian['phone']) }}</a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><br/>
                            <h2>Players</h2>
                        </td>
                    </tr>
                    <tr>
                        @foreach ($players as $idx => $player)
                            <td valign="top" bgcolor="#f4f4f4" style="border-collapse: collapse; width: 300px">
                                <table width="280" align="center" cellpadding="0" cellspacing="0" bgcolor="#f4f4f4" border="0" class="table280">
                                    <tr>
                                        <td valign="top" align="center" height="10" bgcolor="#f4f4f4" class="sectionRegularInfoTextTD" style="border-collapse: collapse;color: #6e777e;font-family: Arial, Tahoma, Verdana, sans-serif;font-size: 13px;font-weight: lighter;padding: 0;margin: 0;text-align: left;line-height: 165%;letter-spacing: 0;">
                                            <strong>{{ $player['full_name'] }}</strong><br/>
                                            Gender: {{ $player['gender'] }}<br/>
                                            Age: {{ $player->age() }}<br/>
                                            Grade: {{ \BibleBowl\Presentation\Describer::describeGrade($grades[$player['id']]) }}<br/>
                                            T-Shirt size: {{ \BibleBowl\Presentation\Describer::describeShirtSize($shirtSizes[$player['id']]) }}
                                            <br/><br/>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>

                            @if((($idx+1) % 2) == 0)
                                </tr>
                                <tr style="margin-top: 1em;">
                            @endif
                        @endforeach
                    </tr>
                    </tbody>
                </table>

            </td>
            <!--================ End of the section ============-->
        </tr>
    </table>
@endsection