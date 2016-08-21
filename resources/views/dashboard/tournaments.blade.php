@if($tournaments->count() > 0)
    <div class="col-md-6">
        <div class="grid simple">
            <div class="grid-title no-border">
                <h4 class="bold">Tournaments</h4>
            </div>
            <div class="grid-body no-border" style="padding-bottom:0;">
                <table class="table" style="margin-bottom: 0">
                    @foreach($tournaments as $tournament)
                        <tr>
                            <td class="v-align-middle">
                                <a href="/tournaments/{{ $tournament->slug }}" class="bold">{{ $tournament->name }}</a>
                            </td>
                            <td class="v-align-middle text-center">
                                <a href="/tournaments/{{ $tournament->slug }}/group">Manage Registration</a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endif