<tr data-id="{{$playlist->id}}" class="playlist bg-info">
    <td class="row-count">0</td>
    <td>
        <input type="hidden" name="validate_playlist" value="ok">
        <input type="hidden" name="playlist[{{$playlist->id}}][id]" value="{{$playlist->id}}">
        <input type="hidden" name="playlist[{{$playlist->id}}][purchase]" value="{{$playlist->purchase}}">
        <input type="text" value="{{$playlist->desc}}" readonly class="form-control">
    </td>
    <td>
        <input type="number" readonly max="{{$playlist->qty}}" name="playlist[{{$playlist->id}}][qty]" value="{{$playlist->qty}}" step="any" min="1" class="form-control qty">
    </td>
    <td>
        <input type="number" value="{{$playlist->sale}}" name="playlist[{{$playlist->id}}][sale]" step="any" min="0" class="form-control sale">
    </td>
    <td>
        <input type="number" value="{{$playlist->sale*$playlist->qty}}" readonly step="any" min="0" class="form-control amount">
    </td>
    <td>
        <a href="#" id="remove-tr" class="badge badge-warning"><i class="icon-diff-removed"></i></a>
    </td>
</tr>