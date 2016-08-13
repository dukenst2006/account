<div class="grid simple">
    <div class="grid-title no-border">
        <h4 class="bold">Tournaments</h4>
    </div>
    <div class="grid-body no-border" style="padding-bottom:0;">
        <table class="table no-more-tables" style="margin-bottom: 0">
            <thead>
            <tr>
                <th>Name</th>
            </tr>
            @foreach($tournaments as $tournament)
                <tr>
                    <td class="v-align-middle">
                        <a href="/tournaments/{{ $tournament->slug }}">{{ $tournament->name }}</a>
                    </td>
                </tr>
            @endforeach
            </thead>
        </table>
    </div>
</div>