 <div class="tiles white m-b-10">
        <div class="tiles-body">
            <div class="pull-left">
                <h4>Your <span class="semi-bold">Children</span></h4>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary btn-cons" href="/player/create">Add another child</a>
            </div>
            <table class="table no-more-tables" style="margin-bottom: 0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th class="text-center">Gender</th>
                        <th class="text-center">Age</th>
                        <th class="text-center">{{ Session::season()->name }} Season</th>
                    </tr>
                @foreach($children as $child)
                    <tr>
                        <td class="v-align-middle">
                            {{ $child->full_name }} <a href="/player/{{ $child->id }}/edit" class="fa fa-edit"></a>
                        </td>
                        <td class="text-center v-align-middle">
                            {!! HTML::genderIcon($child->gender) !!} {{ $child->gender }}
                        </td>
                        <td class="text-center v-align-middle">
                            {{ $child->age() }}
                        </td>
                        <td class="text-center v-align-middle">
                            @if(is_null($group = $child->groupRegisteredWith(Session::season())) === false)
                                <div><div class="fa fa-check"></div> Registered with NBB</div>
                                {{ $group->name }}
                            @elseif($child->isRegisteredWithNBB(Session::season()))
                                <div><div class="fa fa-check"></div> Registered with NBB</div>
                                <a href="/group/search">Find a group</a>
                            @else
                                <a href="/group/search" id="registerPlayer-{{ $child->id }}">Register</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </thead>
            </table>
        </div>
    </div>