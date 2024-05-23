@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    <div class="vms_panel">
        test
    </div>
    <div class="hubers_panel">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <h6>Clients</h6>
                            <form class="d-flex" action="/clients" method="GET" >
                                <input name="search" placeholder="Search" class="form-control mb-0 px--2 inline-block" style="margin-right: 8px;" type="text">
                                <input class="btn btn-primary text-white inline-block ml-2 mb-0" type="submit" value="Search">
                            </form>
                        </div>

                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        @if(count($clients) > 0)
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                            Name</th>
                                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                            Email</th>
                                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                            Phone Number</th>
                                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                            Additional Phone Number</th>
                                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                            </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($clients as $client)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-3">
                                                        <h6 class="mb-0">{{$client->name}}</h6>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex px-3">
                                                        <h6 class="mb-0 text-sm">{{$client->email}}</h6>
                                                    </div>
                                                </td>
                                                <td class="">
                                                    <div class="d-flex px-3">
                                                        <h6 class="mb-0 text-sm">{{$client->phone_number}}</h6>
                                                    </div>
                                                </td>
                                                <td class="">
                                                    <div class="d-flex px-3">
                                                        <h6 class="mb-0 text-sm">{{$client->additional_phone_number}}</h6>
                                                    </div>
                                                </td>
                                                <td class="">
                                                    <a href="{{route('clients.view',['id'=>$client->id])}}">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <h6 class="text-center">There are no clients currently</h6>
                        @endif
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
