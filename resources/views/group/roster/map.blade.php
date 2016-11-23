@extends('layouts.master')

@section('title', 'Player Map')

@section('content')
    <div class="overlayer overlay-fixed">
        <div class="overlayer-wrapper">
            <div class="content">
                <h2>Player <span class="semi-bold">Map</span></h2>
            </div>
        </div>
    </div>
    <div class="overlayer overlay-fixed top-right ">
        <div class="overlayer-wrapper">
            <div class="content">
                <div class="btn-group" data-toggle="buttons-radio">
                    <button type="button" class="btn btn-primary " id="map-zoom-in" ><i class="fa fa-plus"></i></button>
                    <button type="button" class="btn btn-primary" id="map-zoom-out"><i class="fa fa-minus"></i> </button>
                </div>
            </div>
        </div>
    </div>
    <div id="map" class="demo-map overlay-fixed"></div>
@endsection

@includeJs(https://maps.google.com/maps/api/js?sensor=true)
@includeJs(/assets/plugins/jquery-gmap/gmaps.js)
@js
    $(document).ready(function() {
        $('#map').height($('.page-container').height());
        $( window ).resize(function() {
            $('#map').height($('.page-container').height());
        });
        //Initialize Map
        map = new GMaps({
            el: '#map',
            lat: {{ $meetingAddress->latitude }},
            lng: {{ $meetingAddress->longitude }},
            zoom: 11,
            zoomControl : false,
            panControl : false,
            streetViewControl : false,
            mapTypeControl: true,
            overviewMapControl: false,
        });

        /* Group address, centered on this address */
        map.addMarker({
            lat: {{ $meetingAddress->latitude }},
            lng: {{ $meetingAddress->longitude }},
            icon: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png",
            title: "{{ $group->name }}",
            infoWindow: {
                content: "<h4 class='bold'>{{ $group->name }}</h4><p>{!! Html::address($meetingAddress) !!}</p><p>{!! Html::formatPhone($group->phone) !!}</p>"
            }
        });

        @foreach($guardians as $guardian)
            @if(isset($guardian->primaryAddress->latitude) && isset($guardian->primaryAddress->longitude))
            <?php
                $playerList = '<strong>Players</strong><ul>';
                foreach ($guardian->players as $player) {
                    $playerList  .= '<li>'.$player->full_name.'</li>';
                }
                $playerList .= '</ul>';
            ?>
            map.addMarker({
                lat: {{ $guardian->primaryAddress->latitude }},
                lng: {{ $guardian->primaryAddress->longitude }},
                icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png",
                title: "{{ $guardian->last_name }} Family",
                infoWindow: {
                    content: "<h4 class='bold'>{{ $guardian->last_name }} Family</h4>"+
                        "<p>{!! Html::address($guardian->primaryAddress) !!}</p>"+
                        "<p><i class='fa fa-envelope-o'></i> <a href='mailto:{{ $guardian->email }}'>{{ $guardian->email }}</a><br/>"+
                        @if (!is_null($guardian->phone))
                        "<i class='fa fa-phone'></i> <a href='tel:+1{{ $guardian->phone }}'>{!! Html::formatPhone($guardian->phone) !!}</a></p>"+
                        @endif
                        "{!! $playerList !!}"
                }
            });
            @endif
        @endforeach

        $("#map-zoom-out").click(function() {
            map.zoomOut(1);
        });
        $("#map-zoom-in").click(function() {
            map.zoomIn(1);
        });
    });
@endjs