<tr data-id="{{$stock->id}}" class="stock">
    <td class="row-count">0</td>
    <td>
        <input type="hidden" name="validate_stock" value="ok">
        <input type="hidden" name="stock[{{$stock->id}}][id]" value="{{$stock->id}}">
        <input type="hidden" name="stock[{{$stock->id}}][purchase]" value="{{$stock->pur_value}}">
        <input type="text" value="{{$stock->product->desc}}" readonly class="form-control">
    </td>
    <td>
        <input type="number" max="{{$stock->stock_qty}}" name="stock[{{$stock->id}}][qty]" value="1" step="any" min="1" class="form-control qty">
    </td>
    <td>
        <input type="number" value="{{$stock->sale_value}}" name="stock[{{$stock->id}}][sale]" step="any" min="0" class="form-control sale">
    </td>
    <td>
        <input type="number" value="{{$stock->sale_value*1}}" readonly step="any" min="0" class="form-control amount">
    </td>
    <td>
        <a href="#" id="remove-tr" class="badge badge-warning"><i class="icon-diff-removed"></i></a>
    </td>
</tr>