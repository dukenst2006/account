@extends('layouts.master')

@section('title', 'Users')

@section('content')
    <div class="content">
        <div class="grid simple">
            <div class="grid-body dataTables_wrapper">
                <form method="get">
                <div class="row">
                    <div class="col-md-6 col-xs-4">
                        <h4 class="semi-bold">Users</h4>
                    </div>
                    <div class="input-group transparent col-md-4 col-md-offset-8 col-xs-8">
                        <input type="text" class="form-control" placeholder="Search by name or email" name="q" value="{{ Input::get('q') }}"/>
                        <span class="input-group-addon">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                </div>
                </form>
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th class="col-md-4 col-xs-12">Name</th>
                            <th class="col-md-4 col-xs-12 text-center">Email</th>
                            <th class="col-md-4 text-center hidden-sm hidden-xs">Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($users) > 0)
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <a href="/admin/users/{{ $user->id }}" class="semi-bold">
                                        @if($user->full_name != ' ')
                                            {{ $user->last_name }}, {{ $user->first_name }}
                                        @else
                                            [No Name]
                                        @endif
                                    </a>
                                </td>
                                <td class="text-center"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                                <td class="text-center hidden-sm hidden-xs"><a href="tel:+1{{ $user->phone }}">{{ Html::formatPhone($user->phone) }}</a></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                {!! $users->links() !!}
            </div>
        </div>
    </div>
@endsection