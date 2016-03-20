<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
            }

            .content {
                display: inline-block;
                margin 0 auto;
                width:50%;
            }

            .title {
                font-size: 40px;
                text-align: center;
            }
            p {
                font-size: 25px;
                text-align: left;
            }
            
            table {
                text-align:left;
            }
            
            th, td {
                border:none;
                border-bottom: 1px solid #ddd; 
            }
            
            th {
                font-weight: 300;
                font-size:22px;
                background-color: #eee;
            }
                
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div id="cheapestRoomType_list">
                    <h2 class="title">Cheapest date per Room Type</h2>
                    <table style="width:100%">
                        <tr>
                            <th>Room Type Name</th>
                            <th>Date</th>
                            <th>Rate</th>
                        </tr>
                        @if($cheapestRoomTypes !== null)
                            @foreach($cheapestRoomTypes as $cheapestRoomType)
                                <tr>
                                    <td>{{ $cheapestRoomType->name }}</td>
                                    <td>{{ $cheapestRoomType->date }}</td>
                                    <td>{{ $cheapestRoomType->rate }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colespan="3">no results</td>
                            </tr>
                        @endif
                    </table>
                </div>
                <div id="cheapestRoomType_single">
                    <h2 class="title">Cheapest Room Type</h2>
                    @if($cheapestRoomTypes !== null)
                        <p>Room Type Name: {{ $roomType->name }}</p>
                        <p>Date: {{ $roomType->date }}</p>
                        <p>Rate: {{ $roomType->rate }}</p>
                    @else
                        No results available
                    @endif
                </div>
            </div>
        </div>
    </body>
</html>
