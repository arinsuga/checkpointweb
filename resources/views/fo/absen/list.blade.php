@foreach ($viewModel->data->users as $userItem)

    <div class="card">
        <div class="card-body table-responsive">
            @include('fo.absen.data-list-items')
        </div>
    </div>
    
@endforeach


