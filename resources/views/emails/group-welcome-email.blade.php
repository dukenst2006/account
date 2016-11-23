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

    <table cellpadding="0" cellspacing="0" bgcolor="#f4f4f4" border="0">
        <tr>
            <!--=========== JUST ENTER YOUR INFO HERE =========-->
            <td valign="middle" bgcolor="#f4f4f4" height="10" class="sectionRegularInfoTextTD" style="border-collapse: collapse;color: #42484c;font-family: Arial, Tahoma, Verdana, sans-serif;font-size: 13px;font-weight: lighter;padding: 0;margin: 0;text-align: left;line-height: 165%;letter-spacing: 0;">

                @include('emails.theme.text-block', [
                    'body' => 'Please double check the below information to ensure everything is correct.  If you find a mistake, you can '.EmailTemplate::link(url('login'), 'login to your Bible Bowl account').' and correct the mistake.'
                ])

                <br/>
                <strong>{{ $guardian['full_name'] }}</strong>
                @include('partials.address', [
                    'address' => $primaryAddress
                ])
                {{ $guardian['email'] }}
                @if(isset($guardian['phone']) && !empty($guardian['phone']))
                    <br/><a href="tel:{{ $guardian['phone'] }}">{{ Html::formatPhone($guardian['phone']) }}</a>
                @endif

                @foreach ($players as $player)
                    <p>
                    <strong>{{ $player['full_name'] }}</strong><br/>
                    Gender: {{ $player['gender'] }}<br/>
                    Age: {{ $player->age() }}<br/>
                    Grade: {{ \BibleBowl\Presentation\Describer::describeGrade($grades[$player['id']]) }}<br/>
                    T-Shirt size: {{ \BibleBowl\Presentation\Describer::describeShirtSize($shirtSizes[$player['id']]) }}
                    <br/>
                    </p>
                @endforeach

            </td>
            <!--================ End of the section ============-->
        </tr>
    </table>
@endsection