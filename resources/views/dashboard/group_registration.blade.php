 <div class="tiles white m-b-10">
        <div class="tiles-body">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <h4>Groups <span class="semi-bold">Nearby</span></h4>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="input-group transparent col-md-6 col-md-offset-6 col-sm-6 col-sm-offset-6 col-xs-9 col-xs-offset-3">
                        <input type="text" class="form-control" placeholder="Search all groups">
                      <span class="input-group-addon">
                       <i class="fa fa-search"></i>
                      </span>
                    </div>
                </div>
            </div>
            <table class="table no-more-tables" style="margin-bottom: 0">
                <thead>
                @foreach($nearbyGroups as $group)
                    <tr>
                        <td class="v-align-middle">
                            {{ $group->name }}
                        </td>
                        <td>
                            <a href="/seasons/register/{{ $group->id }}">Register {{ Session::season()->name }}</a>
                        </td>
                    </tr>
                @endforeach
                </thead>
            </table>
        </div>
    </div>