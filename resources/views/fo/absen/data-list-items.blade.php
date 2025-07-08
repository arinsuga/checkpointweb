
<form role="form" target="_blank" id="frmData_{{ $userItem->user->id }}" method="POST" action="{{ route('absen.history.post') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="username" id="username" value="{{ $userItem->user->name }}" />
    <input type="hidden" name="history_media" id="history_media_{{ $userItem->user->id }}" />
    <input type="hidden" name="userid" id="userid" value="{{ $userItem->user->id }}"/>
    <input type="hidden" name="startdt" id="startdt" value="{{ $userItem->user->startdt }}"/>
    <input type="hidden" name="enddt" id="enddt" value="{{ $userItem->user->enddt }}" />
    <input type="hidden" name="tableName" id="tableName" value="filter_{{ $userItem->user->id }}" />
</form>

<button onclick="event.preventDefault();exportToPDF('frmData_{{ $userItem->user->id }}', 'history_media_{{ $userItem->user->id }}');" id="btnPDF_{{ $userItem->user->id }}" type="button" class="btn btn-block btn-danger btn-flat col-md-2">Export to PDF</button>
<table id="filter_{{ $userItem->user->id }}" class="table table-hover-pointer table-head-fixed">
<thead>
    @if (isset($userItem->user))
    <tr style="border: none;">
        <td colspan="4">
        <strong>Nama : {{ $userItem->user->name }}</strong>
        </td>
    </tr>
    @endif

    <tr>
        <th style="width: 15%;">Tanggal</th>
        <th style="width: 40%;">Checkin</th>
        <th style="width: 40%;">Checkout</th>
        <th style="width: 5%;"><div>Lama</div> <div>Kerja</div></th>
    </tr>
</thead>
<tbody>

    @if ($userItem)
        @foreach ($userItem->user->attend_list as $item)
            <tr onclick="window.location.assign('{{ route('absen.show', ['absen' => $item->id]) }}');">
                <td>{{ $item->attend_dt }}</td>
                <td>
                    <div>
                        <div><strong>Waktu</strong></div>
                        <div>{{ $item->checkin_time }}</div>
                    </div>
                    <div>
                        <div><strong>Deskripsi</strong></div>
                        <div>{{ $item->checkin_description }}</div>
                    </div>
                    <div>
                        <div><strong>Lokasi</strong></div>
                        <div>{{ $item->checkin_address }}</div>
                    </div>
                </td>
                <td>
                    <div>
                        <div><strong>Waktu</strong></div>
                        <div>{{ $item->checkout_time }}</div>
                    </div>
                    <div>
                        <div><strong>Deskripsi</strong></div>
                        <div>{{ $item->checkout_description }}</div>
                    </div>
                    <div>
                        <div><strong>Lokasi</strong></div>
                        <div>{{ $item->checkout_address }}</div>
                    </div>
                </td>
                <td>{{ $item->time_elapse }}</td>
            </tr>
        @endforeach
    @endif

</tbody>
</table>
