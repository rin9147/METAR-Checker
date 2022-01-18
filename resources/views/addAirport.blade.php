<h2>airport</h2>

<table border="1">
    <tr>
        <th>ID</th>
        <th>NAME</th>
    </tr>
    @foreach($airport_list->data->Station as $station)
    <tr>
        <td>{{$station->station_id}}</td>
        <td>{{$station->site}}</td>
    </tr>
    @endforeach
</table>