<tr data-id="{{$stock_detail->id}}" class="sub_group">
    <td>
        <input type="hidden" name="product[{{$stock_detail->id}}][id]" value="{{$stock_detail->id}}" class="form-control">
        <input type="text" readonly name="product[{{$stock_detail->id}}][desc]" value="{{$stock_detail->product->desc}}" class="form-control">
    </td>
    <td>
        <input type="number" step="any" min="1" name="product[{{$stock_detail->id}}][per_group]" placeholder="Unit per group" class="form-control per_group">
    </td>
    <td>
        <input type="number" readonly step="any" min="1" name="product[{{$stock_detail->id}}][qty]" placeholder="ចំនួន" class="form-control qty">
    </td>
    <td>
        <a href="#" id="playlist-remove-row" class="badge badge-warning"><i class="icon-diff-removed"></i></a>
    </td>
</tr>