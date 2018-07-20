<table class="table">
  <thead class="thead-light">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Date</th>
      <th scope="col">Amount</th>
      <th scope="col">Rate</th>
      <th scope="col">Gross</th>
      <th scope="col">Fee</th>
      <th scope="col">Net</th>
      <th scope="col">Tokens</th>
      <th scope="col">Bonus</th>
      <th scope="col">Status</th>
    </tr>
  </thead>
  <tbody>
    @if(count($orders) > 0)
        @foreach($orders as $order)
        <tr>
          <th scope="row">
              {{ $order->id }}
          </th>
          <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
          <td>{{ $order->amount }} {{ strtoupper($order->currency->short_title) }}</td>
          <td>{{ $order->rate }} $</td>
          <td>{{ $order->gross }} $</td>
          <td>{{ $order->fee }} $</td>
          <td>{{ ($order->net) ? $order->net.' $' : '' }}</td>
          <td>{{ $order->tokens }}</td>
          <td>{{ $order->bonus }}</td>
          <td>
              <p>
                  <a href="{{ $order->invoice }}">Invoice</a>
              </p>
              <span class="badge {{ $order->status->cssClass() }}">{{ $order->status->title }}</span>
          </td>
        </tr>
        @endforeach
    @else:
    <tr>
        <td colspan="11">
            Your transaction history is empty.
        </td>
    </tr>
    @endif
  </tbody>
</table>
