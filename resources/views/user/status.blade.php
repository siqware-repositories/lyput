@if($status->status=='pending')
    <span class="badge badge-info">{{$status->status}}</span>
@elseif($status->status=='active')
    <span class="badge badge-success">{{$status->status}}</span>
@elseif($status->status=='inactive')
    <span class="badge badge-danger">{{$status->status}}</span>
@endif
